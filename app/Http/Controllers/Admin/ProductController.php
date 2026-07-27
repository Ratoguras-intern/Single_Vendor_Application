<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'images']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status === 'active');
        }

        $allowedVisibility = [
            'is_featured', 'is_new_arrival', 'is_trending', 'is_best_seller',
            'is_flash_sale', 'is_recommended', 'is_popular', 'is_limited_edition',
        ];
        if ($request->filled('visibility') && in_array($request->visibility, $allowedVisibility)) {
            $query->where($request->visibility, true);
        }

        $products = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::where('status', true)->get();
        $brands = Brand::where('status', true)->get();

        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }

    public function create()
    {
        $categories = Category::where('status', true)->get();
        $brands = Brand::where('status', true)->get();

        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|string|max:255|unique:products,sku',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'status' => 'required|in:active,inactive',
            'images' => 'nullable|array|max:5',
            'images.*' => 'mimetypes:image/jpeg,image/png,image/webp,image/avif|mimes:jpg,jpeg,png,webp,avif|max:2048',
            'primary_image' => 'nullable|integer',
            'is_featured' => 'nullable|boolean',
            'is_new_arrival' => 'nullable|boolean',
            'is_trending' => 'nullable|boolean',
            'is_best_seller' => 'nullable|boolean',
            'is_flash_sale' => 'nullable|boolean',
            'is_recommended' => 'nullable|boolean',
            'is_popular' => 'nullable|boolean',
            'is_limited_edition' => 'nullable|boolean',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['status'] = $validated['status'] === 'active';
        unset($validated['images'], $validated['primary_image']);

        $flags = [
            'is_featured', 'is_new_arrival', 'is_trending', 'is_best_seller',
            'is_flash_sale', 'is_recommended', 'is_popular', 'is_limited_edition',
        ];
        foreach ($flags as $flag) {
            $validated[$flag] = $request->boolean($flag);
        }

        $product = Product::create($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $path,
                    'is_primary' => $request->primary_image == $index,
                ]);
            }

            if ($request->primary_image === null && $request->hasFile('images')) {
                $firstImage = $product->images()->first();
                if ($firstImage) {
                    $firstImage->update(['is_primary' => true]);
                }
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'brand', 'images']);

        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $product->load('images');
        $categories = Category::where('status', true)->get();
        $brands = Brand::where('status', true)->get();

        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug,'.$product->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|string|max:255|unique:products,sku,'.$product->id,
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'status' => 'required|in:active,inactive',
            'images' => 'nullable|array|max:5',
            'images.*' => 'mimetypes:image/jpeg,image/png,image/webp,image/avif|mimes:jpg,jpeg,png,webp,avif|max:2048',
            'primary_image' => 'nullable|integer',
            'remove_images' => 'nullable|array',
            'remove_images.*' => 'integer',
            'is_featured' => 'nullable|boolean',
            'is_new_arrival' => 'nullable|boolean',
            'is_trending' => 'nullable|boolean',
            'is_best_seller' => 'nullable|boolean',
            'is_flash_sale' => 'nullable|boolean',
            'is_recommended' => 'nullable|boolean',
            'is_popular' => 'nullable|boolean',
            'is_limited_edition' => 'nullable|boolean',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['status'] = $validated['status'] === 'active';
        unset($validated['images'], $validated['primary_image'], $validated['remove_images']);

        $flags = [
            'is_featured', 'is_new_arrival', 'is_trending', 'is_best_seller',
            'is_flash_sale', 'is_recommended', 'is_popular', 'is_limited_edition',
        ];
        foreach ($flags as $flag) {
            $validated[$flag] = $request->boolean($flag);
        }

        $product->update($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $path,
                    'is_primary' => false,
                ]);
            }
        }

        if ($request->filled('remove_images')) {
            foreach ($request->remove_images as $imageId) {
                $image = ProductImage::find($imageId);
                if ($image && $image->product_id === $product->id) {
                    if (Storage::disk('public')->exists($image->image)) {
                        Storage::disk('public')->delete($image->image);
                    }
                    $image->delete();
                }
            }
        }

        if ($request->filled('primary_image')) {
            $product->images()->update(['is_primary' => false]);
            $newPrimary = ProductImage::where('product_id', $product->id)
                ->where('id', $request->primary_image)
                ->first();
            if ($newPrimary) {
                $newPrimary->update(['is_primary' => true]);
            }
        } elseif ($product->images()->count() > 0 && $product->images()->where('is_primary', true)->count() === 0) {
            $product->images()->first()->update(['is_primary' => true]);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        foreach ($product->images as $image) {
            if (Storage::disk('public')->exists($image->image)) {
                Storage::disk('public')->delete($image->image);
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function toggleFlag(Product $product, string $flag)
    {
        $allowed = [
            'is_featured', 'is_new_arrival', 'is_trending', 'is_best_seller',
            'is_flash_sale', 'is_recommended', 'is_popular', 'is_limited_edition',
        ];

        abort_unless(in_array($flag, $allowed), 400);

        $product->update([$flag => !$product->$flag]);

        return response()->json([
            'flag' => $flag,
            'value' => $product->$flag,
            'message' => 'Product flag updated.',
        ]);
    }
}
