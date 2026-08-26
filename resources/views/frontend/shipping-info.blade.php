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
    <section class="py-16 sm:py-20 bg-white dark:bg-secondary-900" aria-label="Delivery process">
        <div class="section">
            <div class="text-center mb-12 sm:mb-16">
                <span class="section-eyebrow">How it works</span>
                <h2 class="section-heading !text-2xl sm:!text-3xl">From Order to Doorstep</h2>
                <p class="mt-3 text-sm sm:text-base text-secondary-500 dark:text-secondary-400 max-w-xl mx-auto">Follow these simple steps from placing your order to receiving it at your doorstep.</p>
            </div>

            @php
                $stepIcons = [
                    'M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75-1.5a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z',
                    'M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9',
                    'M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12',
                    'M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z',
                ];
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
                @foreach ($processSteps->take(4) as $index => $step)
                    <div class="relative flex flex-col items-center text-center group">
                        <div class="relative z-10 mb-5">
                            <div class="w-20 h-20 rounded-full bg-primary-50 dark:bg-primary-950/30 border-2 border-primary-100 dark:border-primary-800/50 flex items-center justify-center group-hover:border-primary-300 dark:group-hover:border-primary-600 group-hover:bg-primary-100 dark:group-hover:bg-primary-900/40 transition-all duration-300">
                                <svg class="h-8 w-8 text-primary-600 dark:text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $stepIcons[$index] ?? $stepIcons[0] }}" />
                                </svg>
                            </div>
                            <span class="absolute -top-1.5 -right-1.5 w-6 h-6 rounded-full bg-primary-600 dark:bg-primary-500 text-white text-[11px] font-bold flex items-center justify-center shadow-sm">
                                {{ $index + 1 }}
                            </span>
                        </div>
                        <h3 class="font-semibold text-secondary-900 dark:text-white text-sm sm:text-base mb-2">{{ $step['title'] ?? $step->title ?? '' }}</h3>
                        @if ($step['description'] ?? $step->description ?? false)
                            <p class="text-sm text-secondary-600 dark:text-secondary-300 leading-relaxed max-w-[220px]">{{ $step['description'] ?? $step->description }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

{{-- ═══════════════════════════════════════════════════════════════
     DASHBOARD: 3-Card Layout
     Left: Tier Options  |  Center: Delivery Matrix  |  Right: Exceptions
     ═══════════════════════════════════════════════════════════════ --}}
<section class="py-16 sm:py-20 bg-secondary-50 dark:bg-[#111318]">
    <div class="section">
        <div class="text-center mb-10 sm:mb-14">
            <span class="section-eyebrow">Shipping Dashboard</span>
            <h2 class="section-heading !text-2xl sm:!text-3xl">Everything You Need to Know</h2>
            <p class="mt-3 text-sm sm:text-base text-secondary-500 dark:text-secondary-400 max-w-xl mx-auto">Rates, timelines, and policies — all in one place.</p>
        </div>

        <div class="grid lg:grid-cols-12 gap-5 lg:gap-6 items-start">

            {{-- ─── LEFT COLUMN: Tier Options ─── --}}
            <div class="lg:col-span-4 space-y-4">
                <div class="flex items-center gap-2.5 mb-1">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary-600 text-white">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                    </span>
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-secondary-500 dark:text-secondary-400">Tier Options</h3>
                </div>

                @if ($methods->isNotEmpty())
                    @foreach ($methods as $method)
                        @php
                            $isStandard = str_contains(strtolower($method->name), 'standard');
                            $isExpress  = str_contains(strtolower($method->name), 'express');
                            $isOvernight = str_contains(strtolower($method->name), 'overnight');
                        @endphp
                        <div @class([
                            'group relative rounded-2xl border p-5 transition-all duration-200 cursor-default',
                            'border-primary-500/30 bg-gradient-to-br from-primary-950/40 via-secondary-900/80 to-secondary-900/60 shadow-lg shadow-primary-900/10 hover:shadow-primary-900/20' => $isStandard,
                            'border-secondary-700/60 bg-secondary-800/50 hover:border-secondary-600 hover:bg-secondary-800/70' => $isExpress,
                            'border-secondary-700/60 bg-secondary-800/50 hover:border-secondary-600 hover:bg-secondary-800/70' => $isOvernight,
                            'border-secondary-700/60 bg-secondary-800/50 hover:border-secondary-600 hover:bg-secondary-800/70' => ! $isStandard && ! $isExpress && ! $isOvernight,
                        ])>
                            @if ($isStandard)
                                <div class="absolute inset-x-0 top-0 h-0.5 rounded-t-2xl bg-gradient-to-r from-primary-500 via-primary-400 to-emerald-400"></div>
                            @endif

                            <div class="flex items-start justify-between gap-3 mb-3">
                                <div class="flex items-center gap-3">
                                    <span @class([
                                        'flex h-10 w-10 shrink-0 items-center justify-center rounded-xl transition-colors',
                                        'bg-primary-600 text-white' => $isStandard,
                                        'bg-secondary-700 text-secondary-300 group-hover:bg-secondary-600' => ! $isStandard,
                                    ])>
                                        @if ($isStandard)
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                                        @elseif ($isExpress)
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                                        @elseif ($isOvernight)
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                                        @else
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/></svg>
                                        @endif
                                    </span>
                                    <div>
                                        <h4 class="font-semibold text-white text-sm">{{ $method->name }}</h4>
                                        @if ($method->delivery_estimate)
                                            <p class="text-xs text-secondary-400 mt-0.5">{{ $method->delivery_estimate }}</p>
                                        @endif
                                    </div>
                                </div>
                                @if ($isStandard)
                                    <span class="inline-flex items-center rounded-full bg-primary-500/15 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-primary-400 border border-primary-500/20">Best Value</span>
                                @endif
                            </div>

                            <div class="flex items-baseline gap-2 mb-3">
                                @if ($method->price > 0)
                                    <span class="text-xl font-bold text-white">{{ $fmt($method->price) }}</span>
                                    <span class="text-xs text-secondary-400">/ order</span>
                                @else
                                    <span class="text-xl font-bold text-emerald-400">Free</span>
                                @endif
                            </div>

                            @if ($method->description)
                                <p class="text-xs text-secondary-400 leading-relaxed mb-3">{{ $method->description }}</p>
                            @endif

                            <div class="flex flex-wrap gap-2">
                                @if ($method->delivery_estimate)
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-secondary-700/50 px-2.5 py-1 text-[11px] text-secondary-300">
                                        <svg class="h-3 w-3 text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                        {{ $method->delivery_estimate }}
                                    </span>
                                @endif
                                @if ($method->availability)
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-secondary-700/50 px-2.5 py-1 text-[11px] text-secondary-300">
                                        <svg class="h-3 w-3 text-emerald-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                        {{ $method->availability }}
                                    </span>
                                @endif
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-secondary-700/50 px-2.5 py-1 text-[11px] text-secondary-300">
                                    <svg class="h-3 w-3 text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                                    Live tracking
                                </span>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="rounded-2xl border border-secondary-700/60 bg-secondary-800/50 p-8 text-center">
                        <p class="text-sm text-secondary-400">Shipping options coming soon.</p>
                    </div>
                @endif
            </div>

            {{-- ─── CENTER COLUMN: Delivery Timeline Matrix ─── --}}
            <div class="lg:col-span-5">
                <div class="flex items-center gap-2.5 mb-1">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-600 text-white">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                    </span>
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-secondary-500 dark:text-secondary-400">Delivery Matrix</h3>
                </div>

                @php
                    $deliveryMatrix = [
                        ['region' => 'Continental US', 'standard' => '3–5 days', 'express' => '1–2 days', 'flag' => '🇺🇸'],
                        ['region' => 'Alaska & Hawaii', 'standard' => '5–7 days', 'express' => '2–3 days', 'flag' => '🇺🇸'],
                        ['region' => 'Canada',         'standard' => '5–10 days', 'express' => '3–5 days', 'flag' => '🇨🇦'],
                        ['region' => 'Europe',         'standard' => '7–14 days', 'express' => '5–7 days', 'flag' => '🇪🇺'],
                        ['region' => 'Asia Pacific',   'standard' => '10–14 days', 'express' => '5–10 days', 'flag' => '🌏'],
                    ];
                @endphp

                <div class="rounded-2xl border border-secondary-700/60 bg-secondary-800/50 overflow-hidden">
                    {{-- Matrix Header --}}
                    <div class="grid grid-cols-3 border-b border-secondary-700/60 bg-secondary-800/80">
                        <div class="px-5 py-3 text-[11px] font-semibold uppercase tracking-wider text-secondary-400">Region</div>
                        <div class="px-5 py-3 text-[11px] font-semibold uppercase tracking-wider text-secondary-400 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Standard
                        </div>
                        <div class="px-5 py-3 text-[11px] font-semibold uppercase tracking-wider text-secondary-400 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-primary-500"></span> Express
                        </div>
                    </div>

                    {{-- Matrix Rows --}}
                    @foreach ($deliveryMatrix as $rowIndex => $row)
                        <div @class([
                            'grid grid-cols-3 transition-colors hover:bg-secondary-700/30',
                            'border-b border-secondary-700/40' => ! $loop->last,
                        ])>
                            <div class="px-5 py-3.5 flex items-center gap-2.5">
                                <span class="text-base leading-none">{{ $row['flag'] }}</span>
                                <span class="text-sm font-medium text-white">{{ $row['region'] }}</span>
                            </div>
                            <div class="px-5 py-3.5 flex items-center">
                                <div class="flex items-center gap-2">
                                    <div class="h-1.5 rounded-full bg-emerald-500/20 w-16">
                                        <div class="h-1.5 rounded-full bg-emerald-500" style="width: {{ match(true) {
                                            str_contains($row['standard'], '3') => '30%',
                                            str_contains($row['standard'], '5') && str_contains($row['standard'], '7') => '50%',
                                            str_contains($row['standard'], '7') && str_contains($row['standard'], '14') => '75%',
                                            default => '90%',
                                        } }}"></div>
                                    </div>
                                    <span class="text-xs text-secondary-300">{{ $row['standard'] }}</span>
                                </div>
                            </div>
                            <div class="px-5 py-3.5 flex items-center">
                                <div class="flex items-center gap-2">
                                    <div class="h-1.5 rounded-full bg-primary-500/20 w-16">
                                        <div class="h-1.5 rounded-full bg-primary-500" style="width: {{ match(true) {
                                            str_contains($row['express'], '1') => '20%',
                                            str_contains($row['express'], '2') && str_contains($row['express'], '3') => '35%',
                                            str_contains($row['express'], '3') && str_contains($row['express'], '5') => '50%',
                                            str_contains($row['express'], '5') && str_contains($row['express'], '7') => '65%',
                                            default => '80%',
                                        } }}"></div>
                                    </div>
                                    <span class="text-xs text-secondary-300">{{ $row['express'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Free Shipping Banner --}}
                @if (filled($freeThreshold) && $methods->isNotEmpty())
                    <div class="mt-4 rounded-2xl border border-emerald-500/20 bg-emerald-950/20 p-4 flex items-center gap-3">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-500/15">
                            <svg class="h-5 w-5 text-emerald-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-emerald-400">Free standard shipping on orders over {{ $fmt($freeThreshold) }}</p>
                            <p class="text-xs text-secondary-400 mt-0.5">Applied automatically at checkout</p>
                        </div>
                    </div>
                @endif

                {{-- Shipping Areas --}}
                @if ($areas->isNotEmpty())
                    <div class="mt-5 rounded-2xl border border-secondary-700/60 bg-secondary-800/50 p-5">
                        <h4 class="text-xs font-semibold uppercase tracking-wider text-secondary-400 mb-3">Available Regions</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($areas as $area)
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-secondary-700/50 px-3 py-1.5 text-xs text-secondary-300 border border-secondary-600/30">
                                    <svg class="h-3 w-3 text-primary-400 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                                    {{ trim($area) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- ─── RIGHT COLUMN: Exception Handling Policies ─── --}}
            <div class="lg:col-span-3 space-y-4">
                <div class="flex items-center gap-2.5 mb-1">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-500 text-white">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/></svg>
                    </span>
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-secondary-500 dark:text-secondary-400">Exceptions</h3>
                </div>

                {{-- Processing --}}
                <div class="rounded-2xl border border-secondary-700/60 bg-secondary-800/50 p-5">
                    <div class="flex items-center gap-2.5 mb-3">
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-500/15">
                            <svg class="h-3.5 w-3.5 text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/></svg>
                        </span>
                        <h4 class="text-sm font-semibold text-white">Order Processing</h4>
                    </div>
                    <p class="text-xs text-secondary-400 leading-relaxed">All orders are processed within 1–2 business days. Weekend and holiday orders ship the next business day.</p>
                </div>

                {{-- Weather & Delays --}}
                <div class="rounded-2xl border border-secondary-700/60 bg-secondary-800/50 p-5">
                    <div class="flex items-center gap-2.5 mb-3">
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-500/15">
                            <svg class="h-3.5 w-3.5 text-amber-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                        </span>
                        <h4 class="text-sm font-semibold text-white">Delays & Exceptions</h4>
                    </div>
                    <ul class="space-y-2">
                        @php
                            $exceptions = [
                                'Severe weather conditions',
                                'Natural disasters',
                                'Carrier disruptions',
                                'Holiday peak seasons',
                                'Incorrect addresses',
                            ];
                        @endphp
                        @foreach ($exceptions as $item)
                            <li class="flex items-center gap-2 text-xs text-secondary-400">
                                <span class="w-1 h-1 rounded-full bg-amber-500/60 shrink-0"></span>
                                {{ $item }}
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Lost / Damaged --}}
                <div class="rounded-2xl border border-red-500/20 bg-red-950/15 p-5">
                    <div class="flex items-center gap-2.5 mb-3">
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-red-500/15">
                            <svg class="h-3.5 w-3.5 text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/></svg>
                        </span>
                        <h4 class="text-sm font-semibold text-white">Lost or Damaged</h4>
                    </div>
                    <p class="text-xs text-secondary-400 leading-relaxed">Contact us within 48 hours of expected delivery. We will investigate and arrange a replacement or refund.</p>
                </div>

                {{-- Tracking --}}
                <div class="rounded-2xl border border-secondary-700/60 bg-secondary-800/50 p-5">
                    <div class="flex items-center gap-2.5 mb-3">
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-500/15">
                            <svg class="h-3.5 w-3.5 text-emerald-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                        </span>
                        <h4 class="text-sm font-semibold text-white">Track Order</h4>
                    </div>
                    <p class="text-xs text-secondary-400 leading-relaxed mb-3">Use your tracking number via your account dashboard, the carrier website, or contact support.</p>
                    <a href="/contact-us" class="inline-flex items-center gap-1.5 text-xs font-semibold text-primary-400 hover:text-primary-300 transition-colors">
                        Contact Support
                        <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════
     International Shipping
     ═══════════════════════════════════════════════════════════════ --}}
