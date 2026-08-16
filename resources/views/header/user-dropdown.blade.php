<div x-data="{ open: false }" class="relative">
    <button @click="open = !open"
        class="flex items-center gap-3 text-sm font-medium text-gray-700 dark:text-gray-400">
        <span class="hidden text-right lg:block">
            <span class="block text-sm font-semibold text-gray-800 dark:text-white">{{ Auth::user()->name ?? 'Admin' }}</span>
            <span class="block text-xs text-gray-500 dark:text-gray-400">{{ Str::headline(Auth::user()->role ?? 'admin') }}</span>
        </span>
        <svg class="hidden sm:block" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M6 9L12 15L18 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </button>

    <div x-show="open" @click.away="open = false" x-transition
        class="absolute right-0 mt-2 w-56 rounded-lg border border-gray-200 bg-white py-2 shadow-lg dark:border-gray-800 dark:bg-gray-900 z-50">
        <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-800">
            <p class="text-sm font-semibold text-gray-800 dark:text-white">{{ Auth::user()->name ?? 'Admin' }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email ?? '' }}</p>
        </div>
        <div class="py-1">
            <a href="{{ route('profile.edit') }}" data-turbo="false" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-white/[0.03]">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M20 21V19C20 17.9391 19.5786 16.9217 18.8284 16.1716C18.0783 15.4214 17.0609 15 16 15H8C6.93913 15 5.92172 15.4214 5.17157 16.1716C4.42143 16.9217 4 17.9391 4 19V21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/><circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="1.5"/></svg>
                Profile
            </a>
            <form method="POST" action="{{ route('logout') }}" data-turbo="false">
                @csrf
                <button type="submit" class="flex items-center gap-2 w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-white/[0.03]">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M9 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M16 17L21 12L16 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M21 12H9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>
