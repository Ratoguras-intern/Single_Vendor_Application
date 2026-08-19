<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\ProductReview;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    private array $titles = [
        'Excellent quality', 'Great value for money', 'Highly recommend',
        'Decent product', 'Exceeded expectations', 'Good but not perfect',
        'Amazing purchase', 'Worth every penny', 'Very satisfied',
        'Love it', 'Perfect fit', 'Fast delivery',
        'Beautiful design', 'Sturdy and well-made', 'Just what I needed',
        'Impressive quality', 'Better than expected', 'Solid build',
        'Fantastic product', 'Would buy again', 'Top notch',
        'Very happy with this', 'Superb craftsmanship', 'Five stars',
    ];

    private array $comments = [
        'The product quality is outstanding. Arrived well-packaged and exactly as described.',
        'Very happy with this purchase. The materials feel premium and it works perfectly.',
        'Good value for the price. Shipping was fast and the product met my expectations.',
        'This is exactly what I was looking for. Highly recommend to anyone considering it.',
        'Solid build quality and beautiful design. Very pleased with my order.',
        'Works great out of the box. No issues at all. Would definitely recommend.',
        'The color and finish are slightly different from the photos, but overall a good product.',
        'Arrived on time and in perfect condition. The quality is impressive for this price range.',
        'I have been using this for a few weeks now and it holds up really well.',
        'Excellent customer service and a great product. What more could you ask for?',
        'A bit heavier than I expected, but the quality makes up for it.',
        'This product exceeded all my expectations. Will definitely be ordering more.',
        'Great everyday product. Durable and looks good too.',
        'The sizing was perfect and the material quality is top-notch.',
        'Very functional and well-designed. Minor packaging issue but product was fine.',
        'Fast shipping and the product is exactly as advertised. Very satisfied.',
        'I bought this as a gift and the recipient loved it. Great choice.',
        'Solid construction and premium feel. You get what you pay for.',
        'Good product overall. Had a small issue with the first one but replacement was quick.',
        'This is my second purchase of this item. Just as good as the first time.',
        'The design is sleek and modern. Fits perfectly with my setup.',
        'High quality materials and excellent attention to detail.',
        'Very comfortable and practical. Use it every day without any issues.',
        'Impressed with the build quality. This should last a long time.',
    ];

    public function run(): void
    {
        ProductReview::query()->delete();

        $deliveredOrders = Order::where('status', 'delivered')
            ->with('items')
            ->get();

        $reviewPairs = [];

        foreach ($deliveredOrders as $order) {
            foreach ($order->items as $item) {
                $key = "{$order->user_id}-{$item->product_id}";
                if (! isset($reviewPairs[$key])) {
                    $reviewPairs[$key] = [
                        'user_id' => $order->user_id,
                        'product_id' => $item->product_id,
                        'order_id' => $order->id,
                    ];
                }
            }
        }

        $ratingWeights = [5, 5, 5, 4, 4, 4, 3, 3, 2, 1];

        $created = 0;
        foreach ($reviewPairs as $pair) {
            $rating = $ratingWeights[array_rand($ratingWeights)];

            ProductReview::create([
                'product_id' => $pair['product_id'],
                'user_id' => $pair['user_id'],
                'order_id' => $pair['order_id'],
                'rating' => $rating,
                'title' => $this->titles[array_rand($this->titles)],
                'comment' => $this->comments[array_rand($this->comments)],
                'status' => 'approved',
                'is_verified_purchase' => true,
            ]);

            $created++;
        }

        $products = \App\Models\Product::pluck('id');
        foreach ($products as $productId) {
            \App\Models\ProductReview::updateProductRating($productId);
        }

        $this->command->info("Created {$created} dummy reviews and updated product ratings.");
    }
}
