<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PressRelease extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'summary',
        'content',
        'featured_image',
        'released_at',
        'status',
        'seo_title',
        'seo_description',
    ];

    protected function casts(): array
    {
        return [
            'released_at' => 'date',
        ];
    }

    public static function boot(): void
    {
        parent::boot();

        static::creating(function (PressRelease $release) {
            if (empty($release->slug)) {
                $release->slug = Str::slug($release->title);
            }
        });
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->orderBy('released_at', 'desc')
            ->orderBy('created_at', 'desc');
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
}
