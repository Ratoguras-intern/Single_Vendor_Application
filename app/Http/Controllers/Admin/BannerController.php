<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        $query = Banner::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('subtitle', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            match ($request->status) {
                'active' => $query->active(),
                'expired' => $query->expired(),
                'scheduled' => $query->scheduled(),
                'inactive' => $query->where('is_enabled', false),
                default => null,
            };
        }

        if ($request->filled('position')) {
            $query->where('position', $request->position);
        }

        $banners = $query->orderBy('sort_order')->orderBy('id')->paginate(15)->withQueryString();
        $positions = config('banners.positions');

        return view('admin.banners.index', compact('banners', 'positions'));
    }

    public function create()
    {
        $positions = config('banners.positions');
        $pages = config('banners.pages');
        return view('admin.banners.create', compact('positions', 'pages'));
    }

    public function store(Request $request)
    {
        $positions = implode(',', config('banners.positions'));
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,avif|max:5120',
            'mobile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,avif|max:5120',
            'link' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:255',
            'secondary_button_text' => 'nullable|string|max:255',
            'secondary_button_url' => 'nullable|string|max:255',
            'badge' => 'nullable|string|max:255',
            'badge_color' => 'nullable|string|max:255',
            'text_alignment' => 'nullable|in:left,center,right',
            'overlay_opacity' => 'nullable|integer|min:0|max:100',
            'text_color' => 'nullable|string|max:50',
            'show_countdown' => 'nullable|boolean',
            'position' => "required|in:{$positions}",
            'target_pages' => 'nullable|array',
            'target_pages.*' => 'string|in:hero,promotional,middle,featured-section,bottom,sidebar,category,shop',
            'is_enabled' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('banners', 'public');
        }

        if ($request->hasFile('mobile_image')) {
            $validated['mobile_image'] = $request->file('mobile_image')->store('banners/mobile', 'public');
        }

        $validated['is_enabled'] = $request->boolean('is_enabled');
        $validated['show_countdown'] = $request->boolean('show_countdown');

        Banner::create($validated);
        Banner::clearCache();

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner created successfully.');
    }

    public function show(Banner $banner)
    {
        return view('admin.banners.show', compact('banner'));
    }

    public function edit(Banner $banner)
    {
        $positions = config('banners.positions');
        $pages = config('banners.pages');
        return view('admin.banners.edit', compact('banner', 'positions', 'pages'));
    }

    public function update(Request $request, Banner $banner)
    {
        $positions = implode(',', config('banners.positions'));
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,avif|max:5120',
            'mobile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,avif|max:5120',
            'link' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:255',
            'secondary_button_text' => 'nullable|string|max:255',
            'secondary_button_url' => 'nullable|string|max:255',
            'badge' => 'nullable|string|max:255',
            'badge_color' => 'nullable|string|max:255',
            'text_alignment' => 'nullable|in:left,center,right',
            'overlay_opacity' => 'nullable|integer|min:0|max:100',
            'text_color' => 'nullable|string|max:50',
            'show_countdown' => 'nullable|boolean',
            'position' => "required|in:{$positions}",
            'target_pages' => 'nullable|array',
            'target_pages.*' => 'string|in:hero,promotional,middle,featured-section,bottom,sidebar,category,shop',
            'is_enabled' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        if ($request->boolean('remove_image')) {
            if ($banner->image && Storage::disk('public')->exists($banner->image)) {
                Storage::disk('public')->delete($banner->image);
            }
            $validated['image'] = null;
        } elseif ($request->hasFile('image')) {
            if ($banner->image && Storage::disk('public')->exists($banner->image)) {
                Storage::disk('public')->delete($banner->image);
            }
            $validated['image'] = $request->file('image')->store('banners', 'public');
        }

        if ($request->boolean('remove_mobile_image')) {
            if ($banner->mobile_image && Storage::disk('public')->exists($banner->mobile_image)) {
                Storage::disk('public')->delete($banner->mobile_image);
            }
            $validated['mobile_image'] = null;
        } elseif ($request->hasFile('mobile_image')) {
            if ($banner->mobile_image && Storage::disk('public')->exists($banner->mobile_image)) {
                Storage::disk('public')->delete($banner->mobile_image);
            }
            $validated['mobile_image'] = $request->file('mobile_image')->store('banners/mobile', 'public');
        }

        $validated['is_enabled'] = $request->boolean('is_enabled');
        $validated['show_countdown'] = $request->boolean('show_countdown');

        $banner->update($validated);
        Banner::clearCache();

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner updated successfully.');
    }

    public function destroy(Banner $banner)
    {
        if ($banner->image && Storage::disk('public')->exists($banner->image)) {
            Storage::disk('public')->delete($banner->image);
        }
        if ($banner->mobile_image && Storage::disk('public')->exists($banner->mobile_image)) {
            Storage::disk('public')->delete($banner->mobile_image);
        }

        $banner->delete();
        Banner::clearCache();

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner deleted successfully.');
    }

    public function toggleEnabled(Banner $banner)
    {
        $banner->update(['is_enabled' => !$banner->is_enabled]);
        Banner::clearCache();

        return response()->json([
            'is_enabled' => $banner->is_enabled,
            'message' => 'Banner ' . ($banner->is_enabled ? 'enabled' : 'disabled') . '.',
        ]);
    }

    public function duplicate(Banner $banner)
    {
        $clone = $banner->replicate(['sort_order']);
        $clone->title = ($banner->title ?? 'Banner') . ' (Copy)';
        $clone->sort_order = Banner::where('position', $banner->position)->max('sort_order') + 1;
        $clone->save();
        Banner::clearCache();

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner duplicated successfully.');
    }

    public function updateSortOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|integer|exists:banners,id',
            'items.*.sort_order' => 'required|integer|min:0',
        ]);

        foreach ($request->items as $item) {
            Banner::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        Banner::clearCache();

        return response()->json(['message' => 'Sort order updated.']);
    }
}