<section class="py-16 sm:py-20 bg-white dark:bg-secondary-900">
    <div class="section max-w-5xl mx-auto">
        <div class="mb-8 sm:mb-10">
            <span class="section-eyebrow">Global Reach</span>
            <h2 class="section-heading !text-2xl sm:!text-3xl">International Shipping</h2>
            <p class="mt-3 text-sm sm:text-base text-secondary-500 dark:text-secondary-400 max-w-xl">We ship to over 50 countries worldwide.</p>
        </div>
        <div class="rounded-2xl border border-secondary-200 dark:border-secondary-800 bg-secondary-50 dark:bg-secondary-800/40 p-6 sm:p-8">
            <p class="text-sm text-secondary-600 dark:text-secondary-300 leading-relaxed mb-5">International shipping rates are calculated at checkout based on destination, weight, and dimensions.</p>
            <ul class="space-y-3">
                <li class="flex items-start gap-3">
                    <span class="mt-1.5 shrink-0 w-1.5 h-1.5 rounded-full bg-primary-500"></span>
                    <span class="text-sm text-secondary-600 dark:text-secondary-300 leading-relaxed">International orders may be subject to customs duties and taxes, which are the responsibility of the recipient.</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="mt-1.5 shrink-0 w-1.5 h-1.5 rounded-full bg-primary-500"></span>
                    <span class="text-sm text-secondary-600 dark:text-secondary-300 leading-relaxed">Delivery times for international orders may vary due to customs processing.</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="mt-1.5 shrink-0 w-1.5 h-1.5 rounded-full bg-primary-500"></span>
                    <span class="text-sm text-secondary-600 dark:text-secondary-300 leading-relaxed">We are not responsible for packages held by customs in the destination country.</span>
                </li>
            </ul>
        </div>
    </div>
</section>

@include('frontend.partials.cta-help')

@push('styles')
@include('frontend.partials.page-content-styles')
@endpush
@endsection
