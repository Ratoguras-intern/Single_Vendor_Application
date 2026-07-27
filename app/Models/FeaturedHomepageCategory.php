<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class FeaturedHomepageCategory extends Model
{
    protected $fillable = [
        'category_id',
        'sort_order',
        'is_enabled',
        'display_style',
    ];

    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
        ];
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('is_enabled', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public static function getCached(): \Illuminate\Support\Collection
    {
        return Cache::remember('featured_homepage_categories', 300, function () {
            return static::enabled()
                ->ordered()
                ->with('category')
                ->get();
        });
    }

    public static function clearCache(): void
    {
        Cache::forget('featured_homepage_categories');
    }
}
