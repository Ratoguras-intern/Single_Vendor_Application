@props([
    'title',
    'description' => null,
    'eyebrow' => null,
    'backgroundImage' => null,
])

<section class="relative overflow-hidden bg-gradient-to-br from-secondary-900 via-secondary-800 to-secondary-900 dark:from-secondary-950 dark:via-secondary-900 dark:to-secondary-950">
    @if($backgroundImage)
        <div class="absolute inset-0">
            <img src="{{ $backgroundImage }}" alt="" class="h-full w-full object-cover opacity-15" aria-hidden="true">
            <div class="absolute inset-0 bg-gradient-to-br from-secondary-900/80 via-secondary-800/70 to-secondary-900/80 dark:from-secondary-950/80 dark:via-secondary-900/70 dark:to-secondary-950/80"></div>
        </div>
    @else
        <div class="absolute inset-0 opacity-[0.07]">
            <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-primary-400 blur-3xl"></div>
            <div class="absolute -bottom-32 -left-32 w-80 h-80 rounded-full bg-primary-500 blur-3xl"></div>
        </div>
    @endif

    <div class="relative section py-10 sm:py-14 lg:py-16">
        <div class="max-w-3xl">
            @if($eyebrow)
                <span class="inline-flex items-center gap-2 text-[11px] font-semibold uppercase tracking-[0.22em] text-primary-400 dark:text-primary-300 mb-3">{{ $eyebrow }}</span>
            @endif

            <h1 class="text-3xl sm:text-4xl lg:text-[2.75rem] font-extrabold text-white tracking-tight leading-tight mb-3">{{ $title }}</h1>

            @if($description)
                <p class="text-base sm:text-lg text-secondary-300 dark:text-secondary-400 leading-relaxed max-w-2xl">{{ $description }}</p>
            @endif
        </div>
    </div>
</section>
