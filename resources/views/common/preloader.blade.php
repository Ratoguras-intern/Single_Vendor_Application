<div id="page-preloader" x-data="{ 'loaded': true }" x-init="$nextTick(() => {
    if (window.__preloaderSkip) {
        loaded = false;
        return;
    }
    const hide = () => { loaded = false; window.removeEventListener('load', hide); };
    if (document.readyState === 'complete') {
        hide();
    } else {
        window.addEventListener('load', hide);
    }
    setTimeout(hide, 4000);
})"
    x-show="loaded"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-[99999] flex items-center justify-center bg-white dark:bg-gray-900">
    <div class="h-10 w-10 animate-spin rounded-full border-4 border-brand-500 border-t-transparent"></div>
</div>
