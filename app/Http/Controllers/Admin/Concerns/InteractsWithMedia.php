<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Lets any admin controller accept an image either as an uploaded file or as
 * a selection from the central media library (`{field}_media_id` input).
 */
trait InteractsWithMedia
{
    /**
     * Resolve the stored path for a `{field}_media_id` library selection.
     */
    protected function mediaPath(Request $request, string $field): ?string
    {
        $id = $request->input($field.'_media_id');

        if (!$id) {
            return null;
        }

        return Media::query()->findOrFail((int) $id)->path;
    }

    /**
     * Resolve multiple media selections for gallery fields
     * (`gallery_media_ids[]` inputs), preserving submission order.
     */
    protected function galleryMedia(Request $request)
    {
        $ids = array_values(array_filter((array) $request->input('gallery_media_ids', [])));

        if ($ids === []) {
            return collect();
        }

        return Media::whereIn('id', $ids)
            ->get()
            ->sortBy(fn (Media $m) => array_search($m->id, $ids))
            ->values();
    }

    /**
     * Delete an entity's previous image file unless it is tracked in the
     * media library — shared assets must never be deleted implicitly.
     */
    protected function deleteImageSafe(?string $path, string $disk = 'public'): void
    {
        if (!$path) {
            return;
        }

        if (Media::where('path', $path)->exists()) {
            return;
        }

        if (Storage::disk($disk)->exists($path)) {
            Storage::disk($disk)->delete($path);
        }
    }
}
