<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function show(int $id)
    {
        $productModel = Product::with(['images', 'category', 'brand'])->where('status', true)->find($id);

        if (! $productModel) {
            abort(404);
        }

        $primaryImage = $productModel->primaryImage();
        $product = [
            'id' => $productModel->id,
            'name' => $productModel->name,
            'price' => $productModel->discount_price ?? $productModel->price,
            'original_price' => $productModel->discount_price ? $productModel->price : null,
            'image' => product_image_url($primaryImage?->image),
            'description' => $productModel->description,
            'stock' => $productModel->stock,
            'average_rating' => (float) $productModel->average_rating,
            'reviews_count' => $productModel->reviews_count,
        ];

        $ratingBreakdown = ProductReview::approved()
            ->where('product_id', $id)
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->toArray();

        $breakdown = [];
        for ($i = 5; $i >= 1; $i--) {
            $breakdown[$i] = $ratingBreakdown[$i] ?? 0;
        }

        $reviews = ProductReview::approved()
            ->with('user')
            ->where('product_id', $id)
            ->latest()
            ->paginate(10);

        $userReview = null;
        $userHasPurchased = false;

        if (Auth::check()) {
            $userReview = ProductReview::where('product_id', $id)
                ->where('user_id', Auth::id())
                ->first();

            $userHasPurchased = \App\Models\Order::where('user_id', Auth::id())
                ->where('status', 'delivered')
                ->whereHas('items', fn ($q) => $q->where('product_id', $id))
                ->exists();
        }

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
                    'average_rating' => (float) $p->average_rating,
                    'reviews_count' => $p->reviews_count,
                ];
            })
            ->toArray();

        return view('frontend.product-details', compact(
            'product',
            'relatedProducts',
            'reviews',
            'breakdown',
            'userReview',
            'userHasPurchased'
        ));
    }
}
