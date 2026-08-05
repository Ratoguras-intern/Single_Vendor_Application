<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'banner_image',
        'banner_mobile_image',
        'banner_image_fit',
        'banner_image_position',
        'thumbnail_image',
        'icon',
        'parent_id',
        'sort_order',
        'featured',
        'status',
        'seo_title',
        'seo_description',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'featured' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public static function boot(): void
    {
        parent::boot();

        static::creating(function (Category $category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
            if (auth()->check()) {
                $category->created_by = auth()->id();
                $category->updated_by = auth()->id();
            }
        });

        static::updating(function (Category $category) {
            if (auth()->check()) {
                $category->updated_by = auth()->id();
            }
        });
    }

    public function getStatusAttribute(): string
    {
        return $this->attributes['status'] ? 'active' : 'inactive';
    }

    public function setStatusAttribute(string $value): void
    {
        $this->attributes['status'] = $value === 'active';
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order')->orderBy('name');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('featured', true);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', true);
    }

    public function scopeTopLevel(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function isTopLevel(): bool
    {
        return is_null($this->parent_id);
    }

    public function isChild(): bool
    {
        return !is_null($this->parent_id);
    }

    public static function nestedOptionsList(bool $activeOnly = true): array
    {
        $query = static::query();
        if ($activeOnly) {
            $query->where('status', true);
        }

        $all = $query->ordered()->with('children')->get()->keyBy('id');

        $roots = $all->filter(fn (Category $category) => is_null($category->parent_id));

        $options = [];
        $seen = [];

        $walk = function (Category $category, int $depth) use (&$walk, &$options, &$seen) {
            if (isset($seen[$category->id])) {
                return;
            }
            $seen[$category->id] = true;

            $options[] = [
                'id' => $category->id,
                'name' => str_repeat('— ', $depth) . $category->name,
            ];

            foreach ($category->children as $child) {
                $walk($child, $depth + 1);
            }
        };

        foreach ($roots as $root) {
            $walk($root, 0);
        }

        return $options;
    }

    protected function mediaDisk(): string
    {
        return (string) config('categories.image.disk');
    }

    public function getBannerUrlAttribute(): ?string
    {
        if ($this->banner_image && \Storage::disk($this->mediaDisk())->exists($this->banner_image)) {
            return \Storage::disk($this->mediaDisk())->url($this->banner_image);
        }
        return null;
    }

    public function getBannerMobileUrlAttribute(): ?string
    {
        if ($this->banner_mobile_image && \Storage::disk($this->mediaDisk())->exists($this->banner_mobile_image)) {
            return \Storage::disk($this->mediaDisk())->url($this->banner_mobile_image);
        }
        return null;
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if ($this->thumbnail_image && \Storage::disk($this->mediaDisk())->exists($this->thumbnail_image)) {
            return \Storage::disk($this->mediaDisk())->url($this->thumbnail_image);
        }
        return null;
    }

    public function getImageUrlAttribute(): ?string
    {
        if ($this->getRawOriginal('image') && \Storage::disk($this->mediaDisk())->exists($this->getRawOriginal('image'))) {
            return \Storage::disk($this->mediaDisk())->url($this->getRawOriginal('image'));
        }
        return null;
    }

    /**
     * Legacy icon upload path (kept for backwards compatibility). New
     * categories store a Lucide icon name in the `icon` column instead.
     */
    public function getIconUrlAttribute(): ?string
    {
        $icon = $this->getRawOriginal('icon');

        if ($icon && $this->looksLikePath($icon) && \Storage::disk($this->mediaDisk())->exists($icon)) {
            return \Storage::disk($this->mediaDisk())->url($icon);
        }
        return null;
    }

    /**
     * Lucide icon name when the `icon` column holds a valid icon key.
     */
    public function getLucideIconAttribute(): ?string
    {
        $icon = $this->getRawOriginal('icon');

        if ($icon && !$this->looksLikePath($icon) && array_key_exists($icon, (array) config('categories.icons', []))) {
            return $icon;
        }
        return null;
    }

    public function getHasIconAttribute(): bool
    {
        return $this->lucide_icon !== null || $this->icon_url !== null;
    }

    protected function looksLikePath(string $value): bool
    {
        return str_contains($value, '/') || str_contains($value, '.');
    }

    public function getImageAttribute(): ?string
    {
        return $this->thumbnail_url ?? $this->image_url ?? $this->banner_url ?? null;
    }

    public function getPlaceholder(string $type = 'thumbnail'): string
    {
        $slug = $this->slug ?? 'fashion';
        $placeholders = config("categories.placeholders.{$slug}.{$type}");
        if ($placeholders) {
            return asset($placeholders);
        }
        return asset(config("categories.default_placeholder.{$type}", ''));
    }

    public function getDisplayImageAttribute(): string
    {
        return $this->getDisplayImage('thumbnail');
    }

    public function getDisplayImage(string $type = 'thumbnail'): string
    {
        $uploaded = match ($type) {
            'banner' => $this->banner_url,
            'banner_mobile' => $this->banner_mobile_url,
            'thumbnail' => $this->thumbnail_url ?? $this->image_url,
            'icon' => $this->icon_url,
            default => null,
        };

        return $uploaded ?? $this->getPlaceholder($type);
    }

    /**
     * Banner object-fit value (validated against config).
     */
    public function getBannerObjectFitAttribute(): string
    {
        $fit = $this->banner_image_fit ?? 'cover';

        return in_array($fit, (array) config('categories.object_fits', []), true) ? $fit : 'cover';
    }

    /**
     * Banner object-position value (validated against config).
     */
    public function getBannerObjectPositionAttribute(): string
    {
        $position = $this->banner_image_position ?? 'center';

        return in_array($position, (array) config('categories.object_positions', []), true) ? $position : 'center';
    }

    public function getTotalProductsCountAttribute(): int
    {
        $direct = $this->products()->where('status', true)->count();
        $children = $this->children()->withCount(['products' => fn ($q) => $q->where('status', true)])->get()->sum('products_count');
        return $direct + $children;
    }
}
