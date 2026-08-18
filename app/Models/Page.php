<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Page extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'footer_section',
        'short_description',
        'content',
        'featured_image',
        'seo_title',
        'seo_description',
        'status',
        'show_in_footer',
        'footer_order',
    ];

    protected function casts(): array
    {
        return [
            'show_in_footer' => 'boolean',
            'footer_order' => 'integer',
        ];
    }

    public static function boot(): void
    {
        parent::boot();

        static::creating(function (Page $page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
        });

        static::saved(function () {
            static::clearCache();
        });

        static::deleted(function () {
            static::clearCache();
        });
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeForFooter(Builder $query): Builder
    {
        return $query->published()
            ->where('show_in_footer', true)
            ->orderBy('footer_order')
            ->orderBy('title');
    }

    public function scopeInFooterSection(Builder $query, string $section): Builder
    {
        return $query->forFooter()
            ->where('footer_section', $section);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getFeaturedImageUrlAttribute(): ?string
    {
        if (! $this->featured_image) {
            return null;
        }

        if (str_starts_with($this->featured_image, 'http')) {
            return $this->featured_image;
        }

        return asset('storage/'.$this->featured_image);
    }

    public static function getFooterPages(string $section): \Illuminate\Support\Collection
    {
        return Cache::remember("footer_pages_{$section}", 3600, function () use ($section) {
            return static::inFooterSection($section)->get();
        });
    }

    public static function clearCache(): void
    {
        foreach (['customer-care', 'company', 'legal'] as $section) {
            Cache::forget("footer_pages_{$section}");
        }
    }
}
