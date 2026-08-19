<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductReview::with(['product', 'user', 'order']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('product', fn ($q) => $q->where('name', 'like', "%{$search}%"))
                ->orWhereHas('user', fn ($q) => $q->where('name', 'like', "%{$search}%"));
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->input('product_id'));
        }

        if ($request->filled('rating')) {
            $query->where('rating', $request->input('rating'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('verified')) {
            $query->where('is_verified_purchase', $request->input('verified') === '1');
        }

        $reviews = $query->latest()->paginate(20)->withQueryString();
        $products = Product::orderBy('name')->pluck('name', 'id');

        return view('admin.reviews.index', compact('reviews', 'products'));
    }

    public function show(ProductReview $review)
    {
        $review->load(['product', 'user', 'order']);

        return view('admin.reviews.show', ['review' => $review]);
    }

    public function approve(ProductReview $review)
    {
        $review->update(['status' => 'approved']);
        ProductReview::updateProductRating($review->product_id);

        return back()->with('success', 'Review approved.');
    }

    public function reject(ProductReview $review)
    {
        $review->update(['status' => 'rejected']);
        ProductReview::updateProductRating($review->product_id);

        return back()->with('success', 'Review rejected.');
    }

    public function destroy(ProductReview $review)
    {
        $productId = $review->product_id;
        $review->delete();
        ProductReview::updateProductRating($productId);

        return back()->with('success', 'Review deleted.');
    }
}
