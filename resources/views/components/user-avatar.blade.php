@props([
    'user' => null,
    'size' => 'h-9 w-9',
    'textSize' => 'text-xs',
])

@php
    $u = $user ?? auth()->user();
@endphp

@if($u)
    <span {{ $attributes }}
        x-data="{ url: @js($u->avatarUrl()), has: {{ $u->avatar_path ? 'true' : 'false' }} }"
        @avatar-updated.window="
            has = !!$event.detail.url;
            if (has) {
                url = $event.detail.url;
            }
        ">
        <img x-show="has" :src="url" alt="{{ $u->name }}"
            class="{{ $size }} rounded-full object-cover ring-2 ring-primary-500/20 shadow-sm">
        <span x-show="!has"
            class="{{ $size }} {{ $textSize }} flex items-center justify-center rounded-full bg-gradient-to-br from-primary-500 to-primary-600 font-bold text-white shadow-sm">
            {{ $u->initials() }}
        </span>
    </span>
@endif
