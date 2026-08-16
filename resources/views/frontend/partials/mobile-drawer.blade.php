@php
    $topCategories = $categories->whereNull('parent_id');
@endphp

{{-- Mobile Drawer (slide-out from left) --}}
<template x-teleport="body">
    {{-- Overlay --}}
    <div x-show="mobileOpen"
        x-transition:enter="transition-opacity duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-on:click="mobileOpen = false"
        x-effect="document.body.style.overflow = mobileOpen ? 'hidden' : ''"
        class="mobile-drawer-overlay"
        style="display: none;"
        aria-hidden="true">
    </div>
</template>

<template x-teleport="body">
    {{-- Panel --}}
    <div x-show="mobileOpen"
        x-transition:enter="transition-transform duration-300 ease-out"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition-transform duration-200 ease-in"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="mobile-drawer-panel"
        style="display: none;"
        role="dialog"
        aria-label="Navigation menu">

        {{-- Drawer Header --}}
        <div class="flex items-center justify-between px-5 py-4 border-b border-secondary-100 dark:border-secondary-700">
            <x-brand-logo compact :show-text="true" />
            <button x-on:click="mobileOpen = false"
                class="p-2 rounded-full hover:bg-secondary-100 dark:hover:bg-white/5 transition-colors text-secondary-500 dark:text-secondary-400"
                aria-label="Close menu">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Search --}}
        <div class="px-5 py-4 border-b border-secondary-100 dark:border-secondary-700">
            <form action="{{ route('frontend.shop') }}" method="GET" class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-secondary-400 dark:text-secondary-500 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <circle cx="11" cy="11" r="8"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35"/>
                </svg>
                <input type="search" name="search" placeholder="Search products..."
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-secondary-200 dark:border-secondary-600 bg-secondary-50 dark:bg-secondary-900 text-sm text-secondary-900 dark:text-white placeholder:text-secondary-400 dark:placeholder:text-secondary-500 focus:border-primary-500 dark:focus:border-primary-400 focus:bg-white dark:focus:bg-secondary-800 transition-all"
                    aria-label="Search products">
            </form>
        </div>

        {{-- Nav Links --}}
        <div class="px-3 py-3 border-b border-secondary-100 dark:border-secondary-700">
            <a href="{{ route('frontend.home') }}" wire:navigate x-on:click="mobileOpen = false"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('frontend.home') ? 'bg-primary-50 text-primary-700 dark:bg-primary-500/10 dark:text-primary-400' : 'text-secondary-700 dark:text-secondary-300 hover:bg-secondary-50 dark:hover:bg-white/5' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                </svg>
                Home
            </a>
            <a href="{{ route('frontend.shop') }}" wire:navigate x-on:click="mobileOpen = false"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('frontend.shop') ? 'bg-primary-50 text-primary-700 dark:bg-primary-500/10 dark:text-primary-400' : 'text-secondary-700 dark:text-secondary-300 hover:bg-secondary-50 dark:hover:bg-white/5' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                </svg>
                Shop
            </a>
            <a href="{{ route('frontend.shop') }}?sort=newest" wire:navigate x-on:click="mobileOpen = false"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors text-secondary-700 dark:text-secondary-300 hover:bg-secondary-50 dark:hover:bg-white/5">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                New Arrivals
            </a>
            <a href="{{ route('frontend.shop') }}?sort=sale" wire:navigate x-on:click="mobileOpen = false"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors text-red-500 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 0 0-2.455 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z"/>
                </svg>
                Sale
            </a>
            <a href="{{ route('frontend.favorites') }}" wire:navigate x-on:click="mobileOpen = false"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('frontend.favorites') ? 'bg-primary-50 text-primary-700 dark:bg-primary-500/10 dark:text-primary-400' : 'text-secondary-700 dark:text-secondary-300 hover:bg-secondary-50 dark:hover:bg-white/5' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                </svg>
                Wishlist
            </a>
            <a href="{{ route('frontend.cart') }}" wire:navigate x-on:click="mobileOpen = false"
                class="flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('frontend.cart') ? 'bg-primary-50 text-primary-700 dark:bg-primary-500/10 dark:text-primary-400' : 'text-secondary-700 dark:text-secondary-300 hover:bg-secondary-50 dark:hover:bg-white/5' }}">
                <span class="flex items-center gap-3">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75-12.75 2.14 2.14"/>
                    </svg>
                    Cart
                </span>
                <span x-show="$store.cart.count() > 0" x-text="$store.cart.count()" x-transition
                    class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 rounded-full bg-primary-500 text-white text-[10px] font-bold"></span>
            </a>
            <a href="{{ route('frontend.contact') }}" wire:navigate x-on:click="mobileOpen = false"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('frontend.contact') ? 'bg-primary-50 text-primary-700 dark:bg-primary-500/10 dark:text-primary-400' : 'text-secondary-700 dark:text-secondary-300 hover:bg-secondary-50 dark:hover:bg-white/5' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                </svg>
                Contact
            </a>
            <a href="{{ route('frontend.about') }}" wire:navigate x-on:click="mobileOpen = false"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('frontend.about') ? 'bg-primary-50 text-primary-700 dark:bg-primary-500/10 dark:text-primary-400' : 'text-secondary-700 dark:text-secondary-300 hover:bg-secondary-50 dark:hover:bg-white/5' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>
                </svg>
                About
            </a>
        </div>

        {{-- Categories: Recursive Accordion --}}
        <div class="px-3 py-3 border-b border-secondary-100 dark:border-secondary-700">
            <button x-on:click="mobileCategoriesOpen = !mobileCategoriesOpen"
                class="flex items-center justify-between w-full px-3 py-2.5 rounded-lg text-sm font-medium text-secondary-700 dark:text-secondary-300 hover:bg-secondary-50 dark:hover:bg-white/5 transition-colors">
                <span class="flex items-center gap-3">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25a2.25 2.25 0 0 1-2.25-2.25v-2.25Z"/>
                    </svg>
                    Categories
                </span>
                <svg class="h-4 w-4 text-secondary-400 transition-transform duration-200" :class="mobileCategoriesOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                </svg>
            </button>
            <div x-show="mobileCategoriesOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1" class="mt-1 ml-6 space-y-0.5">
                @forelse($topCategories as $cat)
                    @php $activeCategory = null; @endphp
                    @if(request()->routeIs('frontend.category'))
                        @php $activeCategory = $categories->firstWhere('slug', request()->route('slug')); @endphp
                    @endif
                    @include('frontend.partials.category-tree', [
                        'categories' => collect([$cat]),
                        'level' => 0,
                        'context' => 'mobile',
                        'activeCategory' => $activeCategory,
                    ])
                @empty
                    <p class="px-3 py-2 text-sm text-secondary-400 dark:text-secondary-500">No categories</p>
                @endforelse
            </div>
        </div>

        {{-- Account --}}
        <div class="px-3 py-3 border-b border-secondary-100 dark:border-secondary-700">
            @auth
                <div class="flex items-center gap-3 px-3 py-2.5 mb-2">
                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center text-white text-sm font-bold shadow-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-secondary-900 dark:text-white">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-secondary-500 dark:text-secondary-400">{{ auth()->user()->email }}</p>
                    </div>
                </div>

                @if (auth()->user()->role === 'customer')
                    <a href="{{ route('customer.orders.index') }}" wire:navigate x-on:click="mobileOpen = false"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-secondary-700 dark:text-secondary-300 hover:bg-secondary-50 dark:hover:bg-white/5 transition-colors">
                        <svg class="h-5 w-5 text-secondary-400 dark:text-secondary-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15a2.25 2.25 0 0 1 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z"/>
                        </svg>
                        My Orders
                    </a>
                @endif
                <a href="{{ route('frontend.favorites') }}" wire:navigate x-on:click="mobileOpen = false"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-secondary-700 dark:text-secondary-300 hover:bg-secondary-50 dark:hover:bg-white/5 transition-colors">
                    <svg class="h-5 w-5 text-secondary-400 dark:text-secondary-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                    </svg>
                    My Favorites
                </a>
                @if (auth()->user()->role === 'super_admin')
                    <a href="{{ route('superadmin.dashboard') }}" x-on:click="mobileOpen = false"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-secondary-700 dark:text-secondary-300 hover:bg-secondary-50 dark:hover:bg-white/5 transition-colors">
                        <svg class="h-5 w-5 text-secondary-400 dark:text-secondary-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18m-9-9h18"/>
                        </svg>
                        Super Admin Panel
                    </a>
                @endif
                @if (in_array(auth()->user()->role, ['admin', 'super_admin']))
                    <a href="{{ route('admin.dashboard') }}" x-on:click="mobileOpen = false"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-secondary-700 dark:text-secondary-300 hover:bg-secondary-50 dark:hover:bg-white/5 transition-colors">
                        <svg class="h-5 w-5 text-secondary-400 dark:text-secondary-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 0 1 1.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.56.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.893.149c-.425.07-.765.383-.93.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 0 1-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.397.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 0 1-.12-1.45l.527-.737c.25-.35.273-.806.108-1.204-.165-.397-.505-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854.108-1.204l.526-.738a1.125 1.125 0 0 1 .12-1.45l.773-.773a1.125 1.125 0 0 1 1.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                        </svg>
                        Admin Panel
                    </a>
                @endif
                <a href="{{ route('profile.edit') }}" wire:navigate x-on:click="mobileOpen = false"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-secondary-700 dark:text-secondary-300 hover:bg-secondary-50 dark:hover:bg-white/5 transition-colors">
                    <svg class="h-5 w-5 text-secondary-400 dark:text-secondary-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                    </svg>
                    Profile
                </a>
                <div class="mx-3 my-2 border-t border-secondary-100 dark:border-secondary-700"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" x-on:click="mobileOpen = false"
                        class="flex items-center gap-3 w-full px-3 py-2.5 rounded-lg text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15"/>
                        </svg>
                        Sign Out
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" x-on:click="mobileOpen = false"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-secondary-700 dark:text-secondary-300 hover:bg-secondary-50 dark:hover:bg-white/5 transition-colors">
                    <svg class="h-5 w-5 text-secondary-400 dark:text-secondary-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/>
                    </svg>
                    Sign In
                </a>
                <a href="{{ route('register') }}" x-on:click="mobileOpen = false"
                    class="flex items-center justify-center gap-2 mt-2 px-4 py-2.5 rounded-btn bg-primary-500 text-white text-sm font-semibold hover:bg-primary-600 transition-colors">
                    Create Account
                </a>
            @endauth
        </div>

        {{-- Bottom Utilities --}}
        <div class="px-3 py-4">
            <button x-on:click="$store.theme.toggle(); mobileOpen = false"
                class="flex items-center gap-3 w-full px-3 py-2.5 rounded-lg text-sm text-secondary-700 dark:text-secondary-300 hover:bg-secondary-50 dark:hover:bg-white/5 transition-colors">
                <svg x-show="$store.theme.current === 'light'" class="h-5 w-5 text-secondary-400 dark:text-secondary-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/>
                </svg>
                <svg x-show="$store.theme.current === 'dark'" class="h-5 w-5 text-secondary-400 dark:text-secondary-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="display: none;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/>
                </svg>
                <span x-text="$store.theme.current === 'light' ? 'Dark Mode' : 'Light Mode'"></span>
            </button>

            <div class="flex items-center gap-2 px-3 py-2.5">
                <svg class="h-5 w-5 text-secondary-400 dark:text-secondary-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5a17.92 17.92 0 0 1-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418"/>
                </svg>
                <div class="flex gap-1">
                    <button x-on:click="$store.i18n.setLocale('en')"
                        class="px-2.5 py-1 rounded text-xs font-semibold transition-colors"
                        :class="$store.i18n.locale === 'en' ? 'bg-primary-500 text-white' : 'bg-secondary-100 dark:bg-white/5 text-secondary-600 dark:text-secondary-400'">EN</button>
                    <button x-on:click="$store.i18n.setLocale('ja')"
                        class="px-2.5 py-1 rounded text-xs font-semibold transition-colors"
                        :class="$store.i18n.locale === 'ja' ? 'bg-primary-500 text-white' : 'bg-secondary-100 dark:bg-white/5 text-secondary-600 dark:text-secondary-400'">JA</button>
                    <button x-on:click="$store.i18n.setLocale('ne')"
                        class="px-2.5 py-1 rounded text-xs font-semibold transition-colors"
                        :class="$store.i18n.locale === 'ne' ? 'bg-primary-500 text-white' : 'bg-secondary-100 dark:bg-white/5 text-secondary-600 dark:text-secondary-400'">NE</button>
                </div>
            </div>

            <div class="flex items-center gap-2 px-3 py-2.5">
                <svg class="h-5 w-5 text-secondary-400 dark:text-secondary-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                <div class="flex gap-1" x-data>
                    <template x-for="(config, code) in window.currencyConfig" :key="code">
                        <button x-on:click="$store.currency.set(code)"
                            class="px-2.5 py-1 rounded text-xs font-semibold transition-colors"
                            :class="$store.currency.code === code ? 'bg-primary-500 text-white' : 'bg-secondary-100 dark:bg-white/5 text-secondary-600 dark:text-secondary-400'"
                            x-text="config.symbol + code"></button>
                    </template>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="px-5 py-4 border-t border-secondary-100 dark:border-secondary-700 mt-auto">
            <p class="text-xs text-secondary-400 dark:text-secondary-500 text-center">&copy; {{ date('Y') }} {{ config('app.name', 'Your Brand') }}. All rights reserved.</p>
        </div>
    </div>
</template>
