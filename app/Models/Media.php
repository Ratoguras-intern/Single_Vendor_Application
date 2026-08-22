<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $fillable = [
        'disk',
        'path',
        'thumb_path',
        'original_name',
        'mime_type',
        'size',
        'width',
        'height',
        'hash',
    ];

    public function url(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    public function thumbUrl(): string
    {
        if ($this->thumb_path && Storage::disk($this->disk)->exists($this->thumb_path)) {
            return Storage::disk($this->disk)->url($this->thumb_path);
        }

        return $this->url();
    }

    public function fileExists(): bool
    {
        return Storage::disk($this->disk)->exists($this->path);
    }

    public function humanSize(): string
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, $i === 0 ? 0 : 1).' '.$units[$i];
    }

    public function scopeSearch($query, ?string $term)
    {
        if (!$term) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('original_name', 'like', "%{$term}%")
                ->orWhere('path', 'like', "%{$term}%");
        });
    }

    /**
     * Filter by a library folder key from config('media.folders').
     * `other` matches everything outside the known feature folders.
     */
    public function scopeFolder($query, ?string $folder)
    {
        $folders = (array) config('media.folders', []);

        if (!$folder || $folder === 'all' || $folder === 'recent') {
            return $query;
        }

        if ($folder === 'other') {
            return $query->where(function ($q) use ($folders) {
                foreach ($folders as $config) {
                    foreach ((array) ($config['match'] ?? []) as $prefix) {
                        $q->whereNot('path', 'like', $prefix.'/%');
                    }
                }
            });
        }

        $prefixes = (array) ($folders[$folder]['match'] ?? [$folder]);

        return $query->where(function ($q) use ($prefixes) {
            foreach ($prefixes as $prefix) {
                $q->orWhere('path', 'like', $prefix.'/%');
            }
        });
    }

    public function toPickerArray(): array
    {
        return [
            'id' => $this->id,
            'path' => $this->path,
            'url' => $this->fileExists() ? $this->url() : null,
            'thumb_url' => $this->fileExists() ? $this->thumbUrl() : null,
            'name' => $this->original_name,
            'mime_type' => $this->mime_type,
            'size_human' => $this->humanSize(),
            'width' => $this->width,
            'height' => $this->height,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
