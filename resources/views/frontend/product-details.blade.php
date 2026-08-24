@extends('layouts.frontend')

@section('title', $product['name'] . ' - ' . site_name())

@section('content')
<section class="py-8 sm:py-12">
    <div class="section">
        <div class="grid lg:grid-cols-2 gap-8 lg:gap-12 mb-10">
            <div class="space-y-4">
                <div class="rounded-card overflow-hidden bg-secondary-100 dark:bg-white/5">
                    <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="w-full h-auto max-h-[500px] object-cover" loading="eager" onerror="this.src='{{ asset('frontend-assets/images/no-image.jpg') }}'" />
                </div>
            </div>

            <div class="space-y-6">
                <h1 class="text-3xl lg:text-4xl font-bold text-secondary-900 dark:text-white">{{ $product['name'] }}</h1>

                <div class="flex items-center gap-2">
                    <div class="flex items-center gap-0.5">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($product['average_rating']))
                                <svg class="h-4 w-4 text-primary-500 dark:text-primary-400 fill-primary-500 dark:fill-primary-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            @elseif($i - $product['average_rating'] < 1 && $i - $product['average_rating'] > 0)
                                <svg class="h-4 w-4 text-primary-500 dark:text-primary-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <defs><linearGradient id="half-{{ $product['id'] }}"><stop offset="{{ ($product['average_rating'] - floor($product['average_rating'])) * 100 }}%" stop-color="currentColor"/><stop offset="{{ ($product['average_rating'] - floor($product['average_rating'])) * 100 }}%" stop-color="transparent"/></linearGradient></defs>
                                    <polygon fill="url(#half-{{ $product['id'] }})" stroke="currentColor" stroke-width="1" points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                </svg>
                            @else
                                <svg class="h-4 w-4 text-secondary-300 dark:text-secondary-600 fill-secondary-300 dark:fill-secondary-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            @endif
                        @endfor
                    </div>
                    <span class="text-sm text-secondary-500 dark:text-secondary-400">
                        @if($product['reviews_count'] > 0)
                            {{ number_format($product['average_rating'], 1) }} &bull; {{ $product['reviews_count'] }} {{ Str::plural('review', $product['reviews_count']) }}
                        @else
                            No reviews yet
                        @endif
                    </span>
                </div>

                <div class="flex items-center gap-3">
                    @if($product['original_price'])
                        <span class="text-3xl font-bold text-secondary-900 dark:text-white">{{ format_currency($product['price']) }}</span>
                        <span class="text-lg text-secondary-400 dark:text-secondary-500 line-through">{{ format_currency($product['original_price']) }}</span>
                    @else
                        <span class="text-3xl font-bold text-secondary-900 dark:text-white">{{ format_currency($product['price']) }}</span>
                    @endif
                </div>

                <p class="text-secondary-600 dark:text-secondary-400 leading-relaxed">{{ $product['description'] }}</p>

                <div class="divider"></div>

                <div class="space-y-4" x-data="{ quantity: 1 }">
                    <div>
                        <label class="label"><span data-i18n="Quantity" x-text="$store.i18n.t('Quantity')">Quantity</span></label>
                        <div class="flex items-center gap-3">
                            <div class="flex items-center border border-secondary-300 dark:border-secondary-600 rounded-input overflow-hidden">
                                <button x-on:click="quantity > 1 && quantity--" :disabled="quantity <= 1" class="min-h-[44px] min-w-[44px] inline-flex items-center justify-center text-secondary-600 dark:text-secondary-400 hover:bg-secondary-100 dark:hover:bg-white/5 transition-colors disabled:pointer-events-none disabled:opacity-50">
                                     <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14"/></svg>
                                 </button>
                                 <span x-text="quantity" class="px-4 py-2 min-w-[60px] text-center font-medium text-secondary-900 dark:text-white"></span>
                                 <button x-on:click="quantity++" class="min-h-[44px] min-w-[44px] inline-flex items-center justify-center text-secondary-600 dark:text-secondary-400 hover:bg-secondary-100 dark:hover:bg-white/5 transition-colors">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                 </button>
                            </div>
                            <div x-show="quantity > 1" x-transition class="rounded-lg bg-primary-50 dark:bg-primary-950/30 border border-primary-200 dark:border-primary-800 px-4 py-2">
                                @php $symbol = config('currency.supported.' . admin_currency() . '.symbol', '$'); @endphp
                                <span class="text-lg font-bold text-primary-700 dark:text-primary-300" x-text="'{{ $symbol }}' + ({{ $product['price'] }} * quantity).toFixed(2)"></span>
                                <span class="text-xs text-primary-500 dark:text-primary-400 ml-1">({{ format_currency($product['price']) }} each)</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <button x-on:click="$store.cart.addToCartQty({ id: {{ $product['id']}}, name: '{{ addslashes($product['name']) }}', price: {{ $product['price']}}, image: '{{ $product['image'] }}' }, quantity)" class="btn-primary flex-1">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                            <span data-i18n="Add to Cart" x-text="$store.i18n.t('Add to Cart')">{{ __('Add to Cart') }}</span>
                        </button>

                        <button x-on:click="$store.cart.buyNowQty({ id: {{ $product['id']}}, name: '{{ addslashes($product['name']) }}', price: {{ $product['price']}}, image: '{{ $product['image'] }}' }, quantity)" class="btn-outline flex-1">
                            <span data-i18n="Buy Now" x-text="$store.i18n.t('Buy Now')">{{ __('Buy Now') }}</span>
                        </button>
                    </div>

                    <div class="flex items-center gap-3">
                        <button x-on:click="$store.wishlist.toggle({{ $product['id'] }})" :class="$store.wishlist.has({{ $product['id'] }}) ? 'text-red-500' : ''" class="btn-ghost btn-sm">
                            <svg class="h-4 w-4" :class="$store.wishlist.has({{ $product['id'] }}) && 'fill-current'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" :stroke-width="$store.wishlist.has({{ $product['id'] }}) ? 0 : 2" :stroke="$store.wishlist.has({{ $product['id'] }}) ? 'none' : 'currentColor'"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/></svg>
                            <span x-text="$store.wishlist.has({{ $product['id'] }}) ? 'Remove from Wishlist' : 'Add to Wishlist'"></span>
                        </button>

                        <button class="btn-ghost btn-sm">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.933-2.185 2.25 2.25 0 0 0-3.933 2.185Z"/></svg>
                            Share
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @include('frontend.partials.features')

        {{-- Reviews Section --}}
        <div class="mt-12 sm:mt-16" x-data="{ activeTab: 'reviews' }">
            <div class="flex items-center gap-6 border-b border-secondary-200 dark:border-secondary-800 mb-8">
                <button x-on:click="activeTab = 'reviews'" :class="activeTab === 'reviews' ? 'border-primary-500 text-primary-600 dark:text-primary-400' : 'border-transparent text-secondary-500 hover:text-secondary-700 dark:hover:text-secondary-300'" class="pb-3 border-b-2 text-sm font-semibold transition-colors">
                    Reviews ({{ $product['reviews_count'] }})
                </button>
            </div>

            <div x-show="activeTab === 'reviews'" x-transition>
                <div class="grid lg:grid-cols-3 gap-8">
                    {{-- Rating Summary --}}
                    <div class="lg:col-span-1">
                        <div class="card p-6 text-center">
                            <div class="text-5xl font-bold text-secondary-900 dark:text-white mb-2">
                                @if($product['reviews_count'] > 0)
                                    {{ number_format($product['average_rating'], 1) }}
                                @else
                                    0.0
                                @endif
                            </div>
                            <div class="flex items-center justify-center gap-0.5 mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($product['average_rating']))
                                        <svg class="h-5 w-5 text-primary-500 fill-primary-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                    @elseif($i - $product['average_rating'] < 1 && $i - $product['average_rating'] > 0)
                                        <svg class="h-5 w-5 text-primary-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                            <defs><linearGradient id="half-summary"><stop offset="{{ ($product['average_rating'] - floor($product['average_rating'])) * 100 }}%" stop-color="currentColor"/><stop offset="{{ ($product['average_rating'] - floor($product['average_rating'])) * 100 }}%" stop-color="transparent"/></linearGradient></defs>
                                            <polygon fill="url(#half-summary)" stroke="currentColor" stroke-width="1" points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                        </svg>
                                    @else
                                        <svg class="h-5 w-5 text-secondary-300 dark:text-secondary-600 fill-secondary-300 dark:fill-secondary-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                    @endif
                                @endfor
                            </div>
                            <p class="text-sm text-secondary-500 dark:text-secondary-400 mb-6">
                                {{ $product['reviews_count'] }} {{ Str::plural('review', $product['reviews_count']) }}
                            </p>

                            @php $totalReviews = array_sum($breakdown); @endphp
                            @for($i = 5; $i >= 1; $i--)
                                @php
                                    $count = $breakdown[$i] ?? 0;
                                    $pct = $totalReviews > 0 ? round(($count / $totalReviews) * 100) : 0;
                                @endphp
                                <div class="flex items-center gap-2 mb-1.5">
                                    <span class="text-xs font-medium text-secondary-500 dark:text-secondary-400 w-3">{{ $i }}</span>
                                    <svg class="h-3 w-3 text-primary-500 fill-primary-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                    <div class="flex-1 h-2 rounded-full bg-secondary-200 dark:bg-secondary-700 overflow-hidden">
                                        <div class="h-full rounded-full bg-primary-500 transition-all" style="width: {{ $pct }}%"></div>
                                    </div>
                                    <span class="text-xs text-secondary-400 dark:text-secondary-500 w-8 text-right">{{ $count }}</span>
                                </div>
                            @endfor
                        </div>
                    </div>

                    {{-- Reviews List + Form --}}
                    <div class="lg:col-span-2 space-y-6">
                        {{-- Success/Error Messages --}}
                        @if(session('success'))
                            <div class="rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 px-4 py-3 text-sm text-green-700 dark:text-green-300">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 px-4 py-3 text-sm text-red-700 dark:text-red-300">
                                @foreach($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                        {{-- Write a Review Form --}}
                        @auth
                            @if($userHasPurchased && !$userReview)
                                <div class="card p-6">
                                    <h3 class="text-lg font-semibold text-secondary-900 dark:text-white mb-4">Write a Review</h3>
                                    <form method="POST" action="{{ route('customer.reviews.store') }}" x-data="reviewForm()" x-on:submit.prevent="if(rating === 0) { error = 'Please select a rating'; return; } error = ''; $el.submit();">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-medium text-secondary-700 dark:text-secondary-300 mb-2">Your Rating *</label>
                                                <div class="flex items-center gap-1" x-data="{ hover: 0 }">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <button type="button" x-on:click="rating = {{ $i }}" x-on:mouseenter="hover = {{ $i }}" x-on:mouseleave="hover = 0"
                                                            class="focus:outline-none transition-colors">
                                                            <svg class="h-7 w-7 transition-colors" :class="(hover >= {{ $i }} || rating >= {{ $i }}) ? 'text-primary-500 fill-primary-500' : 'text-secondary-300 dark:text-secondary-600 fill-secondary-300 dark:fill-secondary-600'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                                        </button>
                                                    @endfor
                                                    <input type="hidden" name="rating" :value="rating">
                                                    <span x-show="rating > 0" x-text="rating + '/5'" class="text-sm text-secondary-500 dark:text-secondary-400 ml-2"></span>
                                                </div>
                                                <p x-show="error" x-text="error" class="text-sm text-red-500 mt-1"></p>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-secondary-700 dark:text-secondary-300 mb-1">Review Title (optional)</label>
                                                <input type="text" name="title" maxlength="255" placeholder="Summarize your experience"
                                                    class="w-full rounded-lg border border-secondary-300 dark:border-secondary-600 bg-white dark:bg-secondary-800 px-4 py-2.5 text-sm text-secondary-900 dark:text-white focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-secondary-700 dark:text-secondary-300 mb-1">Your Review *</label>
                                                <textarea name="comment" rows="4" maxlength="2000" required placeholder="Share your experience with this product..."
                                                    class="w-full rounded-lg border border-secondary-300 dark:border-secondary-600 bg-white dark:bg-secondary-800 px-4 py-2.5 text-sm text-secondary-900 dark:text-white focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20 resize-none"></textarea>
                                            </div>
                                            <button type="submit" class="btn-primary">
                                                Submit Review
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @elseif($userReview)
                                <div class="card p-6">
                                    <div class="flex items-center justify-between mb-3">
                                        <h3 class="text-lg font-semibold text-secondary-900 dark:text-white">Your Review</h3>
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                            {{ $userReview->status === 'approved' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' :
                                               ($userReview->status === 'pending' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' :
                                               'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400') }}">
                                            {{ ucfirst($userReview->status) }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-0.5 mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="h-4 w-4 {{ $i <= $userReview->rating ? 'text-primary-500 fill-primary-500' : 'text-secondary-300 dark:text-secondary-600 fill-secondary-300 dark:fill-secondary-600' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                        @endfor
                                    </div>
                                    @if($userReview->title)
                                        <p class="font-medium text-secondary-900 dark:text-white mb-1">{{ $userReview->title }}</p>
                                    @endif
                                    <p class="text-sm text-secondary-600 dark:text-secondary-400 mb-4">{{ $userReview->comment }}</p>
                                    <div class="flex items-center gap-3">
                                        <button x-on:click="showEditForm = !showEditForm" class="text-sm font-medium text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300">
                                            Edit Review
                                        </button>
                                        <form method="POST" action="{{ route('customer.reviews.destroy', $userReview) }}" onsubmit="return confirm('Delete your review?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm font-medium text-red-500 hover:text-red-600">
                                                Delete
                                            </button>
                                        </form>
                                    </div>

                                    {{-- Edit Form --}}
                                    <div x-show="showEditForm" x-transition class="mt-4 pt-4 border-t border-secondary-200 dark:border-secondary-700">
                                        <form method="POST" action="{{ route('customer.reviews.update', $userReview) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="space-y-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-secondary-700 dark:text-secondary-300 mb-2">Your Rating *</label>
                                                    <div class="flex items-center gap-1" x-data="{ editHover: 0, editRating: {{ $userReview->rating }} }">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <button type="button" x-on:click="editRating = {{ $i }}" x-on:mouseenter="editHover = {{ $i }}" x-on:mouseleave="editHover = 0"
                                                                class="focus:outline-none transition-colors">
                                                                <svg class="h-6 w-6 transition-colors" :class="(editHover >= {{ $i }} || editRating >= {{ $i }}) ? 'text-primary-500 fill-primary-500' : 'text-secondary-300 dark:text-secondary-600 fill-secondary-300 dark:fill-secondary-600'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                                            </button>
                                                        @endfor
                                                        <input type="hidden" name="rating" :value="editRating">
                                                    </div>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-secondary-700 dark:text-secondary-300 mb-1">Review Title (optional)</label>
                                                    <input type="text" name="title" maxlength="255" value="{{ $userReview->title }}"
                                                        class="w-full rounded-lg border border-secondary-300 dark:border-secondary-600 bg-white dark:bg-secondary-800 px-4 py-2.5 text-sm text-secondary-900 dark:text-white focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-secondary-700 dark:text-secondary-300 mb-1">Your Review *</label>
                                                    <textarea name="comment" rows="3" maxlength="2000" required
                                                        class="w-full rounded-lg border border-secondary-300 dark:border-secondary-600 bg-white dark:bg-secondary-800 px-4 py-2.5 text-sm text-secondary-900 dark:text-white focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20 resize-none">{{ $userReview->comment }}</textarea>
                                                </div>
                                                <div class="flex items-center gap-3">
                                                    <button type="submit" class="btn-primary btn-sm">Update Review</button>
                                                    <button type="button" x-on:click="showEditForm = false" class="btn-outline btn-sm">Cancel</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @elseif(!$userHasPurchased)
                                <div class="card p-6 text-center">
                                    <div class="w-12 h-12 mx-auto rounded-full bg-secondary-100 dark:bg-secondary-800 flex items-center justify-center mb-3">
                                        <svg class="h-6 w-6 text-secondary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>
                                    </div>
                                    <p class="text-sm text-secondary-600 dark:text-secondary-400">
                                        Only customers who have purchased this product can leave a review.
                                    </p>
                                </div>
                            @endif
                        @else
                            <div class="card p-6 text-center">
                                <p class="text-sm text-secondary-600 dark:text-secondary-400">
                                    <a href="{{ route('login') }}" class="text-primary-600 dark:text-primary-400 hover:underline font-medium">Sign in</a>
                                    to leave a review for this product.
                                </p>
                            </div>
                        @endauth

                        {{-- Reviews List --}}
                        @forelse($reviews as $review)
                            <div class="card p-6">
                                <div class="flex items-start justify-between mb-3">
                                    <div>
                                        <div class="flex items-center gap-0.5 mb-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="h-4 w-4 {{ $i <= $review->rating ? 'text-primary-500 fill-primary-500' : 'text-secondary-300 dark:text-secondary-600 fill-secondary-300 dark:fill-secondary-600' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                            @endfor
                                        </div>
                                        @if($review->title)
                                            <h4 class="font-semibold text-secondary-900 dark:text-white">{{ $review->title }}</h4>
                                        @endif
                                    </div>
                                    @if($review->is_verified_purchase)
                                        <span class="inline-flex items-center gap-1 rounded-full bg-green-100 dark:bg-green-900/30 px-2.5 py-0.5 text-xs font-medium text-green-700 dark:text-green-400">
                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                            Verified Purchase
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm text-secondary-600 dark:text-secondary-400 leading-relaxed mb-3">{{ $review->comment }}</p>
                                <p class="text-xs text-secondary-400 dark:text-secondary-500">
                                    {{ $review->user->name }} &middot; {{ $review->created_at->format('F j, Y') }}
                                </p>
                            </div>
                        @empty
                            <div class="card p-8 text-center">
                                <div class="flex items-center justify-center gap-0.5 mb-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="h-6 w-6 text-secondary-300 dark:text-secondary-600 fill-secondary-300 dark:fill-secondary-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                    @endfor
                                </div>
                                <h3 class="text-lg font-semibold text-secondary-900 dark:text-white mb-1">No reviews yet</h3>
                                <p class="text-sm text-secondary-500 dark:text-secondary-400">
                                    Be the first verified customer to share your experience with this product.
                                </p>
                            </div>
                        @endforelse

                        @if($reviews->hasPages())
                            <div class="mt-6">
                                {{ $reviews->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-12 sm:mt-16">
            <div class="flex items-center justify-between mb-4">
                <h2 class="section-heading" data-i18n="Related Products" x-text="$store.i18n.t('Related Products')">{{ __('Related Products') }}</h2>
                <a href="{{ route('frontend.shop') }}" class="text-sm font-semibold text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 transition-colors"><span data-i18n="View All" x-text="$store.i18n.t('View All')">{{ __('View All') }}</span></a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                @foreach($relatedProducts as $related)
                    @include('frontend.partials.product-card', ['product' => $related])
                @endforeach
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script type="text/turbo-script">
function reviewForm() {
    return {
        rating: 0,
        error: '',
        showEditForm: false,
    };
}
</script>
@endpush
