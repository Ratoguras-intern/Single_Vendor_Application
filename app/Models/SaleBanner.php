<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class SaleBanner extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'image',
        'product_image',
        'link',
        'button_text',
        'secondary_button_text',
        'secondary_button_url',
        'badge',
        'badge_color',
        'featured_product_id',
        'background_color',
        'gradient_from',
        'gradient_to',
        'text_alignment',
        'image_position',
        'overlay_opacity',
        'text_color',
        'show_countdown',
        'countdown_end_date',
        'countdown_end_time',
        'countdown_timezone',
        'enable_badge',
        'enable_product_image',
        'enable_prices',
        'enable_buttons',
        'enable_overlay',
        'style_settings',
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
            'enable_badge' => 'boolean',
            'enable_product_image' => 'boolean',
            'enable_prices' => 'boolean',
            'enable_buttons' => 'boolean',
            'enable_overlay' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'style_settings' => 'array',
        ];
    }

    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('is_enabled', true);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_enabled', true)
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            });
    }

    public function scopeRunning(Builder $query): Builder
    {
        return $query->where('is_enabled', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
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

    public function featuredProduct(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'featured_product_id');
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->resolveImageUrl($this->image);
    }

    public function getProductImageUrlAttribute(): ?string
    {
        if ($this->product_image) {
            return $this->resolveImageUrl($this->product_image);
        }

        $product = $this->featuredProduct;

        if ($product && $product->relationLoaded('images') && $image = $product->primaryImage()) {
            return $this->resolveImageUrl($image->image);
        }

        return null;
    }

    public function getCurrentPriceAttribute(): ?string
    {
        $product = $this->featuredProduct;

        return $product ? (string) ($product->discount_price ?? $product->price) : null;
    }

    public function getOriginalPriceAttribute(): ?string
    {
        $product = $this->featuredProduct;

        return $product && $product->discount_price ? (string) $product->price : null;
    }

    public function getDiscountPercentageAttribute(): ?int
    {
        $product = $this->featuredProduct;

        if (! $product || ! $product->discount_price || ! $product->price) {
            return null;
        }

        $original = (float) $product->price;
        $current = (float) $product->discount_price;

        if ($original <= 0 || $current >= $original) {
            return null;
        }

        return (int) round((($original - $current) / $original) * 100);
    }

    public function getCountdownEndsAtAttribute(): ?CarbonInterface
    {
        if ($this->countdown_end_date && $this->countdown_end_time) {
            $timezone = $this->countdown_timezone ?: config('app.timezone', 'UTC');
            $date = Carbon::parse($this->countdown_end_date.' '.$this->countdown_end_time);

            try {
                return $date->setTimezone($timezone);
            } catch (\Exception $e) {
                return $date;
            }
        }

        return $this->ends_at;
    }

    public function getCountdownEndsAtIsoAttribute(): ?string
    {
        return $this->countdown_ends_at?->toIso8601String();
    }

    public function getBackgroundStyleAttribute(): string
    {
        if ($this->image) {
            return '';
        }

        if ($this->gradient_from && $this->gradient_to) {
            return 'background: linear-gradient(120deg, '.e($this->gradient_from).' 0%, '.e($this->gradient_to).' 100%);';
        }

        if ($this->background_color) {
            return 'background: '.e($this->background_color).';';
        }

        return '';
    }

    public function getStatusAttribute(): string
    {
        if (! $this->is_enabled) {
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
            $color = $color[0].$color[0].$color[1].$color[1].$color[2].$color[2];
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

        return 'padding: '.(int) $top.'px '.(int) $right.'px '.(int) $bottom.'px '.(int) $left.'px;';
    }

    public function getSectionMarginCssAttribute(): string
    {
        $settings = $this->styleSettings();
        $top = $settings['margin_top'] ?? null;
        $bottom = $settings['margin_bottom'] ?? null;

        if ($top === null && $bottom === null) {
            return '';
        }

        return 'margin-top: '.(int) $top.'px; margin-bottom: '.(int) $bottom.'px;';
    }

    public function getContentVerticalAttribute(): ?string
    {
        $vertical = $this->styleSettings()['content_vertical'] ?? null;

        return in_array($vertical, ['top', 'center', 'bottom']) ? $vertical : null;
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

        return $px ? 'max-width: '.$px.'px;' : '';
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
            if (! $desktop) {
                $classes[] = 'lg:hidden';
            }
        }
        if ($desktop && ! $tablet) {
            $classes[] = 'lg:block';
        }

        return implode(' ', $classes);
    }

    protected function overlayRatio(): float
    {
        $opacity = $this->overlay_opacity ?? 40;

        return max(0, min(100, (int) $opacity)) / 100;
    }

    protected function resolveImageUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }
        if (str_starts_with($path, 'http')) {
            return $path;
        }

        return Storage::disk('public')->url($path);
    }
}
