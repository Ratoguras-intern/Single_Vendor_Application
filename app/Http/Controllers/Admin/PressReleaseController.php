<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\InteractsWithMedia;
use App\Http\Controllers\Controller;
use App\Models\PressRelease;
use App\Services\HtmlSanitizer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PressReleaseController extends Controller
{
    use InteractsWithMedia;

    public function index(Request $request)
    {
        $query = PressRelease::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $releases = $query->orderByDesc('released_at')->orderByDesc('id')->paginate(15)->withQueryString();

        return view('admin.press-releases.index', compact('releases'));
    }

    public function create()
    {
        return view('admin.press-releases.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('press', 'public');
        } elseif ($path = $this->mediaPath($request, 'featured_image')) {
            $validated['featured_image'] = $path;
        }

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $validated['content'] = app(HtmlSanitizer::class)->clean((string) ($validated['content'] ?? ''));

        PressRelease::create($validated);

        return redirect()->route('admin.press-releases.index')
            ->with('success', 'Press release created successfully.');
    }

    public function edit(PressRelease $release)
    {
        return view('admin.press-releases.edit', ['release' => $release]);
    }

    public function update(Request $request, PressRelease $release)
    {
        $validated = $this->validateData($request, $release);

        if ($request->boolean('remove_featured_image')) {
            $this->deleteImageSafe($release->featured_image);
            $validated['featured_image'] = null;
        } elseif ($request->hasFile('featured_image')) {
            $this->deleteImageSafe($release->featured_image);
            $validated['featured_image'] = $request->file('featured_image')->store('press', 'public');
        } elseif ($path = $this->mediaPath($request, 'featured_image')) {
            $this->deleteImageSafe($release->featured_image);
            $validated['featured_image'] = $path;
        }

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $validated['content'] = app(HtmlSanitizer::class)->clean((string) ($validated['content'] ?? ''));

        $release->update($validated);

        return redirect()->route('admin.press-releases.index')
            ->with('success', 'Press release updated successfully.');
    }

    public function destroy(PressRelease $release)
    {
        $this->deleteImageSafe($release->featured_image);

        $release->delete();

        return redirect()->route('admin.press-releases.index')
            ->with('success', 'Press release deleted successfully.');
    }

    protected function validateData(Request $request, ?PressRelease $release = null): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:press_releases,slug'.($release ? ','.$release->id : ''),
            'summary' => 'nullable|string|max:1000',
            'content' => 'nullable|string',
            'featured_image' => 'nullable|image|mimetypes:image/jpeg,image/png,image/webp,image/avif|mimes:jpg,jpeg,png,webp,avif|max:5120',
            'featured_image_media_id' => 'nullable|integer|exists:media,id',
            'released_at' => 'nullable|date',
            'status' => 'required|in:draft,published',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
        ]);
    }
}
