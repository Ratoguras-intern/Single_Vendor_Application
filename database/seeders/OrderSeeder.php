<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Product;
use App\Models\User;
use App\Support\OrderStatuses;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'customer')->pluck('id')->all();
        $products = Product::all();

        if (empty($customers) || $products->isEmpty()) {
            return;
        }

        $weights = [
            'delivered' => 45,
            'shipped' => 15,
            'packed' => 12,
            'pending' => 12,
            'cancelled' => 16,
        ];

        for ($i = 0; $i < 60; $i++) {
            $itemsCount = rand(1, 3);
            $subtotal = 0;
            $items = [];

            for ($j = 0; $j < $itemsCount; $j++) {
                $product = $products->random();
                $quantity = rand(1, 3);
                $price = (float) ($product->discount_price ?? $product->price);
                $subtotal += $price * $quantity;
                $items[] = ['product' => $product, 'quantity' => $quantity, 'price' => $price];
            }

            $tax = round($subtotal * 0.13, 2);
            $shipping = $subtotal >= 100 ? 0 : 9.99;
            $discount = rand(0, 1) ? round($subtotal * 0.10, 2) : 0;
            $total = round($subtotal + $tax + $shipping - $discount, 2);

            $status = $this->weightedPick($weights);

            $paymentStatus = match ($status) {
                'cancelled' => 'failed',
                'delivered', 'shipped' => 'paid',
                default => collect(['pending', 'paid'])->random(),
            };

            $createdAt = now()
                ->subDays(rand(0, 365))
                ->subHours(rand(0, 23))
                ->subMinutes(rand(0, 59));

            $order = Order::create([
                'user_id' => $customers[array_rand($customers)],
                'order_number' => 'ORD-'.strtoupper(Str::random(8)),
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping' => $shipping,
                'discount' => $discount,
                'total_amount' => $total,
                'status' => $status,
                'shipping_address' => fake()->address(),
                'billing_address' => fake()->address(),
                'phone' => fake()->phoneNumber(),
                'payment_method' => collect(['cod', 'card', 'paypal'])->random(),
                'payment_status' => $paymentStatus,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            $this->seedStatusHistory($order, $status, $createdAt);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }
        }
    }

    private function seedStatusHistory(Order $order, string $status, $createdAt): void
    {
        if ($status === OrderStatuses::CANCELLED) {
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => OrderStatuses::PENDING,
                'comment' => 'Order placed',
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => OrderStatuses::CANCELLED,
                'comment' => 'Cancelled by customer',
                'created_at' => $createdAt->copy()->addMinutes(rand(30, 1440)),
                'updated_at' => $createdAt->copy()->addMinutes(rand(30, 1440)),
            ]);

            return;
        }

        $stamps = [$createdAt];
        foreach (array_slice(OrderStatuses::FLOW, 0, OrderStatuses::step($status)) as $prev) {
            $stamps[] = $stamps[count($stamps) - 1]->copy()->addHours(rand(6, 72));
        }

        foreach (array_slice(OrderStatuses::FLOW, 0, OrderStatuses::step($status) + 1) as $i => $st) {
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => $st,
                'created_at' => $stamps[$i],
                'updated_at' => $stamps[$i],
            ]);
        }
    }

    private function weightedPick(array $weights): string
    {
        $total = array_sum($weights);
        $rand = rand(1, $total);
        $cumulative = 0;

        foreach ($weights as $status => $weight) {
            $cumulative += $weight;
            if ($rand <= $cumulative) {
                return $status;
            }
        }

        return 'pending';
    }
}
