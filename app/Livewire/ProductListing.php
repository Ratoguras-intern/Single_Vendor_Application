<?php

namespace App\Livewire;

use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ProductListing extends Component
{
    use WithPagination;

    #[Url]
    public string $sort = 'latest';

    #[Url]
    public string $search = '';

    #[Url]
    public $brand = [];

    #[Url(as: 'min_price')]
    public $minPrice = null;

    #[Url(as: 'max_price')]
    public $maxPrice = null;

    #[Url(as: 'in_stock')]
    public bool $inStock = false;

    #[Url(as: 'on_sale')]
    public bool $onSale = false;

    #[Url(as: 'featured')]
    public bool $featured = false;

    #[Url(as: 'new_arrivals')]
    public bool $newArrivals = false;

    public int $perPage = 24;

    public ?string $categorySlug = null;

    public function mount(?string $categorySlug = null): void
    {
        $this->categorySlug = $categorySlug;
        $this->brand = $this->normalizeBrand($this->brand);
    }

    public function setSort(string $sort): void
    {
        $this->sort = $sort;
    }

    public function updatedBrand($value): void
    {
        $this->brand = $this->normalizeBrand($value);
        $this->resetPage();
    }

    protected function normalizeBrand($value): array
    {
        $brands = is_array($value) ? $value : [$value];

        return array_values(array_filter(array_map('strval', $brands)));
    }

    public function updatedSort(): void
    {
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedMinPrice(): void
    {
        $this->resetPage();
    }

    public function updatedMaxPrice(): void
    {
        $this->resetPage();
    }

    public function updatedInStock(): void
    {
        $this->resetPage();
    }

    public function updatedOnSale(): void
    {
        $this->resetPage();
    }

    public function updatedFeatured(): void
    {
        $this->resetPage();
    }

    public function updatedNewArrivals(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['brand', 'minPrice', 'maxPrice', 'inStock', 'onSale', 'featured', 'newArrivals']);
        $this->resetPage();
    }

    public function removeFilter(string $key): void
    {
        match ($key) {
            'brand' => $this->brand = [],
            'minPrice' => $this->minPrice = null,
            'maxPrice' => $this->maxPrice = null,
            'inStock' => $this->inStock = false,
            'onSale' => $this->onSale = false,
            'featured' => $this->featured = false,
            'newArrivals' => $this->newArrivals = false,
            default => null,
        };
    }

    public function hasActiveFilters(): bool
    {
        return ! empty($this->brand)
            || $this->inStock
            || $this->onSale
            || $this->featured
            || $this->newArrivals
            || ($this->minPrice !== null && $this->minPrice !== '')
            || ($this->maxPrice !== null && $this->maxPrice !== '');
    }

    public function render()
    {
        $category = null;
        $children = collect();
        $breadcrumbs = [];
        $recommendations = ['trending' => [], 'new_arrivals' => []];

        $ids = null;
        if ($this->categorySlug) {
            $category = Category::where('slug', $this->categorySlug)->active()->first();

            if ($category) {
                $ids = $category->children()->active()->pluck('id')->push($category->id)->all();
                $children = $category->children()
                    ->active()
                    ->ordered()
                    ->withCount(['products' => fn ($q) => $q->active()])
                    ->get();
                $breadcrumbs = $this->buildBreadcrumbs($category);
                $recommendations = $this->getRecommendations($ids);
            }
        }

        $query = Product::with(['images', 'category', 'brand'])->active();
        if ($ids) {
            $query->whereIn('category_id', $ids);
        }
        $query = $this->applyFilters($query);
        $query = $this->applySort($query);

        $products = $query->paginate($this->perPage);
        $products->setCollection(collect(
            $products->getCollection()->map(fn ($p) => $this->mapProduct($p))->toArray()
        ));

        $brands = $ids
            ? Brand::whereHas('products', fn ($q) => $q->active()->whereIn('category_id', $ids))
                ->withCount(['products' => fn ($q) => $q->active()->whereIn('category_id', $ids)])
                ->orderBy('name')
                ->get()
            : Brand::where('status', true)
                ->withCount(['products' => fn ($q) => $q->active()])
                ->orderBy('name')
                ->get();

        $priceRange = Product::active()
            ->when($ids, fn ($q) => $q->whereIn('category_id', $ids))
            ->selectRaw('MIN(COALESCE(discount_price, price)) as min_price, MAX(price) as max_price')
            ->first();

        $sidebarBanners = Banner::forTargetPage($this->categorySlug ? 'category' : 'shop')
            ->active()
            ->ordered()
            ->get();

        $viewStorageKey = $this->categorySlug ? 'categoryView' : 'shopView';

        return view('livewire.product-listing', compact(
            'products', 'brands', 'priceRange', 'sidebarBanners', 'viewStorageKey',
            'category', 'children', 'breadcrumbs', 'recommendations'
        ));
    }

    protected function buildBreadcrumbs(Category $category): array
    {
        $crumbs = [['name' => 'Home', 'url' => route('frontend.home')]];

        if ($category->parent) {
            $crumbs[] = [
                'name' => $category->parent->name,
                'url' => route('frontend.category', $category->parent->slug),
            ];
        }

        $crumbs[] = ['name' => $category->name, 'url' => null];

        return $crumbs;
    }

    protected function getRecommendations(array $ids): array
    {
        $limit = 8;

        $trending = Product::with(['images', 'brand'])
            ->active()
            ->whereIn('category_id', $ids)
            ->withSum('orderItems', 'quantity')
            ->orderByDesc('order_items_sum_quantity')
            ->limit($limit)
            ->get()
            ->map(fn ($p) => $this->mapProduct($p))
            ->filter(fn ($p) => $p['id'])
            ->values()
            ->toArray();

        $newArrivals = Product::with(['images', 'brand'])
            ->active()
            ->whereIn('category_id', $ids)
            ->latest()
            ->limit($limit)
            ->get()
            ->map(fn ($p) => $this->mapProduct($p))
            ->values()
            ->toArray();

        return [
            'trending' => $trending,
            'new_arrivals' => $newArrivals,
        ];
    }

    protected function applyFilters(Builder $query): Builder
    {
        if (! empty($this->brand)) {
            $query->whereHas('brand', fn ($q) => $q->whereIn('slug', $this->brand));
        }

        if ($this->minPrice !== null && $this->minPrice !== '') {
            $query->where(fn ($q) => $q
                ->where('price', '>=', (float) $this->minPrice)
                ->orWhere('discount_price', '>=', (float) $this->minPrice));
        }

        if ($this->maxPrice !== null && $this->maxPrice !== '') {
            $query->where('price', '<=', (float) $this->maxPrice);
        }

        if ($this->inStock) {
            $query->where('stock', '>', 0);
        }

        if ($this->onSale) {
            $query->whereNotNull('discount_price')->where('discount_price', '>', 0);
        }

        if ($this->featured) {
            $query->where('is_featured', true);
        }

        if ($this->newArrivals) {
            $query->where('is_new_arrival', true);
        }

        if ($this->search !== '') {
            $query->where(fn ($q) => $q
                ->where('name', 'like', "%{$this->search}%")
                ->orWhere('description', 'like', "%{$this->search}%"));
        }

        return $query;
    }

    protected function applySort(Builder $query): Builder
    {
        return match ($this->sort) {
            'newest' => $query->latest(),
            'oldest' => $query->oldest(),
            'price_asc' => $query->orderBy('discount_price')->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'name_asc' => $query->orderBy('name'),
            'name_desc' => $query->orderByDesc('name'),
            'discount' => $query->orderBy('discount_price', 'asc')->orderBy('price', 'desc'),
            'best_selling' => $query->withSum('orderItems', 'quantity')->orderByDesc('order_items_sum_quantity'),
            default => $query->latest(),
        };
    }

    protected function mapProduct(Product $product): array
    {
        $image = $product->primaryImage();
        $effectivePrice = $product->discount_price ?? $product->price;
        $discountPct = $product->discount_price
            ? round((($product->price - $product->discount_price) / $product->price) * 100)
            : 0;

        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'price' => (float) $effectivePrice,
            'original_price' => $product->discount_price ? (float) $product->price : null,
            'image' => $image?->image
                ? Storage::disk('public')->url($image->image)
                : asset('frontend-assets/images/no-image.jpg'),
            'description' => $product->description,
            'brand' => $product->brand?->name,
            'stock' => $product->stock,
            'discount_percentage' => $discountPct > 0 ? $discountPct : null,
            'is_new' => $product->created_at && $product->created_at->diffInDays(now()) <= 14,
            'is_best_seller' => $product->is_best_seller,
            'is_flash_sale' => $product->is_flash_sale,
            'is_limited_edition' => $product->is_limited_edition,
            'is_featured' => $product->is_featured,
            'is_trending' => $product->is_trending,
            'is_new_arrival' => $product->is_new_arrival,
        ];
    }
}
