@props([
    'title',
    'description' => null,
    'eyebrow' => null,
    'backgroundImage' => null,
])

<section class="relative overflow-hidden bg-secondary-950">
    @if($backgroundImage)
        <div class="absolute inset-0" aria-hidden="true">
            <img src="{{ $backgroundImage }}" alt="" class="h-full w-full object-cover scale-105" loading="eager">
            <div class="absolute inset-0 bg-gradient-to-r from-secondary-950/95 via-secondary-950/75 to-secondary-900/35"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-secondary-950 via-secondary-950/10 to-secondary-950/40"></div>
        </div>
    @else
        <div class="absolute inset-0 bg-gradient-to-br from-secondary-900 via-secondary-800 to-secondary-900 dark:from-secondary-950 dark:via-secondary-900 dark:to-secondary-950" aria-hidden="true"></div>
        <div class="absolute inset-0 opacity-[0.07]" aria-hidden="true">
            <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-primary-400 blur-3xl"></div>
            <div class="absolute -bottom-32 -left-32 w-80 h-80 rounded-full bg-primary-500 blur-3xl"></div>
        </div>
    @endif

    <div class="relative section py-14 sm:py-16 lg:py-20">

        <div class="max-w-3xl">
            @if($eyebrow)
                <span class="inline-flex items-center rounded-full border border-primary-400/30 bg-primary-400/10 px-3.5 py-1.5 text-[11px] font-semibold uppercase tracking-[0.22em] text-primary-300 dark:text-primary-200 mb-4">
                    {{ $eyebrow }}
                </span>
            @endif

            <h1 class="text-3xl sm:text-4xl lg:text-[2.75rem] font-extrabold text-white tracking-tight leading-tight mb-3 drop-shadow-sm">{{ $title }}</h1>

            @if($description)
                <p class="text-base sm:text-lg text-secondary-200/90 dark:text-secondary-300 leading-relaxed max-w-2xl">{{ $description }}</p>
            @endif
        </div>
    </div>
</section>
