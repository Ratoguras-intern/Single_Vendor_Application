<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

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
        $validated = $request->validate(array_merge([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,avif,gif|max:10240',
            'mobile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,avif,gif|max:10240',
            'link' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:255',
            'secondary_button_text' => 'nullable|string|max:255',
            'secondary_button_url' => 'nullable|string|max:255',
            'badge' => 'nullable|string|max:255',
            'badge_color' => 'nullable|string|max:255',
            'text_alignment' => 'nullable|in:left,center,right',
            'image_position' => 'nullable|in:center,top,bottom,left,right,left top,right top,left bottom,right bottom,center center,top center,bottom center,center left,center right,top left,top right,bottom left,bottom right',
            'overlay_opacity' => 'nullable|integer|min:0|max:100',
            'text_color' => 'nullable|string|max:50',
            'show_countdown' => 'nullable|boolean',
            'position' => "required|in:{$positions}",
            'target_pages' => 'nullable|array',
            'target_pages.*' => 'string|in:category,shop',
            'is_enabled' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ], $this->displayRules()));

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('banners', 'public');
            $this->processImage($validated['image'], 1920);
        }

        if ($request->hasFile('mobile_image')) {
            $validated['mobile_image'] = $request->file('mobile_image')->store('banners/mobile', 'public');
            $this->processImage($validated['mobile_image'], 640, 460, true);
        }

        $validated['is_enabled'] = $request->boolean('is_enabled');
        $validated['show_countdown'] = $request->boolean('show_countdown');

        Banner::create($this->packDisplaySettings($validated));
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
        $validated = $request->validate(array_merge([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,avif,gif|max:10240',
            'mobile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,avif,gif|max:10240',
            'link' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:255',
            'secondary_button_text' => 'nullable|string|max:255',
            'secondary_button_url' => 'nullable|string|max:255',
            'badge' => 'nullable|string|max:255',
            'badge_color' => 'nullable|string|max:255',
            'text_alignment' => 'nullable|in:left,center,right',
            'image_position' => 'nullable|in:center,top,bottom,left,right,left top,right top,left bottom,right bottom,center center,top center,bottom center,center left,center right,top left,top right,bottom left,bottom right',
            'overlay_opacity' => 'nullable|integer|min:0|max:100',
            'text_color' => 'nullable|string|max:50',
            'show_countdown' => 'nullable|boolean',
            'position' => "required|in:{$positions}",
            'target_pages' => 'nullable|array',
            'target_pages.*' => 'string|in:category,shop',
            'is_enabled' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ], $this->displayRules()));

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
            $this->processImage($validated['image'], 1920);
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
            $this->processImage($validated['mobile_image'], 640, 460, true);
        }

        $validated['is_enabled'] = $request->boolean('is_enabled');
        $validated['show_countdown'] = $request->boolean('show_countdown');

        $banner->update($this->packDisplaySettings($validated));
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

    protected function displayRules(): array
    {
        return [
            'image_fit' => 'nullable|in:cover,contain,fill,none,scale-down',
            'image_repeat' => 'nullable|in:no-repeat,repeat,repeat-x,repeat-y',
            'banner_height' => 'nullable|in:small,medium,large,xlarge,full_screen,custom',
            'banner_height_custom' => 'nullable|integer|min:50|max:2000',
            'overlay_enabled' => 'nullable|boolean',
            'overlay_color' => 'nullable|string|regex:/^#[0-9a-fA-F]{6}$/',
            'content_vertical' => 'nullable|in:top,center,bottom',
            'border_radius' => 'nullable|in:none,small,medium,large,xlarge,custom',
            'border_radius_custom' => 'nullable|integer|min:0|max:200',
            'padding_top' => 'nullable|integer|min:0|max:200',
            'padding_bottom' => 'nullable|integer|min:0|max:200',
            'padding_left' => 'nullable|integer|min:0|max:200',
            'padding_right' => 'nullable|integer|min:0|max:200',
            'margin_top' => 'nullable|integer|min:0|max:200',
            'margin_bottom' => 'nullable|integer|min:0|max:200',
            'zoom' => 'nullable|integer|min:50|max:200',
            'brightness' => 'nullable|integer|min:0|max:200',
            'contrast' => 'nullable|integer|min:0|max:200',
            'saturation' => 'nullable|integer|min:0|max:200',
            'blur' => 'nullable|integer|min:0|max:20',
            'grayscale' => 'nullable|boolean',
            'text_width' => 'nullable|in:narrow,medium,wide,full',
            'show_desktop' => 'nullable|boolean',
            'show_tablet' => 'nullable|boolean',
            'show_mobile' => 'nullable|boolean',
        ];
    }

    protected function packDisplaySettings(array $validated): array
    {
        $displayKeys = [
            'image_fit', 'image_repeat',
            'banner_height', 'banner_height_custom',
            'overlay_enabled', 'overlay_color',
            'content_vertical', 'border_radius', 'border_radius_custom',
            'padding_top', 'padding_bottom', 'padding_left', 'padding_right',
            'margin_top', 'margin_bottom',
            'zoom', 'brightness', 'contrast', 'saturation', 'blur', 'grayscale',
            'text_width', 'show_desktop', 'show_tablet', 'show_mobile',
        ];

        $display = array_intersect_key($validated, array_flip($displayKeys));

        foreach (['overlay_enabled', 'grayscale', 'show_desktop', 'show_tablet', 'show_mobile'] as $booleanKey) {
            if (array_key_exists($booleanKey, $display)) {
                $display[$booleanKey] = (bool) ($display[$booleanKey] ?? false);
            }
        }

        if (empty($display)) {
            return $validated;
        }

        $validated['style_settings'] = $display;

        return array_diff_key($validated, array_flip($displayKeys));
    }

    protected function processImage(string $path, int $maxWidth, ?int $maxHeight = null, bool $crop = false): void
    {
        $fullPath = Storage::disk('public')->path($path);

        if (!file_exists($fullPath)) {
            return;
        }

        try {
            $manager = new ImageManager(new Driver());
            $image = $manager->decodePath($fullPath);

            if ($crop && $maxHeight) {
                $image->cover($maxWidth, $maxHeight);
            } else {
                $image->scaleDown($maxWidth);
            }

            $image->save();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Banner image processing failed: ' . $e->getMessage());
        }
    }
}
