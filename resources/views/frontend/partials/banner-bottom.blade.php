@if(!empty($bottomBanners) && $bottomBanners->isNotEmpty())
    @foreach($bottomBanners as $banner)
        @php
            $textColor = $banner->text_color ?? 'text-white';
            $alignClass = $banner->text_alignment_class;
            $autoHide = $banner->show_countdown && $banner->ends_at ? 'true' : 'false';
            $endDate = $banner->ends_at?->toIso8601String();
            $justifyClass = $banner->content_vertical === 'top' ? 'justify-start' : ($banner->content_vertical === 'bottom' ? 'justify-end' : 'justify-center');
            $overlayDir = $banner->text_alignment === 'right' ? 'to left' : 'to right';
            $tileStyle = trim($banner->banner_height_css . ' ' . $banner->border_radius_css);
            $textWidthStyle = $banner->text_width_css;
            $visibility = $banner->visibility_classes;
            $overlayBackground = $banner->overlay_enabled
                ? 'linear-gradient(' . $overlayDir . ', ' . $banner->overlay_rgba . ' 40%, transparent 100%)'
                : null;
        @endphp
        <section class="border-t border-secondary-200 dark:border-secondary-800 {{ $visibility }}" @if($banner->section_margin_css) style="{{ $banner->section_margin_css }}" @endif
            x-data="bannerCountdown('{{ $endDate }}', {{ $autoHide }})"
            x-init="init()">
            <div class="section py-0">
                <a href="{{ $banner->link ?? '#' }}" class="group relative flex w-full overflow-hidden rounded-card bg-secondary-900 min-h-[160px] sm:min-h-[200px] lg:min-h-[250px]" @if($tileStyle) style="{{ $tileStyle }}" @endif>
                    @include('frontend.partials.banner-media', ['banner' => $banner, 'overlayBackground' => $overlayBackground])
                    <div class="relative z-10 w-full flex flex-col {{ $justifyClass }} {{ $alignClass }} px-6 sm:px-10 lg:px-14 py-8 sm:py-10" @if($banner->content_padding_css) style="{{ $banner->content_padding_css }}" @endif>
                        <div class="w-full flex flex-col {{ $banner->text_alignment === 'center' ? 'mx-auto' : '' }} {{ $banner->text_alignment === 'right' ? 'ml-auto' : '' }}" @if($textWidthStyle) style="{{ $textWidthStyle }}" @endif>
                            @if($banner->badge)
                                <span class="inline-flex items-center gap-2 self-start rounded-full {{ $banner->badge_color ?? 'bg-primary-500' }} px-4 py-1.5 mb-5">
                                    <span class="text-xs font-bold text-white tracking-wider">{{ $banner->badge }}</span>
                                </span>
                            @endif
                            @if($banner->title)
                                <h2 class="font-display text-3xl sm:text-4xl lg:text-5xl font-semibold tracking-tight leading-[1.1] {{ $banner->text_alignment === 'center' ? 'mx-auto' : '' }} {{ $banner->text_alignment === 'right' ? 'ml-auto' : '' }} {{ $textColor }}">{{ $banner->title }}</h2>
                            @endif
                            @if($banner->description)
                                <p class="mt-3 text-sm sm:text-base lg:text-lg max-w-xl leading-relaxed {{ $banner->text_alignment === 'center' ? 'mx-auto' : '' }} {{ $banner->text_alignment === 'right' ? 'ml-auto' : '' }} {{ $textColor }}/80">{{ $banner->description }}</p>
                            @elseif($banner->subtitle)
                                <p class="mt-3 text-sm sm:text-base lg:text-lg max-w-xl leading-relaxed {{ $banner->text_alignment === 'center' ? 'mx-auto' : '' }} {{ $banner->text_alignment === 'right' ? 'ml-auto' : '' }} {{ $textColor }}/80">{{ $banner->subtitle }}</p>
                            @endif
                            @if($banner->button_text || $banner->secondary_button_text)
                                <div class="mt-6 sm:mt-8 flex flex-col sm:flex-row w-full sm:w-auto items-start gap-4 {{ $banner->text_alignment === 'center' ? 'mx-auto' : '' }} {{ $banner->text_alignment === 'right' ? 'ml-auto' : '' }}">
                                    @if($banner->button_text)
                                        <a href="{{ $banner->link ?? '#' }}" class="btn-primary btn-lg w-full sm:w-auto justify-center shadow-lg shadow-primary-500/25">
                                            {{ $banner->button_text }}
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                                        </a>
                                    @endif
                                    @if($banner->secondary_button_text)
                                        <a href="{{ $banner->secondary_button_url ?? '#' }}" class="btn bg-white/10 text-white hover:bg-white/20 border border-white/20 btn-lg w-full sm:w-auto justify-center backdrop-blur-sm">{{ $banner->secondary_button_text }}</a>
                                    @endif
                                </div>
                            @endif
                            @if($banner->show_countdown && $banner->ends_at)
                                <div class="mt-6 sm:mt-7 flex {{ $banner->text_alignment === 'center' ? 'justify-center' : ($banner->text_alignment === 'right' ? 'justify-end' : '') }}" x-show="show">
                                    <div class="flex flex-wrap gap-2 sm:gap-3">
                                        <div class="countdown-unit"><span class="text-sm sm:text-lg font-bold {{ $textColor }}" x-text="days"></span><span class="text-[10px] uppercase {{ $textColor }}/60">Days</span></div>
                                        <div class="countdown-unit"><span class="text-sm sm:text-lg font-bold {{ $textColor }}" x-text="hours"></span><span class="text-[10px] uppercase {{ $textColor }}/60">Hours</span></div>
                                        <div class="countdown-unit"><span class="text-sm sm:text-lg font-bold {{ $textColor }}" x-text="minutes"></span><span class="text-[10px] uppercase {{ $textColor }}/60">Mins</span></div>
                                        <div class="countdown-unit"><span class="text-sm sm:text-lg font-bold {{ $textColor }}" x-text="seconds"></span><span class="text-[10px] uppercase {{ $textColor }}/60">Secs</span></div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </a>
            </div>
        </section>
    @endforeach
@endif
