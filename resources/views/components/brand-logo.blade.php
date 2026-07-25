@props([
    'sidebar' => false,
    'href' => '/',
    'subtitle' => 'ADMIN SUITE',
    'showText' => true,
])

<!-- =========================
     NBK Vertex Brand
========================= -->
<a href="{{ $href }}"
    class="flex items-center gap-3 group select-none">

    <!-- Logo -->
    <div
        class="relative flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden
               rounded-2xl bg-gradient-to-br
               from-slate-950 via-indigo-700 to-violet-600
               shadow-lg shadow-indigo-500/20
               transition-all duration-300 group-hover:scale-105">

        <!-- Glow -->
        <div class="absolute inset-0 rounded-2xl ring-1 ring-white/10"></div>

        <!-- Accent -->
        <div class="absolute -top-3 -right-3 h-8 w-8 rounded-full bg-cyan-400/20 blur-md"></div>
        <span class="absolute top-1.5 right-1.5 h-2.5 w-2.5 rounded-full bg-cyan-400"></span>

        <!-- NV Monogram -->
        <span class="relative flex items-center text-lg font-black tracking-tight">
            <span class="text-white">N</span>
            <span class="-ml-1 text-cyan-300">V</span>
        </span>

    </div>

    <!-- Brand -->
    @if($showText)
        @if($sidebar)
            <div
                x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                x-transition.opacity
                class="leading-tight">
        @else
            <div class="leading-tight">
        @endif

            <div class="flex items-center gap-2">

                <span class="text-xl font-black tracking-tight text-gray-900 dark:text-white">
                    NBK
                </span>

                <span class="h-5 w-px bg-gray-300 dark:bg-gray-600"></span>

                <span
                    class="text-xl font-black tracking-tight
                           bg-gradient-to-r
                           from-indigo-600
                           via-violet-600
                           to-cyan-500
                           bg-clip-text text-transparent">
                    Vertex
                </span>

            </div>

            <p class="mt-0.5 text-[10px] uppercase tracking-[0.45em] text-gray-500 dark:text-gray-400">
                {{ $subtitle }}
            </p>

        </div>
    @endif

</a>
