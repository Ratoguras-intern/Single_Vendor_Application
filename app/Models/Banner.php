<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'image',
        'mobile_image',
        'link',
        'button_text',
        'secondary_button_text',
        'secondary_button_url',
        'badge',
        'badge_color',
        'text_alignment',
        'image_position',
        'overlay_opacity',
        'style_settings',
        'text_color',
        'show_countdown',
        'position',
        'target_pages',
        'is_enabled',
        'sort_order',
        'starts_at',
        'ends_at',
    ];

    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
            'show_countdown' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'target_pages' => 'array',
            'style_settings' => 'array',
        ];
    }

    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('is_enabled', true);
    }

    public function scopeForPosition(Builder $query, string $position): Builder
    {
        return $query->where('position', $position);
    }

    public function scopeForTargetPage(Builder $query, string $page): Builder
    {
        return $query->whereJsonContains('target_pages', $page);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_enabled', true)
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            });
    }

    public function scopeScheduled(Builder $query): Builder
    {
        return $query->where('is_enabled', true)
            ->where('starts_at', '>', now());
    }

    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('ends_at', '<', now());
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public static function getCachedForPosition(string $position): \Illuminate\Support\Collection
    {
        return Cache::remember("banners_{$position}", 300, function () use ($position) {
            return static::active()
                ->forPosition($position)
                ->ordered()
                ->get();
        });
    }

    public static function clearCache(): void
    {
        foreach (config('banners.positions') as $pos) {
            Cache::forget("banners_{$pos}");
        }
        foreach (array_keys(config('banners.pages')) as $page) {
            Cache::forget("banners_{$page}");
        }
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->resolveImageUrl($this->image);
    }

    public function getMobileImageUrlAttribute(): ?string
    {
        return $this->resolveImageUrl($this->mobile_image);
    }

    public function getStatusAttribute(): string
    {
        if (!$this->is_enabled) {
            return 'inactive';
        }
        if ($this->starts_at && $this->starts_at->isFuture()) {
            return 'scheduled';
        }
        if ($this->ends_at && $this->ends_at->isPast()) {
            return 'expired';
        }
        return 'active';
    }

    public function getIsCurrentlyActiveAttribute(): bool
    {
        return $this->status === 'active';
    }

    public function getOverlayStyleAttribute(): string
    {
        return "rgba(0,0,0,{$this->overlayRatio()})";
    }

    public function styleSettings(): array
    {
        return array_merge([
            'image_fit' => 'cover',
            'image_repeat' => 'no-repeat',
            'image_size' => 'auto',
            'image_width' => null,
            'image_height' => null,
            'banner_height' => null,
            'banner_height_custom' => null,
            'overlay_enabled' => true,
            'overlay_color' => '#000000',
            'content_vertical' => null,
            'border_radius' => null,
            'border_radius_custom' => null,
            'padding_top' => null,
            'padding_bottom' => null,
            'padding_left' => null,
            'padding_right' => null,
            'margin_top' => null,
            'margin_bottom' => null,
            'zoom' => null,
            'brightness' => null,
            'contrast' => null,
            'saturation' => null,
            'blur' => null,
            'grayscale' => false,
            'text_width' => null,
            'show_desktop' => true,
            'show_tablet' => true,
            'show_mobile' => true,
        ], $this->style_settings ?? []);
    }

    public function getOverlayEnabledAttribute(): bool
    {
        return $this->styleSettings()['overlay_enabled'] ?? true;
    }

    public function getOverlayColorAttribute(): string
    {
        return $this->styleSettings()['overlay_color'] ?? '#000000';
    }

    public function getOverlayRgbaAttribute(): string
    {
        $color = ltrim($this->overlay_color, '#');

        if (strlen($color) === 3) {
            $color = $color[0] . $color[0] . $color[1] . $color[1] . $color[2] . $color[2];
        }

        $r = hexdec(substr($color, 0, 2) ?: '00');
        $g = hexdec(substr($color, 2, 2) ?: '00');
        $b = hexdec(substr($color, 4, 2) ?: '00');

        return "rgba({$r},{$g},{$b},{$this->overlayRatio()})";
    }

    public function getImagePositionCssAttribute(): string
    {
        $map = [
            'center' => 'center center',
            'top' => 'top center',
            'bottom' => 'bottom center',
            'left' => 'center left',
            'right' => 'center right',
            'left top' => 'top left',
            'top left' => 'top left',
            'right top' => 'top right',
            'top right' => 'top right',
            'left bottom' => 'bottom left',
            'bottom left' => 'bottom left',
            'right bottom' => 'bottom right',
            'bottom right' => 'bottom right',
            'center center' => 'center center',
            'top center' => 'top center',
            'bottom center' => 'bottom center',
            'center left' => 'center left',
            'center right' => 'center right',
        ];

        return $map[$this->image_position ?? 'center'] ?? 'center center';
    }

    public function getImageCssAttribute(): string
    {
        $settings = $this->styleSettings();
        $css = [];

        $css[] = 'object-fit: ' . ($settings['image_fit'] ?? 'cover');

        $css[] = 'object-position: ' . $this->image_position_css;

        $zoom = $settings['zoom'] ?? null;
        if ($zoom !== null && $zoom !== 100) {
            $css[] = 'scale: ' . ($zoom / 100);
        }

        $css[] = 'filter: ' . $this->filterCss();

        if (($settings['image_size'] ?? 'auto') === 'custom') {
            if ($settings['image_width']) {
                $css[] = 'width: ' . (int) $settings['image_width'] . 'px';
            }
            if ($settings['image_height']) {
                $css[] = 'height: ' . (int) $settings['image_height'] . 'px';
            }
        }

        return implode('; ', $css) . ';';
    }

    public function getBackgroundCssAttribute(): string
    {
        $settings = $this->styleSettings();
        $css = [];

        $css[] = 'background-size: ' . $this->backgroundSizeCss();

        $css[] = 'background-position: ' . $this->image_position_css;

        $css[] = 'background-repeat: ' . ($settings['image_repeat'] ?? 'no-repeat');

        $zoom = $settings['zoom'] ?? null;
        if ($zoom !== null && $zoom !== 100) {
            $css[] = 'scale: ' . ($zoom / 100);
        }

        $css[] = 'filter: ' . $this->filterCss();

        return implode('; ', $css) . ';';
    }

    public function getBannerHeightCssAttribute(): string
    {
        $settings = $this->styleSettings();
        $height = $settings['banner_height'] ?? null;

        if (!$height) {
            return '';
        }

        $px = match ($height) {
            'small' => 300,
            'medium' => 450,
            'large' => 600,
            'xlarge' => 750,
            'full_screen' => null,
            'custom' => (int) ($settings['banner_height_custom'] ?? 0),
            default => null,
        };

        if ($height === 'full_screen') {
            return 'min-height: 100vh;';
        }

        if ($px && $px >= 50) {
            return 'min-height: ' . $px . 'px;';
        }

        return '';
    }

    public function getBorderRadiusCssAttribute(): string
    {
        $settings = $this->styleSettings();
        $radius = $settings['border_radius'] ?? null;

        if (!$radius) {
            return '';
        }

        $px = match ($radius) {
            'none' => 0,
            'small' => 8,
            'medium' => 16,
            'large' => 24,
            'xlarge' => 32,
            'custom' => (int) ($settings['border_radius_custom'] ?? 0),
            default => null,
        };

        if ($px === null) {
            return '';
        }

        return 'border-radius: ' . $px . 'px;';
    }

    public function getContentPaddingCssAttribute(): string
    {
        $settings = $this->styleSettings();
        $top = $settings['padding_top'] ?? null;
        $right = $settings['padding_right'] ?? null;
        $bottom = $settings['padding_bottom'] ?? null;
        $left = $settings['padding_left'] ?? null;

        if ($top === null && $right === null && $bottom === null && $left === null) {
            return '';
        }

        return 'padding: ' . (int) $top . 'px ' . (int) $right . 'px ' . (int) $bottom . 'px ' . (int) $left . 'px;';
    }

    public function getSectionMarginCssAttribute(): string
    {
        $settings = $this->styleSettings();
        $top = $settings['margin_top'] ?? null;
        $bottom = $settings['margin_bottom'] ?? null;

        if ($top === null && $bottom === null) {
            return '';
        }

        return 'margin-top: ' . (int) $top . 'px; margin-bottom: ' . (int) $bottom . 'px;';
    }

    public function getContentVerticalAttribute(): ?string
    {
        $vertical = $this->styleSettings()['content_vertical'] ?? null;

        return in_array($vertical, ['top', 'center', 'bottom']) ? $vertical : null;
    }

    protected function overlayRatio(): float
    {
        $opacity = $this->overlay_opacity ?? 40;

        return max(0, min(100, (int) $opacity)) / 100;
    }

    protected function filterCss(): string
    {
        $settings = $this->styleSettings();
        $brightness = $settings['brightness'] ?? null;
        $contrast = $settings['contrast'] ?? null;
        $saturation = $settings['saturation'] ?? null;
        $blur = $settings['blur'] ?? null;
        $grayscale = $settings['grayscale'] ?? false;

        $filters = [];
        if ($brightness !== null && $brightness !== 100) {
            $filters[] = 'brightness(' . (int) $brightness . '%)';
        }
        if ($contrast !== null && $contrast !== 100) {
            $filters[] = 'contrast(' . (int) $contrast . '%)';
        }
        if ($saturation !== null && $saturation !== 100) {
            $filters[] = 'saturate(' . (int) $saturation . '%)';
        }
        if ($blur !== null && $blur > 0) {
            $filters[] = 'blur(' . (int) $blur . 'px)';
        }
        if ($grayscale) {
            $filters[] = 'grayscale(1)';
        }

        return $filters ? implode(' ', $filters) : 'none';
    }

    protected function backgroundSizeCss(): string
    {
        $settings = $this->styleSettings();
        $size = $settings['image_size'] ?? 'auto';
        $fit = $settings['image_fit'] ?? 'cover';

        if ($size === 'cover' || $size === 'contain') {
            return $size;
        }

        if ($size === 'custom') {
            $width = $settings['image_width'] ? (int) $settings['image_width'] . 'px' : 'auto';
            $height = $settings['image_height'] ? (int) $settings['image_height'] . 'px' : 'auto';

            return "{$width} {$height}";
        }

        return match ($fit) {
            'contain' => 'contain',
            'fill' => '100% 100%',
            'none', 'scale-down' => 'auto',
            default => 'cover',
        };
    }

    public function getTextAlignmentClassAttribute(): string
    {
        return match ($this->text_alignment) {
            'center' => 'text-center items-center',
            'right' => 'text-right items-end',
            default => 'text-left items-start',
        };
    }

    public function getTextWidthCssAttribute(): string
    {
        $width = $this->styleSettings()['text_width'] ?? null;

        $px = match ($width) {
            'narrow' => 320,
            'medium' => 448,
            'wide' => 576,
            default => null,
        };

        return $px ? 'max-width: ' . $px . 'px;' : '';
    }

    public function getVisibilityClassesAttribute(): string
    {
        $settings = $this->styleSettings();
        $desktop = (bool) ($settings['show_desktop'] ?? true);
        $tablet = (bool) ($settings['show_tablet'] ?? true);
        $mobile = (bool) ($settings['show_mobile'] ?? true);

        if ($desktop && $tablet && $mobile) {
            return '';
        }

        $classes = ['hidden'];
        if ($mobile) {
            $classes[] = 'block';
        }
        if ($tablet) {
            $classes[] = 'md:block';
            if (!$desktop) {
                $classes[] = 'lg:hidden';
            }
        }
        if ($desktop && !$tablet) {
            $classes[] = 'lg:block';
        }

        return implode(' ', $classes);
    }

    protected function resolveImageUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }
        if (str_starts_with($path, 'http')) {
            return $path;
        }
        return Storage::disk('public')->url($path);
    }
}
