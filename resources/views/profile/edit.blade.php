<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-secondary-900 dark:text-white">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="section space-y-6">
        <div class="card">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="card">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="card">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
