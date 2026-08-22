<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Services\ImageOptimizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function __construct(protected ImageOptimizer $optimizer)
    {
    }

    /**
     * Paginated JSON listing of the media library (newest first).
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => 'nullable|string|max:255',
            'folder' => 'nullable|string|in:all,recent,other,'.implode(',', array_keys((array) config('media.folders', []))),
            'page' => 'nullable|integer|min:1',
        ]);

        $media = Media::query()
            ->search($validated['search'] ?? null)
            ->folder($validated['folder'] ?? null)
            ->latest()
            ->paginate(config('media.per_page', 24));

        return response()->json([
            'data' => collect($media->items())->map(fn (Media $m) => $m->toPickerArray()),
            'current_page' => $media->currentPage(),
            'last_page' => $media->lastPage(),
            'total' => $media->total(),
        ]);
    }

    /**
     * Upload one or more images into the media library.
     */
    public function store(Request $request)
    {
        $maxSize = (int) config('media.max_size', 2048);
        $allowedMimes = implode(',', (array) config('media.allowed_mimes'));
        $allowedExtensions = implode(',', (array) config('media.allowed_extensions'));

        $validated = $request->validate([
            'files' => 'required|array|max:10',
            'files.*' => [
                'file',
                "max:{$maxSize}",
                "mimes:{$allowedExtensions}",
            ],
            'folder' => 'nullable|string|in:'.implode(',', array_keys((array) config('media.folders', []))),
        ], [
            'files.*.max' => 'Each file may not be larger than :max KB.',
            'files.*.mimes' => 'Only :values files are allowed.',
        ]);

        // Uploads land in the folder the library was opened from.
        $directory = $validated['folder'] ?? null
            ? config("media.folders.{$validated['folder']}.path", $this->dateDirectory())
            : $this->dateDirectory();

        $uploaded = [];
        $errors = [];

        foreach ($request->file('files', []) as $file) {
            if (!in_array($file->getMimeType(), (array) config('media.allowed_mimes'), true)
                && !in_array(strtolower($file->getClientOriginalExtension()), (array) config('media.allowed_extensions'), true)) {
                $errors[] = ['name' => $file->getClientOriginalName(), 'error' => 'Unsupported file type.'];
                continue;
            }

            // Skip exact duplicates: return the existing library record instead.
            $hash = sha1_file($file->getRealPath());
            $existing = Media::where('hash', $hash)->first();
            if ($existing) {
                $uploaded[] = array_merge($existing->toPickerArray(), ['duplicate' => true]);
                continue;
            }

            if (strtolower($file->getClientOriginalExtension()) === 'svg') {
                if (!config('media.allow_svg')) {
                    $errors[] = ['name' => $file->getClientOriginalName(), 'error' => 'SVG uploads are disabled.'];
                    continue;
                }
                $storedPath = $this->storeSvg($file, $directory);
                $thumbPath = null;
                [$width, $height] = [null, null];
            } else {
                $storedPath = $this->optimizer->storeOriginal($file, config('media.disk'), $directory);
                $thumbPath = null;
                [$width, $height] = $this->dimensions($storedPath);

                if ($storedPath && $width) {
                    $thumbPath = $this->generateThumbnail($storedPath);
                }
            }

            if (!$storedPath) {
                $errors[] = ['name' => $file->getClientOriginalName(), 'error' => 'Upload failed. Please try again.'];
                continue;
            }

            $media = Media::create([
                'disk' => config('media.disk'),
                'path' => $storedPath,
                'thumb_path' => $thumbPath,
                'original_name' => $this->safeOriginalName($file->getClientOriginalName()),
                'mime_type' => $file->getMimeType(),
                'size' => (int) (Storage::disk(config('media.disk'))->size($storedPath) ?: $file->getSize()),
                'width' => $width,
                'height' => $height,
                'hash' => $hash,
            ]);

            $uploaded[] = array_merge($media->toPickerArray(), ['duplicate' => false]);
        }

        if (empty($uploaded) && !empty($errors)) {
            return response()->json(['message' => $errors[0]['error'], 'errors' => $errors], 422);
        }

        return response()->json([
            'message' => count($uploaded).' image'.(count($uploaded) === 1 ? '' : 's').' added to media library.',
            'data' => $uploaded,
            'errors' => $errors,
        ]);
    }

    /**
     * Delete a media file and its database record.
     * Only removes the physical file — references held by other features
     * are the owner's responsibility to clean up first.
     */
    public function destroy(Media $media)
    {
        $disk = Storage::disk($media->disk);

        $this->optimizer->delete($media->thumb_path, $media->disk);
        $this->optimizer->delete($media->path, $media->disk);

        $media->delete();

        return response()->json(['message' => 'Image deleted from media library.']);
    }

    protected function dateDirectory(): string
    {
        return trim(config('media.directory'), '/').'/'.now()->format('Ymd');
    }

    protected function safeOriginalName(string $name): string
    {
        $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        $basename = pathinfo($name, PATHINFO_FILENAME);

        return Str::limit(preg_replace('/[^A-Za-z0-9\-_ ]+/', '', $basename) ?: 'image', 60).'.'.($extension ?: 'bin');
    }

    protected function storeSvg($file, string $directory): ?string
    {
        $sanitized = $this->sanitizeSvg(file_get_contents($file->getRealPath()));
        if ($sanitized === null) {
            return null;
        }

        $path = trim($directory, '/').'/'.Str::random(32).'.svg';

        return Storage::disk(config('media.disk'))->put($path, $sanitized) ? $path : null;
    }

    /**
     * Strip scripts, event handlers and javascript: URLs from SVG markup.
     */
    protected function sanitizeSvg(string $content): ?string
    {
        if (!str_contains($content, '<svg')) {
            return null;
        }

        $content = preg_replace('#<script\b[^>]*>.*?</script>#is', '', $content);
        $content = preg_replace('#<script\b[^>]*/?>#i', '', $content);
        $content = preg_replace('#\son\w+\s*=\s*(?:"[^"]*"|\'[^\']*\'|[^\s>]+)#i', '', $content);
        $content = preg_replace('#(href|xlink:href)\s*=\s*(["\'])\s*javascript:[^"\']*\2#i', '$1=$2#$2', $content);
        $content = preg_replace('#<!DOCTYPE[^>]*>#i', '', $content);

        return $content;
    }

    protected function dimensions(?string $path): array
    {
        if (!$path) {
            return [null, null];
        }

        try {
            $fullPath = Storage::disk(config('media.disk'))->path($path);
            $info = @getimagesize($fullPath);

            return $info ? [$info[0], $info[1]] : [null, null];
        } catch (\Throwable) {
            return [null, null];
        }
    }

    protected function generateThumbnail(string $path): ?string
    {
        $disk = Storage::disk(config('media.disk'));
        $thumbPath = preg_replace('/\.([a-z0-9]+)$/i', '-thumb.webp', $path);

        $ok = $this->optimizer->webpThumbnail(
            $disk->path($path),
            dirname($disk->path($path)).'/'.basename($thumbPath),
            (int) config('media.thumb_width', 400),
            (int) config('media.thumb_height', 400),
            (int) config('media.thumb_quality', 78),
        );

        return $ok ? $thumbPath : null;
    }
}
