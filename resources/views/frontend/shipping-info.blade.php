@extends('layouts.frontend')

@section('title', $seoTitle . ' - ' . site_name())

@include('frontend.partials.seo-meta', [
    'title' => $seoTitle,
    'description' => $seoDescription,
    'canonical' => $page->canonical_url,
    'image' => $page->og_image_url,
])

@section('content')
@php
    $fmt = fn ($amount) => app(\App\Services\CurrencyService::class)->format((float) $amount, $currency);
@endphp

@include('frontend.partials.page-hero', [
    'title' => $page->title,
    'description' => $page->short_description,
    'eyebrow' => $page->subtitle ?: 'Shipping & Delivery',
    'backgroundImage' => $page->featured_image_url ?? asset('images/pages/shipping-warehouse.jpg'),
])

@if ($processSteps->isNotEmpty())
    <section class="py-12 sm:py-16 bg-white dark:bg-secondary-900" aria-label="Delivery process">
        <div class="section">
            <div class="text-center mb-10">
                <span class="section-eyebrow">How it works</span>
                <h2 class="section-heading !text-2xl sm:!text-3xl">From Order to Doorstep</h2>
            </div>
            @include('frontend.partials.process-steps', ['steps' => $processSteps])
        </div>
    </section>
@endif

<section class="py-12 sm:py-16 lg:py-20 bg-secondary-50 dark:bg-secondary-950">
    <div class="section space-y-14 sm:space-y-16">

        {{-- Shipping Methods --}}
        <div>
            <div class="mb-6 sm:mb-8">
                <h2 class="text-xl sm:text-2xl font-bold text-secondary-900 dark:text-white mb-1.5">Shipping Methods</h2>
                <p class="text-sm text-secondary-500 dark:text-secondary-400 max-w-2xl">Choose the delivery option that suits you best at checkout.</p>
            </div>

            @if ($methods->isNotEmpty())
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                    @foreach ($methods as $method)
                        <div class="card-hover flex flex-col h-full">
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <h3 class="font-semibold text-secondary-900 dark:text-white">{{ $method->name }}</h3>
                                @if ($method->price > 0)
                                    <span class="badge-primary shrink-0">{{ $fmt($method->price) }}</span>
                                @else
                                    <span class="badge-success shrink-0">Free</span>
                                @endif
                            </div>
                            @if ($method->description)
                                <p class="text-sm text-secondary-500 dark:text-secondary-400 leading-relaxed mb-4">{{ $method->description }}</p>
                            @endif
                            <div class="mt-auto space-y-2 pt-2 border-t border-secondary-100 dark:border-secondary-800 text-xs text-secondary-500 dark:text-secondary-400">
                                @if ($method->delivery_estimate)
                                    <div class="flex items-center gap-2">
                                        <svg class="h-3.5 w-3.5 shrink-0 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                        {{ $method->delivery_estimate }}
                                    </div>
                                @endif
                                @if ($method->availability)
                                    <div class="flex items-center gap-2">
                                        <svg class="h-3.5 w-3.5 shrink-0 text-emerald-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                        {{ $method->availability }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                @include('frontend.partials.empty-state', [
                    'title' => 'Shipping options coming soon',
                    'description' => 'We are finalising our delivery options. Please check back shortly.',
                    'icon' => 'inbox',
                    'actionUrl' => '/contact-us',
                    'actionLabel' => 'Contact Support',
                ])
            @endif
        </div>

        {{-- Delivery in pictures --}}
        <div class="grid sm:grid-cols-2 gap-4 sm:gap-5 max-w-4xl">
            <figure class="card !p-0 overflow-hidden group">
                <div class="overflow-hidden">
                    <img src="{{ asset('images/pages/delivery-van.jpg') }}" alt="Delivery van on its way to a customer" loading="lazy" class="w-full h-44 object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
                <figcaption class="flex items-center gap-2 p-4 text-sm font-medium text-secondary-700 dark:text-secondary-300">
                    <svg class="h-4 w-4 text-primary-500 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                    {{ \App\Models\Setting::get('shipping.caption_dispatch', 'Fast dispatch, tracked to your door') }}
                </figcaption>
            </figure>
            <figure class="card !p-0 overflow-hidden group">
                <div class="overflow-hidden">
                    <img src="{{ asset('images/pages/packaging-open.jpg') }}" alt="Order carefully packed in protective packaging" loading="lazy" class="w-full h-44 object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
                <figcaption class="flex items-center gap-2 p-4 text-sm font-medium text-secondary-700 dark:text-secondary-300">
                    <svg class="h-4 w-4 text-primary-500 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/></svg>
                    {{ \App\Models\Setting::get('shipping.caption_packing', 'Packed with care, every order') }}
                </figcaption>
            </figure>
        </div>

        {{-- Shipping Costs --}}
        <div>
            <div class="mb-6 sm:mb-8">
                <h2 class="text-xl sm:text-2xl font-bold text-secondary-900 dark:text-white mb-1.5">Shipping Costs</h2>
                <p class="text-sm text-secondary-500 dark:text-secondary-400 max-w-2xl">Transparent pricing with no surprises at checkout.</p>
            </div>
            <div class="card !p-0 overflow-hidden max-w-4xl">
                <table class="w-full text-sm">
                    <caption class="sr-only">Shipping costs by delivery method</caption>
                    <thead>
                        <tr class="border-b border-secondary-200 dark:border-secondary-700 bg-secondary-50 dark:bg-secondary-800/50">
                            <th scope="col" class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-secondary-500 dark:text-secondary-400">Method</th>
                            <th scope="col" class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-secondary-500 dark:text-secondary-400">Estimate</th>
                            <th scope="col" class="px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-secondary-500 dark:text-secondary-400">Cost</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-secondary-200 dark:divide-secondary-700/60">
                        @forelse ($methods as $method)
                            <tr class="hover:bg-secondary-50/70 dark:hover:bg-white/[0.02] transition-colors">
                                <td class="px-5 py-3.5 font-medium text-secondary-800 dark:text-secondary-200">{{ $method->name }}</td>
                                <td class="px-5 py-3.5 text-secondary-500 dark:text-secondary-400">{{ $method->delivery_estimate ?? '—' }}</td>
                                <td class="px-5 py-3.5 text-right font-medium text-secondary-800 dark:text-secondary-200">
                                    {{ $method->price > 0 ? $fmt($method->price) : 'Free' }}
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-5 py-8 text-center text-secondary-400">No shipping methods configured yet.</td></tr>
                        @endforelse
                        @if (filled($freeThreshold) && $methods->isNotEmpty())
                            <tr class="bg-primary-50/60 dark:bg-primary-950/20">
                                <td colspan="2" class="px-5 py-3.5 font-medium text-primary-700 dark:text-primary-300">
                                    Free standard shipping on orders over {{ $fmt($freeThreshold) }}
                                </td>
                                <td class="px-5 py-3.5 text-right font-semibold text-primary-600 dark:text-primary-400">{{ $fmt(0) }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Shipping Areas --}}
        @if ($areas->isNotEmpty())
            <div>
                <div class="mb-6 sm:mb-8">
                    <h2 class="text-xl sm:text-2xl font-bold text-secondary-900 dark:text-white mb-1.5">Shipping Areas</h2>
                    <p class="text-sm text-secondary-500 dark:text-secondary-400 max-w-2xl">Regions we currently deliver to.</p>
                </div>
                <div class="flex flex-wrap gap-2 max-w-4xl">
                    @foreach ($areas as $area)
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-secondary-200 dark:border-secondary-700 bg-white dark:bg-secondary-900 px-4 py-2 text-sm text-secondary-600 dark:text-secondary-300">
                            <svg class="h-3.5 w-3.5 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                            {{ trim($area) }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Important Information --}}
        @if ($importantNotes->isNotEmpty())
            <div>
                <div class="mb-6 sm:mb-8">
                    <h2 class="text-xl sm:text-2xl font-bold text-secondary-900 dark:text-white mb-1.5">Important Information</h2>
                    <p class="text-sm text-secondary-500 dark:text-secondary-400 max-w-2xl">A few things worth knowing before your order ships.</p>
                </div>
                <div class="grid sm:grid-cols-2 gap-4 max-w-4xl">
                    @foreach ($importantNotes as $note)
                        <div class="flex items-start gap-3 card !p-4">
                            <span class="mt-0.5 shrink-0 w-6 h-6 rounded-md bg-amber-50 dark:bg-amber-950/30 flex items-center justify-center">
                                <svg class="h-3.5 w-3.5 text-amber-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg>
                            </span>
                            <p class="text-sm text-secondary-600 dark:text-secondary-400 leading-relaxed">{{ trim($note) }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if ($page->content && trim(strip_tags($page->content)) !== '')
            <div class="max-w-4xl">
                <article class="card p-6 sm:p-8 lg:p-10">
                    <div class="page-content text-secondary-600 dark:text-secondary-400">{!! $content !!}</div>
                </article>
            </div>
        @endif
    </div>
</section>

@include('frontend.partials.cta-help')

@push('styles')
@include('frontend.partials.page-content-styles')
@endpush
@endsection
