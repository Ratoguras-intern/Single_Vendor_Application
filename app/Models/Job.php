<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Job extends Model
{
    protected $table = 'job_openings';

    protected $fillable = [
        'title',
        'slug',
        'department',
        'location',
        'employment_type',
        'experience_level',
        'description',
        'responsibilities',
        'requirements',
        'benefits',
        'application_instructions',
        'application_email',
        'status',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    public static function boot(): void
    {
        parent::boot();

        static::creating(function (Job $job) {
            if (empty($job->slug)) {
                $job->slug = Str::slug($job->title);
            }
            if ($job->status === 'published' && ! $job->published_at) {
                $job->published_at = now();
            }
        });
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->orderBy('title');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function responsibilitiesList(): array
    {
        return $this->splitLines($this->responsibilities);
    }

    public function requirementsList(): array
    {
        return $this->splitLines($this->requirements);
    }

    public function benefitsList(): array
    {
        return $this->splitLines($this->benefits);
    }

    protected function splitLines(?string $text): array
    {
        if (! $text) {
            return [];
        }

        return collect(preg_split('/\r\n|\r|\n/', $text))
            ->map(fn ($line) => trim(ltrim(trim($line), "-*•")))
            ->filter()
            ->values()
            ->all();
    }
}
