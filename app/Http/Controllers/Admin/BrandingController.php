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

        return view('admin.branding.edit', compact('logoPath', 'logoUrl', 'logoMeta'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'logo_media_id' => 'nullable|integer|exists:media,id',
        ]);

        $oldPath = Setting::get('site_logo');

        // Picker "Remove" action — clear the logo without deleting a
        // media-tracked file (shared assets are never removed implicitly).
        if ($request->boolean('remove_logo') && empty($validated['logo_media_id'])) {
            if ($oldPath) {
                $this->deleteOldLogo($oldPath, '');
                Setting::forget('site_logo');
            }

            return redirect()->route('admin.branding.edit')
                ->with('success', 'Logo removed. The site now uses the default text-based brand.');
        }

        if (empty($validated['logo_media_id'])) {
            return redirect()->route('admin.branding.edit')
                ->with('info', 'Choose an image from the media library to update the logo.');
        }

        // Selection from the media library — reference the stored file,
        // never copy or delete it (it may be reused elsewhere).
        $media = Media::findOrFail($validated['logo_media_id']);

        Setting::set('site_logo', $media->path);
        $this->deleteOldLogo($oldPath, $media->path);

        return redirect()->route('admin.branding.edit')
            ->with('success', 'Logo updated successfully.');
    }

    /**
     * Remove the previous logo file unless it is tracked in the media
     * library (shared assets must never be deleted implicitly).
     */
    protected function deleteOldLogo(?string $oldPath, string $newPath): void
    {
        if (!$oldPath || $oldPath === $newPath) {
            return;
        }

        if (Media::where('path', $oldPath)->exists()) {
            return;
        }

        if (Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }
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
