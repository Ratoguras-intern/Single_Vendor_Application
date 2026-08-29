<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CartService
{
    public function getCartItems(int $userId): array
    {
        $cart = Cart::firstOrCreate(['user_id' => $userId]);

        $items = CartItem::where('cart_items.cart_id', $cart->id)
            ->join('products', 'cart_items.product_id', '=', 'products.id')
            ->leftJoin('product_images', function ($join) {
                $join->on('products.id', '=', 'product_images.product_id')
                     ->where('product_images.is_primary', '=', true);
            })
            ->select(
                'cart_items.id as cart_item_id',
                'cart_items.product_id as id',
                'cart_items.quantity',
                'cart_items.price',
                'products.name',
                'products.slug',
                'products.stock',
                'product_images.image as image_path'
            )
            ->where('products.status', true)
            ->get();

        return $items->map(function ($item) {
            $item->image = product_image_url($item->image_path);
            unset($item->image_path);
            return $item;
        })->toArray();
    }

    public function getCartCount(int $userId): int
    {
        $cart = Cart::where('user_id', $userId)->first();

        if (!$cart) {
            return 0;
        }

        return (int) CartItem::where('cart_id', $cart->id)->sum('quantity');
    }

    public function addItem(int $userId, int $productId, int $quantity = 1): array
    {
        $cart = Cart::firstOrCreate(['user_id' => $userId]);

        $product = Product::findOrFail($productId);
        $stock = (int) $product->stock;

        if ($stock <= 0) {
            return [
                'items' => $this->getCartItems($userId),
                'stock_reached' => true,
                'message' => "This product is currently out of stock.",
            ];
        }

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        $newQuantity = $cartItem
            ? min($cartItem->quantity + $quantity, $stock)
            : min($quantity, $stock);

        if ($cartItem) {
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'quantity' => $newQuantity,
                'price' => $product->discount_price ?? $product->price,
            ]);
        }

        return [
            'items' => $this->getCartItems($userId),
            'stock_reached' => $newQuantity >= $stock,
            'message' => $newQuantity >= $stock
                ? "Only {$stock} units of \"{$product->name}\" are available in stock."
                : null,
        ];
    }

    public function updateQuantity(int $userId, int $productId, int $quantity): array
    {
        $cart = Cart::where('user_id', $userId)->first();
        $stockReached = false;
        $message = null;

        if ($cart) {
            $product = Product::find($productId);

            if ($product) {
                $stock = (int) $product->stock;

                if ($stock <= 0) {
                    CartItem::where('cart_id', $cart->id)
                        ->where('product_id', $productId)
                        ->delete();
                    $stockReached = true;
                    $message = 'This product is currently out of stock.';
                } else {
                    $newQuantity = min(max(1, $quantity), $stock);

                    CartItem::where('cart_id', $cart->id)
                        ->where('product_id', $productId)
                        ->update(['quantity' => $newQuantity]);

                    if ($newQuantity >= $stock) {
                        $stockReached = true;
                        $message = "Only {$stock} units of \"{$product->name}\" are available in stock.";
                    }
                }
            }
        }

        return [
            'items' => $this->getCartItems($userId),
            'stock_reached' => $stockReached,
            'message' => $message,
        ];
    }

    public function removeItem(int $userId, int $productId): array
    {
        $cart = Cart::where('user_id', $userId)->first();

        if ($cart) {
            CartItem::where('cart_id', $cart->id)
                ->where('product_id', $productId)
                ->delete();
        }

        return $this->getCartItems($userId);
    }

    public function clearCart(int $userId): void
    {
        $cart = Cart::where('user_id', $userId)->first();

        if ($cart) {
            CartItem::where('cart_id', $cart->id)->delete();
        }
    }

    public function calculateTotals(array $items): array
    {
        $subtotal = collect($items)->sum(fn ($item) => $item['price'] * $item['quantity']);
        $shipping = $subtotal > 50 ? 0 : 9.99;
        $tax = $subtotal * 0.08;
        $total = $subtotal + $shipping + $tax;

        return compact('subtotal', 'shipping', 'tax', 'total');
    }
}
