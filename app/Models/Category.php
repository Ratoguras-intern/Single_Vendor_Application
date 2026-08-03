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

    public function getBannerUrlAttribute(): ?string
    {
        if ($this->banner_image && \Storage::disk('public')->exists($this->banner_image)) {
            return \Storage::disk('public')->url($this->banner_image);
        }
        return null;
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if ($this->thumbnail_image && \Storage::disk('public')->exists($this->thumbnail_image)) {
            return \Storage::disk('public')->url($this->thumbnail_image);
        }
        return null;
    }

    public function getIconUrlAttribute(): ?string
    {
        if ($this->icon && \Storage::disk('public')->exists($this->icon)) {
            return \Storage::disk('public')->url($this->icon);
        }
        return null;
    }

    public function getImageAttribute(): ?string
    {
        return $this->thumbnail_url ?? $this->banner_url ?? null;
    }

    public function getPlaceholder(string $type = 'thumbnail'): string
    {
        $slug = $this->slug ?? 'fashion';
        $placeholders = config("categories.placeholders.{$slug}.{$type}");
        if ($placeholders) {
            return $placeholders;
        }
        return config("categories.default_placeholder.{$type}", '');
    }

    public function getDisplayImageAttribute(): string
    {
        return $this->getDisplayImage('thumbnail');
    }

    public function getDisplayImage(string $type = 'thumbnail'): string
    {
        $uploaded = match ($type) {
            'banner' => $this->banner_url,
            'thumbnail' => $this->thumbnail_url,
            'icon' => $this->icon_url,
            default => null,
        };

        return $uploaded ?? $this->getPlaceholder($type);
    }

    public function getTotalProductsCountAttribute(): int
    {
        $direct = $this->products()->where('status', true)->count();
        $children = $this->children()->withCount(['products' => fn ($q) => $q->where('status', true)])->get()->sum('products_count');
        return $direct + $children;
    }
}
