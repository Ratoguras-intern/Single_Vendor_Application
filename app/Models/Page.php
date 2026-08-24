<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Page extends Model
{
    public const TEMPLATES = [
        '' => 'Default (Document)',
        'contact' => 'Contact Us',
        'help-center' => 'Help Center',
        'shipping-info' => 'Shipping Info',
        'returns' => 'Returns & Exchanges',
        'about' => 'About Us',
        'careers' => 'Careers',
        'blog' => 'Blog',
        'press' => 'Press',
    ];

    protected $fillable = [
        'title',
        'subtitle',
        'slug',
        'template',
        'footer_section',
        'short_description',
        'content',
        'featured_image',
        'seo_title',
        'seo_description',
        'canonical_url',
        'og_title',
        'og_description',
        'og_image',
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
        return $this->mediaUrl($this->featured_image);
    }

    public function getOgImageUrlAttribute(): ?string
    {
        return $this->mediaUrl($this->og_image) ?? $this->featured_image_url;
    }

    protected function mediaUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (str_starts_with($path, 'http')) {
            return $path;
        }

        return asset('storage/'.$path);
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
