<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\InteractsWithMedia;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SaleBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class SaleBannerController extends Controller
{
    use InteractsWithMedia;
    public function index()
    {
        $saleBanners = SaleBanner::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.sale-banners.index', compact('saleBanners'));
    }

    public function create()
    {
        $products = Product::active()->orderBy('name')->get(['id', 'name', 'price', 'discount_price']);

        return view('admin.sale-banners.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('sale-banners', 'public');
            $this->processImage($validated['image'], 1920);
        } elseif ($path = $this->mediaPath($request, 'image')) {
            $validated['image'] = $path;
        }

        if ($request->hasFile('product_image')) {
            $validated['product_image'] = $request->file('product_image')->store('sale-banners/products', 'public');
            $this->processImage($validated['product_image'], 800);
        } elseif ($path = $this->mediaPath($request, 'product_image')) {
            $validated['product_image'] = $path;
        }

        SaleBanner::create($this->packDisplaySettings($this->coerceBooleans($validated)));

        $this->syncProductSaleEndsAt($validated);

        return redirect()->route('admin.sale-banners.index')
            ->with('success', 'Sale banner created successfully.');
    }

    public function edit(SaleBanner $saleBanner)
    {
        $products = Product::active()->orderBy('name')->get(['id', 'name', 'price', 'discount_price']);

        return view('admin.sale-banners.edit', compact('saleBanner', 'products'));
    }

    public function update(Request $request, SaleBanner $saleBanner)
    {
        $validated = $this->validateData($request);

        if ($request->boolean('remove_image')) {
            $this->deleteImageSafe($saleBanner->image);
            $validated['image'] = null;
        } elseif ($request->hasFile('image')) {
            $this->deleteImageSafe($saleBanner->image);
            $validated['image'] = $request->file('image')->store('sale-banners', 'public');
            $this->processImage($validated['image'], 1920);
        } elseif ($path = $this->mediaPath($request, 'image')) {
            $this->deleteImageSafe($saleBanner->image);
            $validated['image'] = $path;
        }

        if ($request->boolean('remove_product_image')) {
            $this->deleteImageSafe($saleBanner->product_image);
            $validated['product_image'] = null;
        } elseif ($request->hasFile('product_image')) {
            $this->deleteImageSafe($saleBanner->product_image);
            $validated['product_image'] = $request->file('product_image')->store('sale-banners/products', 'public');
            $this->processImage($validated['product_image'], 800);
        } elseif ($path = $this->mediaPath($request, 'product_image')) {
            $this->deleteImageSafe($saleBanner->product_image);
            $validated['product_image'] = $path;
        }

        $saleBanner->update($this->packDisplaySettings($this->coerceBooleans($validated)));

        $this->syncProductSaleEndsAt($validated, $saleBanner);

        return redirect()->route('admin.sale-banners.index')
            ->with('success', 'Sale banner updated successfully.');
    }

    public function destroy(SaleBanner $saleBanner)
    {
        $this->clearProductSaleEndsAt($saleBanner);

        foreach (['image', 'product_image'] as $field) {
            if ($saleBanner->{$field} && Storage::disk('public')->exists($saleBanner->{$field})) {
                Storage::disk('public')->delete($saleBanner->{$field});
            }
        }

        $saleBanner->delete();

        return redirect()->route('admin.sale-banners.index')
            ->with('success', 'Sale banner deleted successfully.');
    }

    public function toggleEnabled(SaleBanner $saleBanner)
    {
        $saleBanner->update(['is_enabled' => ! $saleBanner->is_enabled]);

        $this->syncProductSaleEndsAt([], $saleBanner);

        return response()->json([
            'is_enabled' => $saleBanner->is_enabled,
            'message' => 'Sale banner '.($saleBanner->is_enabled ? 'enabled' : 'disabled').'.',
        ]);
    }

    public function updateSortOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|integer|exists:sale_banners,id',
            'items.*.sort_order' => 'required|integer|min:0',
        ]);

        foreach ($request->items as $item) {
            SaleBanner::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['message' => 'Sort order updated.']);
    }

    protected function coerceBooleans(array $validated): array
    {
        foreach ([
            'is_enabled', 'show_countdown', 'enable_badge', 'enable_product_image',
            'enable_prices', 'enable_buttons', 'enable_overlay',
        ] as $key) {
            if (array_key_exists($key, $validated)) {
                $validated[$key] = filter_var($validated[$key], FILTER_VALIDATE_BOOLEAN);
            }
        }

        return $validated;
    }

    protected function validateData(Request $request): array
    {
        return $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'image_media_id' => 'nullable|integer|exists:media,id',
                'product_image_media_id' => 'nullable|integer|exists:media,id',
            'link' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:255',
            'secondary_button_text' => 'nullable|string|max:255',
            'secondary_button_url' => 'nullable|string|max:255',
            'badge' => 'nullable|string|max:255',
            'badge_color' => 'nullable|string|max:50',
            'featured_product_id' => 'nullable|exists:products,id',
            'background_color' => 'nullable|string|max:20',
            'gradient_from' => 'nullable|string|max:20',
            'gradient_to' => 'nullable|string|max:20',
            'text_alignment' => 'nullable|in:left,center,right',
            'image_position' => 'nullable|string|max:30',
            'overlay_opacity' => 'nullable|integer|min:0|max:100',
            'text_color' => 'nullable|string|max:50',
            'show_countdown' => 'boolean',
            'countdown_end_date' => 'nullable|date',
            'countdown_end_time' => 'nullable|date_format:H:i,H:i:s',
            'countdown_timezone' => 'nullable|string|max:64',
            'enable_badge' => 'boolean',
            'enable_product_image' => 'boolean',
            'enable_prices' => 'boolean',
            'enable_buttons' => 'boolean',
            'enable_overlay' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'is_enabled' => 'boolean',
            'image_fit' => 'nullable|string|max:20',
            'image_repeat' => 'nullable|string|max:20',
            'banner_height' => 'nullable|string|max:20',
            'banner_height_custom' => 'nullable|integer|min:50|max:2000',
            'overlay_enabled' => 'boolean',
            'overlay_color' => 'nullable|string|max:20',
            'content_vertical' => 'nullable|in:top,center,bottom',
            'border_radius' => 'nullable|string|max:20',
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
            'grayscale' => 'boolean',
            'text_width' => 'nullable|string|max:20',
            'show_desktop' => 'boolean',
            'show_tablet' => 'boolean',
            'show_mobile' => 'boolean',
        ]);
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
            Log::warning('Sale banner image processing failed: '.$e->getMessage());
        }
    }

    protected function syncProductSaleEndsAt(array $validated, ?SaleBanner $existingBanner = null): void
    {
        $productId = $validated['featured_product_id'] ?? $existingBanner?->featured_product_id;
        $endsAt = $validated['ends_at'] ?? $existingBanner?->ends_at;

        if (! $productId) {
            return;
        }

        $bannerActive = ($validated['is_enabled'] ?? $existingBanner?->is_enabled ?? false)
            && $endsAt
            && \Carbon\Carbon::parse($endsAt)->isFuture();

        Product::where('id', $productId)->update([
            'sale_ends_at' => $bannerActive ? $endsAt : null,
        ]);
    }

    protected function clearProductSaleEndsAt(SaleBanner $saleBanner): void
    {
        if ($saleBanner->featured_product_id) {
            Product::where('id', $saleBanner->featured_product_id)->update(['sale_ends_at' => null]);
        }
    }
}
