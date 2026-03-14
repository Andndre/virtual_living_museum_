<?php

namespace App\Helper;

use RuntimeException;

class ArPatternHelper
{
  private const FULL_MARKER_SIZE = 512;
  private const PATTERN_SIZE = 16;
  private const WHITE_MARGIN = 0.1;
  private const DEFAULT_PATTERN_RATIO = 0.5;

  public static function encodeImageToPattern(string $imagePath): string
  {
    self::ensureGdAvailable();
    $sourceImage = self::loadImage($imagePath);

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

  public static function buildFullMarkerPng(
    string $imagePath,
    float $patternRatio = self::DEFAULT_PATTERN_RATIO,
    int $size = self::FULL_MARKER_SIZE,
    string $borderColor = 'black'
  ): string {
    self::ensureGdAvailable();

    if ($size < 128) {
      throw new RuntimeException('Ukuran marker minimal 128 piksel.');
    }

    if ($patternRatio <= 0 || $patternRatio >= 1) {
      throw new RuntimeException('Pattern ratio harus berada di antara 0 dan 1.');
    }

    $sourceImage = self::loadImage($imagePath);
    $canvas = null;

    try {
      $canvas = imagecreatetruecolor($size, $size);
      if ($canvas === false) {
        throw new RuntimeException('Gagal menyiapkan kanvas marker AR.');
      }

      [$borderR, $borderG, $borderB] = self::resolveBorderColor($borderColor);
      $white = imagecolorallocate($canvas, 255, 255, 255);
      $blackBorder = imagecolorallocate($canvas, $borderR, $borderG, $borderB);

      imagefill($canvas, 0, 0, $white);

      $whiteMargin = self::WHITE_MARGIN;
      $blackMargin = (1 - 2 * $whiteMargin) * ((1 - $patternRatio) / 2);
      $innerMargin = $whiteMargin + $blackMargin;

      $outerX = (int) round($whiteMargin * $size);
      $outerY = (int) round($whiteMargin * $size);
      $outerW = max(1, (int) round((1 - 2 * $whiteMargin) * $size));
      $outerH = max(1, (int) round((1 - 2 * $whiteMargin) * $size));

      imagefilledrectangle(
        $canvas,
        $outerX,
        $outerY,
        $outerX + $outerW - 1,
        $outerY + $outerH - 1,
        $blackBorder
      );

      $innerX = (int) round($innerMargin * $size);
      $innerY = (int) round($innerMargin * $size);
      $innerW = max(1, (int) round((1 - 2 * $innerMargin) * $size));
      $innerH = max(1, (int) round((1 - 2 * $innerMargin) * $size));

      imagefilledrectangle(
        $canvas,
        $innerX,
        $innerY,
        $innerX + $innerW - 1,
        $innerY + $innerH - 1,
        $white
      );

      imagecopyresampled(
        $canvas,
        $sourceImage,
        $innerX,
        $innerY,
        0,
        0,
        $innerW,
        $innerH,
        imagesx($sourceImage),
        imagesy($sourceImage)
      );

      ob_start();
      imagepng($canvas);
      $pngBinary = ob_get_clean();

      if ($pngBinary === false) {
        throw new RuntimeException('Gagal mengenkode marker AR ke PNG.');
      }

      return $pngBinary;
    } finally {
      if ($canvas !== null) {
        imagedestroy($canvas);
      }

      imagedestroy($sourceImage);
    }
  }

  private static function ensureGdAvailable(): void
  {
    if (! function_exists('imagecreatefromstring')) {
      throw new RuntimeException('Ekstensi GD tidak tersedia untuk membuat file pattern AR.');
    }
  }

  private static function loadImage(string $imagePath): \GdImage
  {
    $imageContents = @file_get_contents($imagePath);
    if ($imageContents === false) {
      throw new RuntimeException('Gagal membaca file gambar marker.');
    }

    $sourceImage = @imagecreatefromstring($imageContents);
    if ($sourceImage === false) {
      throw new RuntimeException('Format gambar marker tidak didukung.');
    }

    return $sourceImage;
  }

  private static function resolveBorderColor(string $borderColor): array
  {
    $normalized = strtolower(trim($borderColor));

    if ($normalized === '' || $normalized === 'black') {
      return [0, 0, 0];
    }

    if ($normalized === 'white') {
      return [255, 255, 255];
    }

    if (preg_match('/^#([0-9a-f]{6})$/i', $normalized, $matches) === 1) {
      $hex = $matches[1];

      return [
        hexdec(substr($hex, 0, 2)),
        hexdec(substr($hex, 2, 2)),
        hexdec(substr($hex, 4, 2)),
      ];
    }

    return [0, 0, 0];
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
