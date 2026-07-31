@if(!empty($featuredBanners) && $featuredBanners->isNotEmpty())
    @foreach($featuredBanners as $banner)
        @php
            $textColor = $banner->text_color ?? 'text-white';
            $alignClass = $banner->text_alignment_class;
            $overlayRatio = $banner->overlay_opacity !== null ? $banner->overlay_opacity / 100 : null;
            $overlayStyle = $overlayRatio !== null ? "rgba(0,0,0,{$overlayRatio})" : null;
            $autoHide = $banner->show_countdown && $banner->ends_at ? 'true' : 'false';
            $endDate = $banner->ends_at?->toIso8601String();
        @endphp
        <section class="py-8 sm:py-10 lg:py-12 border-t border-secondary-200 dark:border-secondary-800"
            x-data="bannerCountdown('{{ $endDate }}', {{ $autoHide }})"
            x-init="init()"
            x-show="show"
            x-transition:leave="transition ease-in duration-500"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95">
            <div class="section">
                <a href="{{ $banner->link ?? '#' }}" class="group relative block w-full overflow-hidden rounded-card bg-secondary-900 min-h-[200px] sm:min-h-[250px] lg:min-h-[300px]">
                    @if($banner->image_url)
                        <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="absolute inset-0 w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105 hidden md:block" loading="lazy">
                    @endif
                    @if($banner->mobile_image_url)
                        <img src="{{ $banner->mobile_image_url }}" alt="{{ $banner->title }}" class="absolute inset-0 w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105 md:hidden" loading="lazy">
                    @endif
                    <div class="absolute inset-0" @if($overlayStyle) style="background: linear-gradient(to left, {{ $overlayStyle }} 40%, transparent 100%);" @else class="absolute inset-0 bg-gradient-to-l from-black/60 via-black/20 to-transparent" @endif></div>
                    <div class="relative h-full flex items-center px-6 sm:px-10 lg:px-14 py-8 sm:py-10 {{ $banner->text_alignment === 'right' ? 'justify-start' : ($banner->text_alignment === 'center' ? 'justify-center' : 'justify-end') }}">
                        <div class="max-w-xl flex flex-col {{ $alignClass }}">
                            @if($banner->badge)
                                <span class="inline-flex items-center rounded-full {{ $banner->badge_color ?? 'bg-primary-500' }} px-3 py-1 mb-3 {{ $banner->text_alignment === 'center' ? 'mx-auto' : '' }} {{ $banner->text_alignment === 'right' ? 'ml-auto mr-0' : 'ml-0 mr-auto' }}">
                                    <span class="text-xs font-bold text-white tracking-wider">{{ $banner->badge }}</span>
                                </span>
                            @endif
                            @if($banner->title)
                                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold leading-tight {{ $textColor }}">{{ $banner->title }}</h2>
                            @endif
                            @if($banner->description)
                                <p class="mt-2 text-sm sm:text-base max-w-lg {{ $textColor }}/80">{{ $banner->description }}</p>
                            @elseif($banner->subtitle)
                                <p class="mt-2 text-sm sm:text-base max-w-lg {{ $textColor }}/80">{{ $banner->subtitle }}</p>
                            @endif
                            @if($banner->button_text || $banner->secondary_button_text)
                                <div class="mt-4 flex flex-wrap gap-3 {{ $banner->text_alignment === 'center' ? 'justify-center' : '' }} {{ $banner->text_alignment === 'right' ? 'justify-end' : 'justify-start' }}">
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
                            @if($banner->show_countdown && $banner->ends_at)
                                <div class="mt-5" x-show="show">
                                    <div class="flex gap-2">
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
