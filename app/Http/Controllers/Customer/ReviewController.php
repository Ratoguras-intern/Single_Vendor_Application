<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'comment' => 'required|string|max:2000',
        ]);

        $user = Auth::user();
        $productId = $data['product_id'];

        $existing = ProductReview::where('product_id', $productId)
            ->where('user_id', $user->id)
            ->first();

        if ($existing) {
            return back()->withErrors(['product_id' => 'You have already reviewed this product.']);
        }

        $hasDeliveredOrder = Order::where('user_id', $user->id)
            ->where('status', 'delivered')
            ->whereHas('items', fn ($q) => $q->where('product_id', $productId))
            ->exists();

        if (! $hasDeliveredOrder) {
            return back()->withErrors(['product_id' => 'Only customers who have purchased this product can leave a review.']);
        }

        $deliveredOrder = Order::where('user_id', $user->id)
            ->where('status', 'delivered')
            ->whereHas('items', fn ($q) => $q->where('product_id', $productId))
            ->latest()
            ->first();

        ProductReview::create([
            'product_id' => $productId,
            'user_id' => $user->id,
            'order_id' => $deliveredOrder?->id,
            'rating' => $data['rating'],
            'title' => $data['title'] ?? null,
            'comment' => $data['comment'],
            'status' => 'pending',
            'is_verified_purchase' => true,
        ]);

        return back()->with('success', 'Your review has been submitted and is pending approval.');
    }

    public function update(Request $request, ProductReview $review)
    {
        if ($review->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'comment' => 'required|string|max:2000',
        ]);

        $review->update([
            'rating' => $request->input('rating'),
            'title' => $request->input('title'),
            'comment' => $request->input('comment'),
            'status' => 'pending',
        ]);

        return back()->with('success', 'Your review has been updated and is pending re-approval.');
    }

    public function destroy(ProductReview $review)
    {
        if ($review->user_id !== Auth::id()) {
            abort(403);
        }

        $productId = $review->product_id;
        $review->delete();
        ProductReview::updateProductRating($productId);

        return back()->with('success', 'Your review has been deleted.');
    }
}
