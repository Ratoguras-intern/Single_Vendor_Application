<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $query = $request->input('q', '');

        if (strlen($query) < 2) {
            return response()->json([
                'products'       => [],
                'categories'     => [],
                'recent'         => $this->recentSearches($request),
                'popular'        => $this->popularSearches(),
            ]);
        }

        $products = Product::query()
            ->where('status', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->with(['images', 'category', 'brand'])
            ->limit(8)
            ->get()
            ->map(function ($product) {
                $image = $product->primaryImage();
                return [
                    'id'       => $product->id,
                    'name'     => $product->name,
                    'price'    => $product->discount_price ?? $product->price,
                    'original_price' => $product->price,
                    'image'    => $image ? asset('storage/' . $image->image) : asset('images/placeholder.jpg'),
                    'category' => $product->category?->name,
                    'brand'    => $product->brand?->name,
                ];
            });

        $categories = \App\Models\Category::query()
            ->where('status', true)
            ->where('name', 'like', "%{$query}%")
            ->limit(4)
            ->get(['id', 'name', 'slug'])
            ->map(fn ($c) => ['id' => $c->id, 'name' => $c->name, 'url' => route('frontend.shop') . '?category=' . $c->slug]);

        $this->recordSearch($request, $query);

        return response()->json([
            'products'   => $products,
            'categories' => $categories,
            'recent'     => $this->recentSearches($request),
            'popular'    => $this->popularSearches(),
        ]);
    }

    private function recordSearch(Request $request, string $query): void
    {
        $session_id = $request->session()->getId();
        $key = 'recent_searches_' . $session_id;
        $recent = session()->get($key, []);

        $recent = array_filter($recent, fn ($s) => strtolower($s) !== strtolower($query));
        array_unshift($recent, $query);
        $recent = array_slice($recent, 0, 8);

        session()->put($key, array_values($recent));
    }

    private function recentSearches(Request $request): array
    {
        $session_id = $request->session()->getId();
        return session()->get('recent_searches_' . $session_id, []);
    }

    private function popularSearches(): array
    {
        return \App\Models\Category::query()
            ->where('status', true)
            ->withCount(['products' => fn ($q) => $q->where('status', true)])
            ->orderByDesc('products_count')
            ->orderBy('name')
            ->limit(6)
            ->pluck('name')
            ->all();
    }
}
