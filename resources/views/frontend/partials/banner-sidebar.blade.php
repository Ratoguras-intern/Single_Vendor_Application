@if(!empty($sidebarBanners) && $sidebarBanners->isNotEmpty())
    @foreach($sidebarBanners as $banner)
        @php
            $textColor = $banner->text_color ?? 'text-white';
            $autoHide = $banner->show_countdown && $banner->ends_at ? 'true' : 'false';
            $endDate = $banner->ends_at?->toIso8601String();
            $verticalClass = $banner->content_vertical === 'top' ? 'justify-start' : ($banner->content_vertical === 'center' ? 'justify-center' : 'justify-end');
            $tileStyle = trim($banner->border_radius_css);
            $textWidthStyle = $banner->text_width_css;
            $visibility = $banner->visibility_classes;
        @endphp
        <div class="mb-6 {{ $visibility }}" @if($banner->section_margin_css) style="{{ $banner->section_margin_css }}" @endif
            x-data="bannerCountdown('{{ $endDate }}', {{ $autoHide }})"
            x-init="init()"
            x-show="show"
            x-transition:leave="transition ease-in duration-500"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95">
            <a href="{{ $banner->link ?? '#' }}" class="group relative block overflow-hidden rounded-card bg-secondary-900 aspect-[4/5] sm:aspect-[3/4] lg:aspect-[4/3]" @if($tileStyle) style="{{ $tileStyle }}" @endif>
                @if($banner->image_url)
                    <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="absolute inset-0 w-full h-full transition-transform duration-500 group-hover:scale-105 hidden md:block" style="{{ $banner->image_css }}" loading="lazy">
                @endif
                @if($banner->mobile_image_url)
                    <img src="{{ $banner->mobile_image_url }}" alt="{{ $banner->title }}" class="absolute inset-0 w-full h-full transition-transform duration-500 group-hover:scale-105 md:hidden" style="{{ $banner->image_css }}" loading="lazy">
                @elseif($banner->image_url)
                    <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="absolute inset-0 w-full h-full transition-transform duration-500 group-hover:scale-105 md:hidden" style="{{ $banner->image_css }}" loading="lazy">
                @endif
                @if($banner->overlay_enabled)
                    <div class="absolute inset-0" style="background: linear-gradient(to top, {{ $banner->overlay_rgba }} 60%, transparent 100%);"></div>
                @endif
                <div class="relative h-full flex flex-col {{ $verticalClass }} p-4" @if($banner->content_padding_css) style="{{ $banner->content_padding_css }}" @endif>
                    <div class="flex flex-col {{ $banner->text_alignment_class }}" @if($textWidthStyle) style="{{ $textWidthStyle }}" @endif>
                        @if($banner->badge)
                            <span class="inline-flex items-center self-start rounded-full {{ $banner->badge_color ?? 'bg-primary-500' }} px-2 py-0.5 mb-2">
                                <span class="text-xs font-bold text-white">{{ $banner->badge }}</span>
                            </span>
                        @endif
                        @if($banner->title)
                            <h3 class="text-sm font-bold {{ $textColor }}">{{ $banner->title }}</h3>
                        @endif
                        @if($banner->description)
                            <p class="text-xs mt-0.5 {{ $textColor }}/80">{{ $banner->description }}</p>
                        @elseif($banner->subtitle)
                            <p class="text-xs mt-0.5 {{ $textColor }}/80">{{ $banner->subtitle }}</p>
                        @endif
                        @if($banner->show_countdown && $banner->ends_at)
                            <div class="mt-2" x-show="show">
                                <div class="flex gap-1">
                                    <div class="countdown-unit !px-2 !py-1 !min-w-[40px]"><span class="text-xs font-bold {{ $textColor }}" x-text="days"></span><span class="text-[8px] uppercase {{ $textColor }}/60">D</span></div>
                                    <div class="countdown-unit !px-2 !py-1 !min-w-[40px]"><span class="text-xs font-bold {{ $textColor }}" x-text="hours"></span><span class="text-[8px] uppercase {{ $textColor }}/60">H</span></div>
                                    <div class="countdown-unit !px-2 !py-1 !min-w-[40px]"><span class="text-xs font-bold {{ $textColor }}" x-text="minutes"></span><span class="text-[8px] uppercase {{ $textColor }}/60">M</span></div>
                                    <div class="countdown-unit !px-2 !py-1 !min-w-[40px]"><span class="text-xs font-bold {{ $textColor }}" x-text="seconds"></span><span class="text-[8px] uppercase {{ $textColor }}/60">S</span></div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </a>
        </div>
    @endforeach
@endif
