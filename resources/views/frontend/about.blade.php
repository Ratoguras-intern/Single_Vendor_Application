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
    $values = collect([
        [
            'icon' => 'check',
            'color' => 'emerald',
            'title' => \App\Models\Setting::get('about.value1_title') ?: 'Quality first',
            'text' => \App\Models\Setting::get('about.value1_text') ?: 'Every product is checked before it ships.',
        ],
        [
            'icon' => 'truck',
            'color' => 'primary',
            'title' => \App\Models\Setting::get('about.value2_title') ?: 'Fast, careful delivery',
            'text' => \App\Models\Setting::get('about.value2_text') ?: 'Tracked shipping with protective packaging.',
        ],
        [
            'icon' => 'chat',
            'color' => 'violet',
            'title' => \App\Models\Setting::get('about.value3_title') ?: 'Support that answers',
            'text' => \App\Models\Setting::get('about.value3_text') ?: 'Real people, replies within a business day.',
        ],
    ])->filter(fn ($v) => filled($v['title']));

    $badgeTitle = \App\Models\Setting::get('about.badge_title') ?: '';
    if (! filled($badgeTitle)) {
        $badgeTitle = filled($foundedYear) ? "Serving customers since {$foundedYear}" : 'Made for everyday shoppers';
    }
    $badgeSub = \App\Models\Setting::get('about.badge_sub') ?: 'One store, obsessed with getting it right.';

    $valueIcons = [
        'check' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z"/>',
        'truck' => '<path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>',
        'chat' => '<path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155"/>',
    ];
    $valueColorClasses = [
        'emerald' => 'bg-emerald-50 dark:bg-emerald-950/30 text-emerald-500',
        'primary' => 'bg-primary-50 dark:bg-primary-950/30 text-primary-500',
        'violet' => 'bg-violet-50 dark:bg-violet-950/30 text-violet-500',
    ];
@endphp
@include('frontend.partials.page-hero', [
    'title' => $page->title,
    'description' => $page->short_description,
    'eyebrow' => $page->subtitle ?: 'Our Story',
    'backgroundImage' => $page->featured_image_url ?? asset('images/pages/about-team.jpg'),
])

@php $hasStory = $content && trim(strip_tags($content)) !== ''; @endphp

<section class="py-12 sm:py-16 lg:py-20 bg-secondary-50 dark:bg-secondary-950">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        @if ($stats->isNotEmpty())
            <dl class="mb-10 grid grid-cols-2 gap-4 sm:mb-12 sm:gap-5 lg:grid-cols-4">
                @foreach ($stats as $stat)
                    <div class="rounded-2xl border border-secondary-200 bg-white px-4 py-5 text-center shadow-card dark:border-secondary-800 dark:bg-secondary-900 sm:px-5">
                        <dd class="text-2xl font-extrabold tabular-nums text-primary-500 dark:text-primary-400 sm:text-3xl">{{ number_format($stat['value']) }}+</dd>
                        <dt class="mt-1 text-xs font-medium text-secondary-500 dark:text-secondary-400 sm:text-sm">{{ $stat['label'] }}</dt>
                    </div>
                @endforeach
            </dl>
        @endif

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12 lg:gap-12 xl:gap-16">
            @if ($hasStory)
                <article class="card order-1 min-w-0 p-6 sm:p-8 lg:col-span-7 lg:p-10">
                    <div class="page-content text-secondary-600 dark:text-secondary-400">{!! $content !!}</div>
                </article>
            @endif

            <div class="order-2 min-w-0 {{ $hasStory ? 'lg:col-span-5' : 'mx-auto w-full max-w-3xl lg:col-span-12' }}">
                <div class="space-y-6 lg:sticky lg:top-24">
                    <div class="group w-full overflow-hidden rounded-2xl shadow-card">
                        <img src="{{ asset('images/pages/about-collab.jpg') }}" alt="Our team collaborating in the studio" loading="lazy" class="block aspect-[4/3] w-full object-cover transition-transform duration-500 group-hover:scale-[1.03]">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="group w-full overflow-hidden rounded-2xl shadow-card">
                            <img src="{{ asset('images/pages/shopping-bags.jpg') }}" alt="Orders packed and ready for our customers" loading="lazy" class="block aspect-[4/3] w-full object-cover transition-transform duration-500 group-hover:scale-[1.03]">
                        </div>
                        <div class="flex aspect-[4/3] flex-col justify-center rounded-2xl bg-gradient-to-br from-primary-500 to-primary-600 p-5 text-white shadow-card sm:p-6">
                            <svg class="mb-3 h-7 w-7 opacity-90" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456Z"/></svg>
                            <p class="font-bold leading-snug">{{ $badgeTitle }}</p>
                            @if (filled($badgeSub))
                                <p class="mt-1.5 text-xs text-primary-100">{{ $badgeSub }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($values->isNotEmpty())
            <div class="grid sm:grid-cols-3 gap-5 lg:gap-6 mt-12 lg:mt-16">
                @foreach ($values as $value)
                    <div class="card-hover flex items-start gap-3.5 !p-5">
                        <span class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 {{ $valueColorClasses[$value['color']] ?? $valueColorClasses['primary'] }}">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">{!! $valueIcons[$value['icon']] ?? $valueIcons['check'] !!}</svg>
                        </span>
                        <span>
                            <span class="block font-semibold text-secondary-900 dark:text-white text-sm">{{ $value['title'] }}</span>
                            @if (filled($value['text']))
                                <span class="block text-xs text-secondary-500 dark:text-secondary-400 mt-1 leading-relaxed">{{ $value['text'] }}</span>
                            @endif
                        </span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

<section class="py-12 sm:py-16 lg:py-20 bg-secondary-50 dark:bg-secondary-950">
    <div class="section">
        <div class="grid sm:grid-cols-2 gap-5 max-w-5xl mx-auto">
            <a href="{{ route('frontend.shop') }}" class="card-hover group flex items-center gap-4 !p-6">
                <span class="w-12 h-12 rounded-xl bg-primary-500 text-white flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform duration-200">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/></svg>
                </span>
                <span>
                    <span class="block font-semibold text-secondary-900 dark:text-white">Shop Products</span>
                    <span class="block text-sm text-secondary-500 dark:text-secondary-400 mt-0.5">Explore our full collection.</span>
                </span>
            </a>
            <a href="/contact-us" class="card-hover group flex items-center gap-4 !p-6">
                <span class="w-12 h-12 rounded-xl bg-secondary-900 dark:bg-secondary-700 text-white flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform duration-200">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                </span>
                <span>
                    <span class="block font-semibold text-secondary-900 dark:text-white">Contact Support</span>
                    <span class="block text-sm text-secondary-500 dark:text-secondary-400 mt-0.5">Questions? We're happy to help.</span>
                </span>
            </a>
        </div>
    </div>
</section>

@push('styles')
@include('frontend.partials.page-content-styles')
@endpush
@endsection
