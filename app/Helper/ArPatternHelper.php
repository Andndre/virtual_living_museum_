<?php

namespace App\Helper;

use RuntimeException;

class ArPatternHelper
{
  private const PATTERN_SIZE = 16;

  public static function encodeImageToPattern(string $imagePath): string
  {
    if (! function_exists('imagecreatefromstring')) {
      throw new RuntimeException('Ekstensi GD tidak tersedia untuk membuat file pattern AR.');
    }

    $imageContents = @file_get_contents($imagePath);
    if ($imageContents === false) {
      throw new RuntimeException('Gagal membaca file gambar marker.');
    }

    $sourceImage = @imagecreatefromstring($imageContents);
    if ($sourceImage === false) {
      throw new RuntimeException('Format gambar marker tidak didukung.');
    }

    $baseImage = null;

    try {
      $baseImage = imagecreatetruecolor(self::PATTERN_SIZE, self::PATTERN_SIZE);
      if ($baseImage === false) {
        throw new RuntimeException('Gagal menyiapkan kanvas pattern AR.');
      }

      $white = imagecolorallocate($baseImage, 255, 255, 255);
      imagefill($baseImage, 0, 0, $white);
      imagecopyresampled(
        $baseImage,
        $sourceImage,
        0,
        0,
        0,
        0,
        self::PATTERN_SIZE,
        self::PATTERN_SIZE,
        imagesx($sourceImage),
        imagesy($sourceImage)
      );

      $blocks = [];
      foreach ([0, -90, -180, -270] as $rotation) {
        $rotatedImage = self::rotateImage($baseImage, $rotation);

        try {
          $blocks[] = self::encodeOrientation($rotatedImage);
        } finally {
          imagedestroy($rotatedImage);
        }
      }

      return implode("\n", $blocks) . "\n";
    } finally {
      if ($baseImage !== null) {
        imagedestroy($baseImage);
      }

      imagedestroy($sourceImage);
    }
  }

  private static function rotateImage(\GdImage $image, int $rotation): \GdImage
  {
    $white = imagecolorallocate($image, 255, 255, 255);
    $rotated = imagerotate($image, $rotation, $white);

    if ($rotated === false) {
      throw new RuntimeException('Gagal memutar gambar marker untuk pattern AR.');
    }

    if (imagesx($rotated) === self::PATTERN_SIZE && imagesy($rotated) === self::PATTERN_SIZE) {
      return $rotated;
    }

    $normalized = imagecreatetruecolor(self::PATTERN_SIZE, self::PATTERN_SIZE);
    if ($normalized === false) {
      imagedestroy($rotated);
      throw new RuntimeException('Gagal menormalkan ukuran pattern AR.');
    }

    imagefill($normalized, 0, 0, $white);
    imagecopyresampled(
      $normalized,
      $rotated,
      0,
      0,
      0,
      0,
      self::PATTERN_SIZE,
      self::PATTERN_SIZE,
      imagesx($rotated),
      imagesy($rotated)
    );

    imagedestroy($rotated);

    return $normalized;
  }

  private static function encodeOrientation(\GdImage $image): string
  {
    $rows = [];

    foreach (['blue', 'green', 'red'] as $channel) {
      for ($y = 0; $y < self::PATTERN_SIZE; $y++) {
        $values = [];

        for ($x = 0; $x < self::PATTERN_SIZE; $x++) {
          $rgb = imagecolorat($image, $x, $y);
          $values[] = str_pad((string) self::extractChannel($rgb, $channel), 3, ' ', STR_PAD_LEFT);
        }

        $rows[] = implode(' ', $values);
      }
    }

    return implode("\n", $rows);
  }

  private static function extractChannel(int $rgb, string $channel): int
  {
    return match ($channel) {
      'red' => ($rgb >> 16) & 0xFF,
      'green' => ($rgb >> 8) & 0xFF,
      default => $rgb & 0xFF,
    };
  }
}
