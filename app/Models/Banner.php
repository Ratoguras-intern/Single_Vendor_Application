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
        'overlay_opacity',
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
        $opacity = ($this->overlay_opacity ?? 40) / 100;
        return "rgba(0,0,0,{$opacity})";
    }

    public function getTextAlignmentClassAttribute(): string
    {
        return match ($this->text_alignment) {
            'center' => 'text-center items-center',
            'right' => 'text-right items-end',
            default => 'text-left items-start',
        };
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
