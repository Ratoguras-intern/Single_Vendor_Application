@php
    $toastMessages = [];
    foreach (['success', 'error', 'warning', 'info'] as $type) {
        if (session($type)) {
            $toastMessages[] = ['type' => $type, 'message' => session($type)];
        }
    }
@endphp

@if (count($toastMessages) > 0)
<div x-data="toastNotifications()" x-init="init()" class="fixed top-5 right-5 z-[100001] flex flex-col gap-3 max-w-sm w-full pointer-events-none">
    @foreach ($toastMessages as $toast)
        <div
            x-data="{ show: false }"
            x-init="$nextTick(() => { show = true; setTimeout(() => close($el), 5000) })"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-x-8"
            x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-x-0"
            x-transition:leave-end="opacity-0 translate-x-8"
            class="pointer-events-auto flex items-start gap-3 rounded-lg border p-4 shadow-lg backdrop-blur-sm
                {{ $toast['type'] === 'success' ? 'border-green-200 bg-green-50 dark:border-green-800 dark:bg-green-900/30' : '' }}
                {{ $toast['type'] === 'error' ? 'border-red-200 bg-red-50 dark:border-red-800 dark:bg-red-900/30' : '' }}
                {{ $toast['type'] === 'warning' ? 'border-yellow-200 bg-yellow-50 dark:border-yellow-800 dark:bg-yellow-900/30' : '' }}
                {{ $toast['type'] === 'info' ? 'border-blue-200 bg-blue-50 dark:border-blue-800 dark:bg-blue-900/30' : '' }}"
        >
            {{-- Icon --}}
            @if ($toast['type'] === 'success')
                <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-green-500 text-white mt-0.5">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7"/></svg>
                </div>
            @elseif ($toast['type'] === 'error')
                <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-red-500 text-white mt-0.5">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6L6 18M6 6l12 12"/></svg>
                </div>
            @elseif ($toast['type'] === 'warning')
                <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-yellow-500 text-white mt-0.5">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v4M12 17h.01"/></svg>
                </div>
            @else
                <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-blue-500 text-white mt-0.5">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                </div>
            @endif

            {{-- Message --}}
            <p class="flex-1 text-sm font-medium
                {{ $toast['type'] === 'success' ? 'text-green-800 dark:text-green-300' : '' }}
                {{ $toast['type'] === 'error' ? 'text-red-800 dark:text-red-300' : '' }}
                {{ $toast['type'] === 'warning' ? 'text-yellow-800 dark:text-yellow-300' : '' }}
                {{ $toast['type'] === 'info' ? 'text-blue-800 dark:text-blue-300' : '' }}">
                {{ $toast['message'] }}
            </p>

            {{-- Close button --}}
            <button @click="close($el.closest('[x-data]'))" class="shrink-0
                {{ $toast['type'] === 'success' ? 'text-green-500 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300' : '' }}
                {{ $toast['type'] === 'error' ? 'text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300' : '' }}
                {{ $toast['type'] === 'warning' ? 'text-yellow-500 hover:text-yellow-700 dark:text-yellow-400 dark:hover:text-yellow-300' : '' }}
                {{ $toast['type'] === 'info' ? 'text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300' : '' }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>
    @endforeach
</div>

<script>
function toastNotifications() {
    return {
        init() {},
        close(el) {
            if (el) {
                el.__x = el._x_dataStack?.[0] || el.__x;
                if (el._x_dataStack) {
                    el._x_dataStack[0].show = false;
                }
                setTimeout(() => el.remove(), 300);
            }
        }
    };
}
</script>
@endif
