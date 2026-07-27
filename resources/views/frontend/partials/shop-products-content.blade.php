@props([
    'products',
    'viewMode' => 'grid',
    'ajax' => false,
])

@if($products->count() > 0)
    <div :class="viewMode === 'list' ? 'grid grid-cols-1 gap-4' : 'grid gap-4 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 items-stretch'">
        @foreach($products as $product)
            @include('frontend.partials.product-card', ['product' => $product])
        @endforeach
    </div>

    <div class="mt-10">
        @include('frontend.partials.category-pagination', ['paginator' => $products, 'ajax' => $ajax])
    </div>
@else
    <div class="text-center py-20">
        <div class="w-20 h-20 rounded-full bg-secondary-100 dark:bg-white/5 flex items-center justify-center mx-auto mb-6">
            <svg class="h-10 w-10 text-secondary-300 dark:text-secondary-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
        </div>
        <h3 class="text-xl font-bold text-secondary-900 dark:text-white mb-2">No products found</h3>
        <p class="text-secondary-500 dark:text-secondary-400 mb-8 max-w-md mx-auto">We couldn't find any products matching your criteria. Try adjusting your filters or browse our categories.</p>
        <div class="flex items-center justify-center gap-3">
            @if(collect(request()->query())->except(['page', 'sort', 'view'])->filter()->count() > 0)
                <a href="{{ route('frontend.shop') }}" class="btn-primary">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                    Clear Filters
                </a>
            @endif
        </div>
    </div>
@endif
