@extends('admin.layouts.app')

@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Banners', 'url' => route('admin.banners.index')],
        ['label' => $banner->title ?? 'Banner #' . $banner->id, 'url' => null],
    ];
@endphp

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white">Banner Details</h2>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.banners.edit', $banner) }}" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">Edit</a>
            <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" onsubmit="return confirm('Delete?')">
                @csrf @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-red-300 px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50">Delete</button>
            </form>
        </div>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="space-y-4">
            @if($banner->image)
                <div>
                    <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="rounded-lg max-h-64 object-cover">
                </div>
            @endif
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><span class="font-medium text-gray-700 dark:text-gray-300">Title:</span> <span class="text-gray-800 dark:text-white">{{ $banner->title ?? '—' }}</span></div>
                <div><span class="font-medium text-gray-700 dark:text-gray-300">Subtitle:</span> <span class="text-gray-800 dark:text-white">{{ $banner->subtitle ?? '—' }}</span></div>
                <div><span class="font-medium text-gray-700 dark:text-gray-300">Position:</span> <span class="text-gray-800 dark:text-white">{{ ucfirst($banner->position) }}</span></div>
                <div><span class="font-medium text-gray-700 dark:text-gray-300">Link:</span> <span class="text-gray-800 dark:text-white">{{ $banner->link ?? '—' }}</span></div>
                <div><span class="font-medium text-gray-700 dark:text-gray-300">Button Text:</span> <span class="text-gray-800 dark:text-white">{{ $banner->button_text ?? '—' }}</span></div>
                <div><span class="font-medium text-gray-700 dark:text-gray-300">Sort Order:</span> <span class="text-gray-800 dark:text-white">{{ $banner->sort_order }}</span></div>
                <div><span class="font-medium text-gray-700 dark:text-gray-300">Status:</span>
                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $banner->is_enabled ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' : 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400' }}">
                        {{ $banner->is_enabled ? 'Enabled' : 'Disabled' }}
                    </span>
                </div>
                <div><span class="font-medium text-gray-700 dark:text-gray-300">Schedule:</span> <span class="text-gray-800 dark:text-white">{{ $banner->starts_at?->format('M d, Y H:i') ?? 'Now' }} — {{ $banner->ends_at?->format('M d, Y H:i') ?? '∞' }}</span></div>
            </div>
        </div>
    </div>
@endsection
