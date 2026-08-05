<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

/**
 * Manages category media (main image, auto thumbnail, banner, mobile banner).
 *
 * Responsibilities:
 *  - Store the original main image and generate an optimized WebP thumbnail.
 *  - Store optimized WebP banners for desktop and mobile.
 *  - Clean up files when replaced or when a category is deleted.
 */
class CategoryImageService
{
    public function __construct(protected ImageOptimizer $optimizer)
    {
    }

    public function disk(): string
    {
        return (string) config('categories.image.disk');
    }

    public function mainDirectory(): string
    {
        return $this->directory('main_subdir');
    }

    public function thumbnailDirectory(): string
    {
        return $this->directory('thumb_subdir');
    }

    public function bannerDirectory(): string
    {
        return $this->directory('banner_subdir');
    }

    public function bannerMobileDirectory(): string
    {
        return $this->directory('banner_mobile_subdir');
    }

    protected function directory(string $subdirKey): string
    {
        return trim(config('categories.image.directory')) . '/' . trim(config("categories.image.{$subdirKey}"));
    }

    /**
     * Store the main category image and generate its thumbnail.
     *
     * @return array{image: ?string, thumbnail_image: ?string}
     */
    public function storeMainImage(UploadedFile $file): array
    {
        $original = $this->optimizer->storeOriginal($file, $this->disk(), $this->mainDirectory());

        if (!$original) {
            return ['image' => null, 'thumbnail_image' => null];
        }

        $thumbnail = $this->generateThumbnail($original);

        return [
            'image' => $original,
            'thumbnail_image' => $thumbnail ?: $original,
        ];
    }

    /**
     * Store desktop and optional mobile banner images (optimized WebP).
     *
     * @return array{banner_image: ?string, banner_mobile_image: ?string}
     */
    public function storeBanner(UploadedFile $desktop, ?UploadedFile $mobile = null): array
    {
        $banner = $this->storeOptimized($desktop, $this->bannerDirectory(), 'banner');
        $mobileBanner = $mobile ? $this->storeOptimized($mobile, $this->bannerMobileDirectory(), 'banner_mobile') : null;

        return [
            'banner_image' => $banner,
            'banner_mobile_image' => $mobileBanner,
        ];
    }

    public function storeMobileBanner(UploadedFile $file): ?string
    {
        return $this->storeOptimized($file, $this->bannerMobileDirectory(), 'banner_mobile');
    }

    /**
     * Store an upload and try to replace it with an optimized WebP variant.
     * Falls back to the original file when encoding fails.
     */
    protected function storeOptimized(UploadedFile $file, string $directory, string $preset): ?string
    {
        $stored = $this->optimizer->storeOriginal($file, $this->disk(), $directory);

        if (!$stored) {
            return null;
        }

        $webpPath = $directory . '/' . Str::uuid() . '.webp';
        $config = (array) config("categories.image.{$preset}");
        $ok = $this->optimizer->webpScaleDown(
            $this->path($stored),
            $this->path($webpPath),
            (int) ($config['max_width'] ?? 1920),
            (int) ($config['max_height'] ?? 1080),
            (int) ($config['quality'] ?? 80),
        );

        if ($ok) {
            $this->optimizer->delete($stored, $this->disk());

            return $webpPath;
        }

        return $stored;
    }

    protected function generateThumbnail(string $originalPath): ?string
    {
        $thumbPath = $this->thumbnailDirectory() . '/' . Str::uuid() . '.webp';
        $config = (array) config('categories.image.thumbnail');

        $ok = $this->optimizer->webpThumbnail(
            $this->path($originalPath),
            $this->path($thumbPath),
            (int) ($config['width'] ?? 300),
            (int) ($config['height'] ?? 300),
            (int) ($config['quality'] ?? 80),
        );

        return $ok ? $thumbPath : null;
    }

    protected function path(string $storedPath): string
    {
        return \Storage::disk($this->disk())->path(ltrim($storedPath, '/'));
    }

    /**
     * Remove all media files belonging to a category.
     */
    public function deleteAll(Category $category): void
    {
        foreach ([
            'banner_mobile_image',
            'banner_image',
            'thumbnail_image',
            'image',
        ] as $field) {
            $this->optimizer->delete($category->getRawOriginal($field), $this->disk());
        }
    }

    /**
     * Remove main image + generated thumbnail only.
     */
    public function deleteMainImage(Category $category): void
    {
        $this->optimizer->delete($category->getRawOriginal('image'), $this->disk());

        // Only delete the thumbnail when it is a distinct generated file
        // (not a fallback pointer to the original image).
        $thumb = $category->getRawOriginal('thumbnail_image');
        if ($thumb && $thumb !== $category->getRawOriginal('image')) {
            $this->optimizer->delete($thumb, $this->disk());
        }
    }

    /**
     * Remove banner + mobile banner only.
     */
    public function deleteBanner(Category $category): void
    {
        $this->optimizer->delete($category->getRawOriginal('banner_mobile_image'), $this->disk());
        $this->optimizer->delete($category->getRawOriginal('banner_image'), $this->disk());
    }

    /**
     * Remove the mobile banner only (desktop banner is kept).
     */
    public function deleteMobileBanner(Category $category): void
    {
        $this->optimizer->delete($category->getRawOriginal('banner_mobile_image'), $this->disk());
    }
}
