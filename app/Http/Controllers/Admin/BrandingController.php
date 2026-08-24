<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandingController extends Controller
{
    public function edit()
    {
        $logoPath = Setting::get('site_logo');
        $logoUrl = $logoPath ? Storage::disk('public')->url($logoPath) : null;
        $logoMeta = null;

        if ($logoPath && Storage::disk('public')->exists($logoPath)) {
            $fullPath = Storage::disk('public')->path($logoPath);
            $logoMeta = [
                'filename' => basename($logoPath),
                'type' => mime_content_type($fullPath) ?? 'unknown',
                'size' => Storage::disk('public')->size($logoPath),
                'updated_at' => Setting::where('key', 'site_logo')->first()?->updated_at,
            ];
        }

        $faviconPath = Setting::get('site_favicon');
        $faviconUrl = ($faviconPath && Storage::disk('public')->exists($faviconPath))
            ? Storage::disk('public')->url($faviconPath)
            : null;

        return view('admin.branding.edit', compact(
            'logoPath', 'logoUrl', 'logoMeta',
            'faviconPath', 'faviconUrl'
        ));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'nullable|string|max:50',
            'logo_media_id' => 'nullable|integer|exists:media,id',
            'favicon_media_id' => 'nullable|integer|exists:media,id',
        ]);

        if (array_key_exists('site_name', $validated)) {
            $name = trim($validated['site_name']);

            if ($name === '') {
                Setting::forget('site_name');
            } else {
                Setting::set('site_name', $name);
            }
        }

        $this->handleLogo($request, $validated);
        $this->handleFavicon($request, $validated);

        return redirect()->route('admin.branding.edit')
            ->with('success', 'Branding updated successfully.');
    }

    protected function handleLogo(Request $request, array $validated): void
    {
        // Picker "Remove" action — clear the logo without deleting a
        // media-tracked file (shared assets are never removed implicitly).
        if ($request->boolean('remove_logo') && empty($validated['logo_media_id'] ?? null)) {
            Setting::forget('site_logo');

            return;
        }

        if (empty($validated['logo_media_id'])) {
            return;
        }

        // Selection from the media library — reference the stored file,
        // never copy or delete it (it may be reused elsewhere).
        $media = Media::findOrFail($validated['logo_media_id']);

        Setting::set('site_logo', $media->path);
    }

    protected function handleFavicon(Request $request, array $validated): void
    {
        if ($request->boolean('remove_favicon') && empty($validated['favicon_media_id'] ?? null)) {
            Setting::forget('site_favicon');

            return;
        }

        if (empty($validated['favicon_media_id'])) {
            return;
        }

        $media = Media::findOrFail($validated['favicon_media_id']);

        Setting::set('site_favicon', $media->path);
    }

    public function destroy()
    {
        $logoPath = Setting::get('site_logo');

        if ($logoPath && Storage::disk('public')->exists($logoPath)) {
            Storage::disk('public')->delete($logoPath);
        }

        Setting::forget('site_logo');

        return redirect()->route('admin.branding.edit')
            ->with('success', 'Logo removed successfully.');
    }
}
