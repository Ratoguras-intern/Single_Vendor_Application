<?php

namespace App\Http\Controllers\Frontend\Concerns;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait FilterProducts
{
    protected function applyProductFilters(Builder $query, Request $request): Builder
    {
        if ($brands = $request->input('brand')) {
            $brands = (array) $brands;
            $query->whereHas('brand', fn ($q) => $q->whereIn('slug', $brands));
        }

        if ($request->filled('min_price')) {
            $query->where(function ($q) use ($request) {
                $q->where('price', '>=', $request->input('min_price'))
                    ->orWhere('discount_price', '>=', $request->input('min_price'));
            });
        }

        if ($request->filled('max_price')) {
            $query->where(function ($q) use ($request) {
                $q->where('price', '<=', $request->input('max_price'));
            });
        }

        if ($request->boolean('in_stock')) {
            $query->where('stock', '>', 0);
        }

        if ($request->boolean('on_sale')) {
            $query->whereNotNull('discount_price')->where('discount_price', '>', 0);
        }

        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        if ($request->boolean('new_arrivals')) {
            $query->where('is_new_arrival', true);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    protected function applyProductSort(Builder $query, Request $request): Builder
    {
        return match ($request->input('sort', 'latest')) {
            'newest' => $query->latest(),
            'oldest' => $query->oldest(),
            'price_asc' => $query->orderBy('discount_price')->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'name_asc' => $query->orderBy('name'),
            'name_desc' => $query->orderByDesc('name'),
            'discount' => $query->orderBy('discount_price', 'asc')->orderBy('price', 'desc'),
            'best_selling' => $query->withSum('orderItems', 'quantity')
                ->orderByDesc('order_items_sum_quantity'),
            default => $query->latest(),
        };
    }

    protected function mapProduct(Product $product): array
    {
        $image = $product->primaryImage();
        $effectivePrice = $product->discount_price ?? $product->price;
        $discountPct = $product->discount_price
            ? round((($product->price - $product->discount_price) / $product->price) * 100)
            : 0;

        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'price' => (float) $effectivePrice,
            'original_price' => $product->discount_price ? (float) $product->price : null,
            'image' => $image?->image
                ? Storage::disk('public')->url($image->image)
                : asset('frontend-assets/images/no-image.jpg'),
            'description' => $product->description,
            'brand' => $product->brand?->name,
            'stock' => $product->stock,
            'discount_percentage' => $discountPct > 0 ? $discountPct : null,
            'is_new' => $product->created_at && $product->created_at->diffInDays(now()) <= 14,
            'is_best_seller' => $product->is_best_seller,
            'is_flash_sale' => $product->is_flash_sale,
            'is_limited_edition' => $product->is_limited_edition,
            'is_featured' => $product->is_featured,
            'is_trending' => $product->is_trending,
            'is_new_arrival' => $product->is_new_arrival,
        ];
    }
}
