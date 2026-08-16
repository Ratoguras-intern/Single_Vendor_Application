<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBannerRequest;
use App\Http\Requests\Admin\UpdateBannerRequest;
use App\Models\Banner;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

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
        $products = Product::active()->orderBy('name')->get(['id', 'name', 'price', 'discount_price']);

        return view('admin.banners.create', compact('positions', 'pages', 'products'));
    }

    public function store(StoreBannerRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('banners', 'public');
            $this->processImage($validated['image'], 1920);
        }

        if ($request->hasFile('mobile_image')) {
            $validated['mobile_image'] = $request->file('mobile_image')->store('banners/mobile', 'public');
            $this->processImage($validated['mobile_image'], 640, 460, true);
        }

        if ($request->hasFile('product_image')) {
            $validated['product_image'] = $request->file('product_image')->store('banners/products', 'public');
            $this->processImage($validated['product_image'], 800);
        }

        $validated['is_enabled'] = $request->boolean('is_enabled');
        $validated['show_countdown'] = $request->boolean('show_countdown');
        $validated['enable_badge'] = $request->boolean('enable_badge');
        $validated['enable_product_image'] = $request->boolean('enable_product_image');
        $validated['enable_prices'] = $request->boolean('enable_prices');
        $validated['enable_buttons'] = $request->boolean('enable_buttons');
        $validated['enable_overlay'] = $request->boolean('enable_overlay');

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
        $products = Product::active()->orderBy('name')->get(['id', 'name', 'price', 'discount_price']);

        return view('admin.banners.edit', compact('banner', 'positions', 'pages', 'products'));
    }

    public function update(UpdateBannerRequest $request, Banner $banner)
    {
        $validated = $request->validated();

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

        if ($request->boolean('remove_product_image')) {
            if ($banner->product_image && Storage::disk('public')->exists($banner->product_image)) {
                Storage::disk('public')->delete($banner->product_image);
            }
            $validated['product_image'] = null;
        } elseif ($request->hasFile('product_image')) {
            if ($banner->product_image && Storage::disk('public')->exists($banner->product_image)) {
                Storage::disk('public')->delete($banner->product_image);
            }
            $validated['product_image'] = $request->file('product_image')->store('banners/products', 'public');
            $this->processImage($validated['product_image'], 800);
        }

        $validated['is_enabled'] = $request->boolean('is_enabled');
        $validated['show_countdown'] = $request->boolean('show_countdown');
        $validated['enable_badge'] = $request->boolean('enable_badge');
        $validated['enable_product_image'] = $request->boolean('enable_product_image');
        $validated['enable_prices'] = $request->boolean('enable_prices');
        $validated['enable_buttons'] = $request->boolean('enable_buttons');
        $validated['enable_overlay'] = $request->boolean('enable_overlay');

        $banner->update($this->packDisplaySettings($validated));
        Banner::clearCache();

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner updated successfully.');
    }

    public function destroy(Banner $banner)
    {
        foreach (['image', 'mobile_image', 'product_image'] as $field) {
            if ($banner->{$field} && Storage::disk('public')->exists($banner->{$field})) {
                Storage::disk('public')->delete($banner->{$field});
            }
        }

        $banner->delete();
        Banner::clearCache();

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner deleted successfully.');
    }

    public function toggleEnabled(Banner $banner)
    {
        $banner->update(['is_enabled' => ! $banner->is_enabled]);
        Banner::clearCache();

        return response()->json([
            'is_enabled' => $banner->is_enabled,
            'message' => 'Banner '.($banner->is_enabled ? 'enabled' : 'disabled').'.',
        ]);
    }

    public function duplicate(Banner $banner)
    {
        $clone = $banner->replicate(['sort_order', 'image', 'mobile_image']);
        $clone->title = ($banner->title ?? 'Banner') . ' (Copy)';
        $clone->sort_order = Banner::where('position', $banner->position)->max('sort_order') + 1;

        foreach (['image', 'mobile_image'] as $field) {
            if ($banner->{$field} && Storage::disk('public')->exists($banner->{$field})) {
                $ext = pathinfo($banner->{$field}, PATHINFO_EXTENSION);
                $copy = dirname($banner->{$field}) . '/' . Str::random(40) . ".{$ext}";
                Storage::disk('public')->copy($banner->{$field}, $copy);
                $clone->{$field} = $copy;
            }
        }

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

        if (! file_exists($fullPath)) {
            return;
        }

        try {
            $manager = new ImageManager(new Driver);
            $image = $manager->decodePath($fullPath);

            if ($crop && $maxHeight) {
                $image->cover($maxWidth, $maxHeight);
            } else {
                $image->scaleDown($maxWidth);
            }

            $image->save();
        } catch (\Exception $e) {
            Log::warning('Banner image processing failed: '.$e->getMessage());
        }
    }
}
