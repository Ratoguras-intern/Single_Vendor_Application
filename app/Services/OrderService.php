<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    public function createOrder(User $user, array $validated): Order
    {
        $items = json_decode($validated['items'], true);

        return $this->buildOrder($user, $validated, $items);
    }

    public function createOrderFromCart(User $user, array $validated, array $cartItems): Order
    {
        $items = array_map(fn ($item) => [
            'id' => $item['id'],
            'quantity' => $item['quantity'],
            'price' => $item['price'],
        ], $cartItems);

        return $this->buildOrder($user, $validated, $items);
    }

    private function buildOrder(User $user, array $validated, array $items): Order
    {
        $shippingAddress = collect([
            $validated['first_name'] . ' ' . $validated['last_name'],
            $validated['address'],
            $validated['city'] . ', ' . $validated['state'] . ' ' . $validated['zip'],
            $validated['email'],
        ])->implode("\n");

        $subtotal = collect($items)->sum(fn ($item) => $item['price'] * $item['quantity']);
        $shipping = $subtotal > 50 ? 0 : 9.99;
        $tax = $subtotal * 0.08;
        $discount = 0;
        $total = $subtotal + $shipping + $tax - $discount;

        $order = DB::transaction(function () use ($user, $validated, $items, $shippingAddress, $subtotal, $tax, $shipping, $discount, $total) {
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping' => $shipping,
                'discount' => $discount,
                'total_amount' => $total,
                'status' => 'pending',
                'shipping_address' => $shippingAddress,
                'phone' => $validated['phone'],
                'payment_method' => $validated['payment_method'],
                'payment_status' => $validated['payment_method'] === 'cod' ? 'cod' : 'pending',
            ]);

            foreach ($items as $item) {
                $order->items()->create([
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            $order->payments()->create([
                'payment_method' => $validated['payment_method'],
                'status' => $validated['payment_method'] === 'cod' ? 'cod' : 'pending',
            ]);

            return $order;
        });

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new NewOrderNotification($order));
        }

        return $order;
    }
}
