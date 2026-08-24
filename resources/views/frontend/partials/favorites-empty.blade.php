{{-- Favorites empty state (shared by server-side + client-side removal paths) --}}
<div class="py-10 sm:py-16 text-center max-w-md mx-auto px-2">
    <div class="relative w-[72px] h-[72px] mx-auto mb-5">
        <div class="absolute inset-0 rounded-full bg-primary-500/[0.08] dark:bg-primary-400/10"></div>
        <div class="absolute inset-[7px] rounded-full bg-white dark:bg-secondary-800 shadow-sm border border-secondary-100 dark:border-white/10 flex items-center justify-center">
            <svg class="h-7 w-7 text-primary-500/80" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/></svg>
        </div>
    </div>
    <h2 class="text-lg sm:text-xl font-bold text-secondary-900 dark:text-white mb-1.5" data-i18n="Your favorites list is empty" x-text="$store.i18n.t('Your favorites list is empty')">{{ __('Your favorites list is empty') }}</h2>
    <p class="text-sm text-secondary-500 dark:text-secondary-400 mb-6 leading-relaxed" data-i18n="Save products you love and find them here anytime." x-text="$store.i18n.t('Save products you love and find them here anytime.')">{{ __('Save products you love and find them here anytime.') }}</p>
    <a href="{{ route('frontend.shop') }}" class="btn-primary inline-flex items-center justify-center min-h-[44px] px-7">
        <span data-i18n="Continue Shopping" x-text="$store.i18n.t('Continue Shopping')">{{ __('Continue Shopping') }}</span>
    </a>
</div>
