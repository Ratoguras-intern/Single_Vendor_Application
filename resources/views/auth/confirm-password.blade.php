<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-bold text-secondary-900">Confirm password</h1>
        <p class="text-sm text-secondary-500 mt-1">This is a secure area. Please confirm your password before continuing.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="space-y-4">
            <div>
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <button type="submit" class="btn-primary w-full">
                {{ __('Confirm') }}
            </button>
        </div>
    </form>
</x-guest-layout>
