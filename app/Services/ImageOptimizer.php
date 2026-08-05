<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

/**
 * Reusable image processing service.
 *
 * Stores uploads on the configured disk and generates optimized WebP
 * variants. All failures degrade gracefully: an unreadable source returns
 * false instead of throwing, so callers can fall back to the original file.
 */
class ImageOptimizer
{
    protected ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Store an upload on the configured disk under the given directory.
     * Keeps the original format (jpg/png/webp/avif). Returns the stored path.
     */
    public function storeOriginal(UploadedFile $file, string $disk, string $directory): ?string
    {
        try {
            return $file->store($directory, $disk);
        } catch (\Throwable $e) {
            Log::warning('ImageOptimizer: failed to store original image.', [
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Generate a WebP thumbnail cropped to an exact square (or width x height).
     * Returns true on success, false when generation fails.
     */
    public function webpThumbnail(
        string $sourceFullPath,
        string $targetFullPath,
        int $width,
        int $height,
        int $quality = 80,
    ): bool {
        return $this->encodeWebp($sourceFullPath, $targetFullPath, $width, $height, $quality, true);
    }

    /**
     * Generate a WebP variant scaled down (preserving aspect ratio).
     * Returns true on success, false when generation fails.
     */
    public function webpScaleDown(
        string $sourceFullPath,
        string $targetFullPath,
        int $maxWidth,
        int $maxHeight,
        int $quality = 80,
    ): bool {
        return $this->encodeWebp($sourceFullPath, $targetFullPath, $maxWidth, $maxHeight, $quality, false);
    }

    protected function encodeWebp(
        string $sourceFullPath,
        string $targetFullPath,
        int $width,
        int $height,
        int $quality,
        bool $crop,
    ): bool {
        try {
            $image = $this->manager->decodePath($sourceFullPath);

            if ($crop) {
                $image->cover($width, $height);
            } else {
                $image->scaleDown($width, $height);
            }

            $image->encodeUsingFileExtension('webp', quality: max(1, min(100, $quality)))->save($targetFullPath);

            return true;
        } catch (\Throwable $e) {
            Log::warning('ImageOptimizer: webp generation failed.', [
                'source' => $sourceFullPath,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Delete a file from the given disk if it exists. Safe to call with null.
     */
    public function delete(?string $path, string $disk): void
    {
        if (!$path) {
            return;
        }

        try {
            if (Storage::disk($disk)->exists($path)) {
                Storage::disk($disk)->delete($path);
            }
        } catch (\Throwable $e) {
            Log::warning('ImageOptimizer: failed to delete image.', [
                'path' => $path,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
