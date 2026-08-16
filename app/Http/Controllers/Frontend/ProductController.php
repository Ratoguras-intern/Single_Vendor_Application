<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function show(int $id)
    {
        $product = Product::with(['images', 'category', 'brand'])->where('status', true)->find($id);

        if (! $product) {
            abort(404);
        }

        $primaryImage = $product->primaryImage();
        $product = [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->discount_price ?? $product->price,
            'image' => product_image_url($primaryImage?->image),
            'description' => $product->description,
        ];

        $relatedProducts = Product::with(['images', 'category', 'brand'])
            ->where('status', true)
            ->where('id', '!=', $id)
            ->latest()
            ->take(4)
            ->get()
            ->map(function ($p) {
                $image = $p->primaryImage();

                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'price' => $p->discount_price ?? $p->price,
                    'image' => product_image_url($image?->image),
                    'description' => $p->description,
                ];
            })
            ->toArray();

        return view('frontend.product-details', compact('product', 'relatedProducts'));
    }
}
