<?php

namespace App\Console\Commands;

use App\Models\Scene;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class FixPanoramaExtensions extends Command
{
    protected $signature = 'panorama:fix-extensions';

    protected $description = 'Fix panorama files with missing extensions (ending with just a dot)';

    public function handle(): int
    {
        $disk = Storage::disk('public');
        $fixed = 0;
        $errors = 0;

        $this->info('Scanning for panorama files with missing extensions...');

        $allFiles = $disk->files('panoramas');
        $brokenFiles = array_filter($allFiles, fn ($f) => str_ends_with($f, '.'));

        if (empty($brokenFiles)) {
            $this->info('No broken files found.');

            return 0;
        }

        $this->info('Found '.count($brokenFiles).' files with missing extensions.');

        foreach ($brokenFiles as $path) {
            $fullPath = $disk->path($path);
            $mimeType = mime_content_type($fullPath);

            $ext = match ($mimeType) {
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/webp' => 'webp',
                default => 'jpg',
            };

            $newPath = $path.$ext;
            $newFullPath = $disk->path($newPath);

            if (rename($fullPath, $newFullPath)) {
                $this->line("✓ Renamed: {$path} → {$newPath}");

                // Update database references
                $oldUrl = "/storage/{$path}";
                $newUrl = "/storage/{$newPath}";

                $updated = Scene::where('image', $oldUrl)->update(['image' => $newUrl]);

                if ($updated > 0) {
                    $this->line("  Updated {$updated} scene(s) in database");
                }

                $fixed++;
            } else {
                $this->error("✗ Failed to rename: {$path}");
                $errors++;
            }
        }

        $this->newLine();
        $this->info("Fixed: {$fixed} | Errors: {$errors}");

        return $errors > 0 ? 1 : 0;
    }
}
