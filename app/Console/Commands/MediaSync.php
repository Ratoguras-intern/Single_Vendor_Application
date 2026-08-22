<?php

namespace App\Console\Commands;

use App\Models\Media;
use App\Services\ImageOptimizer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Imports legacy files that were uploaded before the media library existed
 * (products, categories, brands, banners, branding logos, pages) into the
 * `media` table so they become selectable in the Media Library picker.
 *
 * Safe to run repeatedly: files whose path or content hash is already
 * tracked are skipped. No files are moved or modified.
 */
class MediaSync extends Command
{
    protected $signature = 'media:sync {--disk= : Storage disk to scan (default: config media.disk)}';

    protected $description = 'Import existing storage images into the media library table';

    public function handle(ImageOptimizer $optimizer): int
    {
        $diskName = $this->option('disk') ?: config('media.disk');
        $disk = Storage::disk($diskName);
        $allowedExtensions = (array) config('media.allowed_extensions', []);
        $folders = (array) config('media.folders', []);

        $roots = collect($folders)
            ->flatMap(fn ($cfg) => (array) ($cfg['match'] ?? []))
            ->push(trim((string) config('media.directory'), '/'))
            ->unique()
            ->values();

        $imported = 0;
        $skipped = 0;
        $failed = 0;

        foreach ($roots as $root) {
            if (!$disk->directoryExists($root)) {
                continue;
            }

            foreach ($disk->allFiles($root) as $path) {
                $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

                if (!in_array($extension, $allowedExtensions, true)) {
                    continue;
                }

                // Skip generated thumbnails from previous imports/uploads.
                if (str_ends_with(strtolower($path), '-thumb.webp')) {
                    continue;
                }

                if (Media::where('disk', $diskName)->where('path', $path)->exists()) {
                    $skipped++;
                    continue;
                }

                $fullPath = $disk->path($path);
                $hash = @sha1_file($fullPath);

                if ($hash && Media::where('hash', $hash)->exists()) {
                    $skipped++;
                    continue;
                }

                [$width, $height] = [null, null];

                if ($extension !== 'svg') {
                    try {
                        $info = @getimagesize($fullPath);
                        if ($info) {
                            [$width, $height] = $info;
                        }
                    } catch (\Throwable) {
                        // Non-image or unreadable — leave dimensions empty.
                    }
                }

                $thumbPath = null;

                if ($width) {
                    $candidate = preg_replace('/\.([a-z0-9]+)$/i', '-thumb.webp', $path);
                    $thumbPath = $optimizer->webpThumbnail(
                        $fullPath,
                        dirname($fullPath).'/'.basename($candidate),
                        (int) config('media.thumb_width', 400),
                        (int) config('media.thumb_height', 400),
                        (int) config('media.thumb_quality', 78),
                    ) ? $candidate : null;
                }

                try {
                    Media::create([
                        'disk' => $diskName,
                        'path' => $path,
                        'thumb_path' => $thumbPath,
                        'original_name' => basename($path),
                        'mime_type' => $this->mimeType($fullPath, $extension),
                        'size' => (int) $disk->size($path),
                        'width' => $width,
                        'height' => $height,
                        'hash' => $hash,
                    ]);
                    $imported++;
                } catch (\Throwable $e) {
                    $failed++;
                    $this->warn("Failed: {$path} — {$e->getMessage()}");
                }
            }
        }

        $this->info("Media sync complete. Imported: {$imported}. Skipped (already tracked): {$skipped}. Failed: {$failed}.");

        return self::SUCCESS;
    }

    protected function mimeType(string $fullPath, string $extension): string
    {
        if ($extension === 'svg') {
            return 'image/svg+xml';
        }

        $mime = @mime_content_type($fullPath);

        return str_starts_with((string) $mime, 'image/') ? $mime : 'application/octet-stream';
    }
}
