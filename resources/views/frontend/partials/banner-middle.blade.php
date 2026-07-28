@if(!empty($middleBanners) && $middleBanners->isNotEmpty())
    <section class="py-8 sm:py-10 lg:py-12 border-t border-secondary-200 dark:border-secondary-800">
        <div class="section">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($middleBanners as $banner)
                    @php
                        $textColor = $banner->text_color ?? 'text-white';
                        $alignClass = $banner->text_alignment_class;
                        $overlayRatio = $banner->overlay_opacity !== null ? $banner->overlay_opacity / 100 : null;
                        $overlayStyle = $overlayRatio !== null ? "rgba(0,0,0,{$overlayRatio})" : null;
                        $autoHide = $banner->show_countdown && $banner->ends_at ? 'true' : 'false';
                        $endDate = $banner->ends_at?->toIso8601String();
                    @endphp
                    <a href="{{ $banner->link ?? '#' }}"
                        x-data="bannerCountdown('{{ $endDate }}', {{ $autoHide }})"
                        x-init="init()"
                        x-show="show"
                        x-transition:leave="transition ease-in duration-500"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="group relative block overflow-hidden rounded-card bg-secondary-900 min-h-[200px] sm:min-h-[240px] {{ $loop->first ? 'sm:col-span-2 lg:col-span-1' : '' }}">
                        @if($banner->image_url)
                            <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="absolute inset-0 w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105" loading="lazy">
                        @endif
                        <div class="absolute inset-0" @if($overlayStyle) style="background: linear-gradient(to top, {{ $overlayStyle }} 60%, transparent 100%);" @else class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent" @endif></div>
                        <div class="relative h-full flex flex-col justify-end p-5">
                            <div class="flex flex-col {{ $alignClass }}">
                                @if($banner->badge)
                                    <span class="inline-flex items-center self-start rounded-full {{ $banner->badge_color ?? 'bg-primary-500' }} px-2.5 py-0.5 mb-2">
                                        <span class="text-xs font-bold text-white">{{ $banner->badge }}</span>
                                    </span>
                                @endif
                                @if($banner->title)
                                    <h3 class="text-lg sm:text-xl font-bold {{ $textColor }}">{{ $banner->title }}</h3>
                                @endif
                                @if($banner->description)
                                    <p class="text-sm mt-1 {{ str_replace('text-', 'text-', $textColor) }}/80">{{ $banner->description }}</p>
                                @elseif($banner->subtitle)
                                    <p class="text-sm mt-1 {{ str_replace('text-', 'text-', $textColor) }}/80">{{ $banner->subtitle }}</p>
                                @endif
                                @if($banner->show_countdown && $banner->ends_at)
                                    <div class="mt-3" x-show="show">
                                        <div class="flex gap-1.5">
                                            <div class="countdown-unit"><span class="text-sm font-bold {{ $textColor }}" x-text="days"></span><span class="text-[10px] uppercase {{ str_replace('text-', 'text-', $textColor) }}/60">Days</span></div>
                                            <div class="countdown-unit"><span class="text-sm font-bold {{ $textColor }}" x-text="hours"></span><span class="text-[10px] uppercase {{ str_replace('text-', 'text-', $textColor) }}/60">Hrs</span></div>
                                            <div class="countdown-unit"><span class="text-sm font-bold {{ $textColor }}" x-text="minutes"></span><span class="text-[10px] uppercase {{ str_replace('text-', 'text-', $textColor) }}/60">Min</span></div>
                                            <div class="countdown-unit"><span class="text-sm font-bold {{ $textColor }}" x-text="seconds"></span><span class="text-[10px] uppercase {{ str_replace('text-', 'text-', $textColor) }}/60">Sec</span></div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif
