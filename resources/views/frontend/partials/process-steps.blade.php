@props([
    'steps' => [],           // collection of objects/arrays with title + description
    'columns' => 5,          // lg column count for horizontal layout
])

@php
    $stepList = collect($steps)->map(fn ($s) => is_array($s)
        ? ['title' => $s['title'] ?? '', 'description' => $s['description'] ?? '']
        : ['title' => $s->title ?? '', 'description' => $s->description ?? '']);
@endphp

@if ($stepList->isNotEmpty())
    <ol class="grid grid-cols-1 sm:grid-cols-2 {{ $columns >= 5 ? 'lg:grid-cols-5' : 'lg:grid-cols-'.$columns }} gap-4">
        @foreach ($stepList as $index => $step)
            <li class="relative card flex flex-col items-start">
                <span class="text-xs font-bold tracking-[0.2em] text-primary-500 dark:text-primary-400 mb-3">
                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                </span>
                <h3 class="font-semibold text-secondary-900 dark:text-white text-sm sm:text-base mb-1.5">{{ $step['title'] }}</h3>
                @if ($step['description'])
                    <p class="text-xs sm:text-sm text-secondary-500 dark:text-secondary-400 leading-relaxed">{{ $step['description'] }}</p>
                @endif
            </li>
        @endforeach
    </ol>
@endif
