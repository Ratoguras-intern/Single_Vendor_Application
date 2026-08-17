@extends('admin.layouts.app')

@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Navigation', 'url' => null],
    ];
@endphp

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white">Navigation Management</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Manage frontend nav links, mega menu, and admin sidebar.</p>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        @foreach($menus as $menu)
            <a href="{{ route('admin.navigations.show', $menu) }}"
                class="group block rounded-lg border border-gray-200 bg-white p-5 transition-all hover:border-brand-300 hover:shadow-md dark:border-gray-800 dark:bg-white/[0.03] dark:hover:border-brand-500/50">
                <div class="flex items-start justify-between">
                    <div class="min-w-0">
                        <h3 class="text-base font-semibold text-gray-800 dark:text-white group-hover:text-brand-600 dark:group-hover:text-brand-400 truncate">
                            {{ $menu->name }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            {{ $menu->items_count }} {{ Str::plural('item', $menu->items_count) }}
                        </p>
                        <p class="mt-2 text-xs text-gray-400 dark:text-gray-500 font-mono">{{ $menu->slug }}</p>
                    </div>
                    <button onclick="event.preventDefault(); toggleMenu({{ $menu->id }})"
                        class="relative inline-flex h-6 w-11 shrink-0 items-center rounded-full transition-colors
                            {{ $menu->is_enabled ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600' }}">
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform
                            {{ $menu->is_enabled ? 'translate-x-6' : 'translate-x-1' }}"></span>
                    </button>
                </div>
            </a>
        @endforeach
    </div>

    @push('scripts')
    <script type="text/turbo-script">
        function toggleMenu(id) {
            fetch(`/admin/navigations/${id}/toggle`, {
                method: 'PATCH',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
            }).then(r => r.json()).then(() => Turbo.visit(location.href, { action: 'replace' }));
        }
    </script>
    @endpush
@endsection
