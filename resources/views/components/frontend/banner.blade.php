@props(['position' => 'promotional', 'page' => null, 'limit' => null])

@php
    $banners = $page
        ? \App\Models\Banner::forTargetPage($page)->active()->ordered()->get()
        : \App\Models\Banner::getCachedForPosition($position);

    if ($limit && $banners->isNotEmpty()) {
        $banners = $banners->take($limit);
    }
@endphp

@if($banners->isNotEmpty())
    <section class="py-8 sm:py-10 lg:py-12">
        <div class="section flex flex-col gap-6 sm:gap-8">
            @foreach($banners as $banner)
                @php
                    $textColor = $banner->text_color ?? 'text-white';
                    $alignClass = $banner->text_alignment_class;
                    $overlay = $banner->overlay_opacity !== null ? $banner->overlay_opacity / 100 : 0.45;
                    $endDate = $banner->ends_at?->toIso8601String();
                    $autoHide = $banner->show_countdown && $banner->ends_at ? 'true' : 'false';
                @endphp
                <article
                    class="banner-card banner-card--{{ $banner->text_alignment ?? 'left' }}"
                    style="--banner-overlay: rgba(0,0,0,{{ $overlay }});"
                    x-data="bannerCountdown('{{ $endDate }}', {{ $autoHide }})"
                    x-init="init()"
                    x-show="show"
                >
                    @if($banner->image_url)
                        <div class="banner-card__bg hidden md:block" style="--banner-image: url('{{ $banner->image_url }}')" aria-hidden="true"></div>
                    @endif
                    @if($banner->mobile_image_url || $banner->image_url)
                        <div class="banner-card__bg md:hidden" style="--banner-image: url('{{ $banner->mobile_image_url ?: $banner->image_url }}')" aria-hidden="true"></div>
                    @endif

                    <div class="banner-card__overlay" aria-hidden="true"></div>

                    <div class="banner-card__content">
                        <div class="banner-card__inner {{ $alignClass }}">
                            @if($banner->badge)
                                <span class="inline-flex items-center rounded-full {{ $banner->badge_color ?? 'bg-primary-500' }} px-3 py-1 mb-3 {{ $banner->text_alignment === 'center' ? 'mx-auto' : '' }} {{ $banner->text_alignment === 'right' ? 'ml-auto' : '' }}">
                                    <span class="text-xs font-bold text-white tracking-wider">{{ $banner->badge }}</span>
                                </span>
                            @endif
                            @if($banner->title)
                                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold leading-tight {{ $textColor }}">{{ $banner->title }}</h2>
                            @endif
                            @if($banner->subtitle)
                                <p class="mt-2 text-sm sm:text-base font-medium {{ $textColor }}/70">{{ $banner->subtitle }}</p>
                            @endif
                            @if($banner->description)
                                <p class="mt-2 text-sm sm:text-base max-w-lg {{ $textColor }}/80">{{ $banner->description }}</p>
                            @endif
                            @if($banner->show_countdown && $banner->ends_at)
                                <div class="mt-5 flex gap-2">
                                    <div class="countdown-unit"><span class="text-sm sm:text-lg font-bold {{ $textColor }}" x-text="days"></span><span class="text-[10px] uppercase {{ $textColor }}/60">Days</span></div>
                                    <div class="countdown-unit"><span class="text-sm sm:text-lg font-bold {{ $textColor }}" x-text="hours"></span><span class="text-[10px] uppercase {{ $textColor }}/60">Hours</span></div>
                                    <div class="countdown-unit"><span class="text-sm sm:text-lg font-bold {{ $textColor }}" x-text="minutes"></span><span class="text-[10px] uppercase {{ $textColor }}/60">Mins</span></div>
                                    <div class="countdown-unit"><span class="text-sm sm:text-lg font-bold {{ $textColor }}" x-text="seconds"></span><span class="text-[10px] uppercase {{ $textColor }}/60">Secs</span></div>
                                </div>
                            @endif
                            @if($banner->button_text || $banner->secondary_button_text)
                                <div class="mt-5 flex flex-wrap gap-3 {{ $banner->text_alignment === 'center' ? 'justify-center' : '' }} {{ $banner->text_alignment === 'right' ? 'justify-end' : 'justify-start' }}">
                                    @if($banner->button_text)
                                        <a href="{{ $banner->link ?? '#' }}" class="inline-flex items-center gap-2 btn-primary btn-sm">
                                            {{ $banner->button_text }}
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                                        </a>
                                    @endif
                                    @if($banner->secondary_button_text)
                                        <a href="{{ $banner->secondary_button_url ?? '#' }}" class="inline-flex items-center gap-2 btn bg-white/10 text-white hover:bg-white/20 border border-white/20 btn-sm backdrop-blur-sm">
                                            {{ $banner->secondary_button_text }}
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
@endif
