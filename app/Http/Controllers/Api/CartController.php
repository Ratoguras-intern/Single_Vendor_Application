<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct(
        private readonly CartService $cartService,
    ) {}

    public function index(): JsonResponse
    {
        $userId = Auth::id();
        $items = $this->cartService->getCartItems($userId);
        $totals = $this->cartService->calculateTotals($items);

        return response()->json([
            'items' => $items,
            ...$totals,
        ]);
    }

    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'nullable|integer|min:1|max:99',
        ]);

        $items = $this->cartService->addItem(
            Auth::id(),
            $request->product_id,
            $request->integer('quantity', 1)
        );
        $totals = $this->cartService->calculateTotals($items);

        return response()->json([
            'items' => $items,
            ...$totals,
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        $items = $this->cartService->updateQuantity(
            Auth::id(),
            $request->product_id,
            $request->quantity
        );
        $totals = $this->cartService->calculateTotals($items);

        return response()->json([
            'items' => $items,
            ...$totals,
        ]);
    }

    public function remove(int $productId): JsonResponse
    {
        $items = $this->cartService->removeItem(Auth::id(), $productId);
        $totals = $this->cartService->calculateTotals($items);

        return response()->json([
            'items' => $items,
            ...$totals,
        ]);
    }

    public function clear(): JsonResponse
    {
        $this->cartService->clearCart(Auth::id());

        return response()->json([
            'items' => [],
            'subtotal' => 0,
            'shipping' => 0,
            'tax' => 0,
            'total' => 0,
        ]);
    }
}
