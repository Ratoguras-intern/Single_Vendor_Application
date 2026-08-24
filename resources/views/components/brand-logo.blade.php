@props([
    'sidebar' => false,
    'href' => '/',
    'subtitle' => '',
    'showText' => true,
    'compact' => false,
    'textClass' => '',
])

@php
    $appName = site_name();
    $words = explode(' ', $appName);
    $initials = strtoupper(collect($words)->map(fn($w) => $w[0] ?? '')->take(2)->join(''));

    $logoPath = \App\Models\Setting::get('site_logo');
    $hasLogo = $logoPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($logoPath);
    $logoUrl = $hasLogo ? \Illuminate\Support\Facades\Storage::disk('public')->url($logoPath) : null;
@endphp

<a href="{{ $href }}"
    class="flex items-center gap-2.5 group select-none {{ $compact ? '' : '' }}">

    @if($logoUrl)
        <div class="relative flex {{ $compact ? 'h-7 w-7' : 'h-8 w-8' }} shrink-0 items-center justify-center overflow-hidden rounded-lg shadow-lg shadow-secondary-900/20 transition-all duration-300 group-hover:scale-105">
            <img src="{{ $logoUrl }}" alt="{{ $appName }} logo"
                class="h-full w-full object-contain"
                onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
            <div class="absolute inset-0 hidden items-center justify-center rounded-lg bg-gradient-to-br from-secondary-900 via-secondary-800 to-secondary-700">
                <span class="relative flex items-center {{ $compact ? 'text-xs' : 'text-sm' }} font-black tracking-tight">
                    <span class="text-white">{{ $initials }}</span>
                </span>
            </div>
        </div>
    @else
        <div class="relative flex {{ $compact ? 'h-7 w-7' : 'h-8 w-8' }} shrink-0 items-center justify-center overflow-hidden rounded-lg bg-gradient-to-br from-secondary-900 via-secondary-800 to-secondary-700 shadow-lg shadow-secondary-900/20 transition-all duration-300 group-hover:scale-105">
            <div class="absolute inset-0 rounded-lg ring-1 ring-white/10"></div>
            <span class="relative flex items-center {{ $compact ? 'text-xs' : 'text-sm' }} font-black tracking-tight">
                <span class="text-white">{{ $initials }}</span>
            </span>
        </div>
    @endif

    @if($showText)
        @if($sidebar)
            <div x-show="$store.sidebar?.isExpanded || $store.sidebar?.isHovered || $store.sidebar?.isMobileOpen"
                x-transition.opacity class="leading-tight">
        @else
            <div class="leading-tight">
        @endif

            <div class="flex items-center gap-1.5">
                <span class="{{ $compact ? 'text-lg' : 'text-xl' }} font-extrabold tracking-tight text-secondary-900 dark:text-white {{ $textClass }}">
                    {{ $appName }}
                </span>
            </div>

            @if($subtitle)
                <p class="mt-px text-[10px] uppercase tracking-[0.3em] font-medium text-secondary-400 dark:text-secondary-500">
                    {{ $subtitle }}
                </p>
            @endif

        </div>
    @endif
</a>
