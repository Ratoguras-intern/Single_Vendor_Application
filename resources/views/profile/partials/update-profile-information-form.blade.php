<section>
    <header>
        <h2 class="text-lg font-semibold text-secondary-900 dark:text-white">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-secondary-500 dark:text-secondary-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-4" enctype="multipart/form-data"
        x-data="{
            preview: null,
            saving: false,
            saved: false,
            errors: [],
            removedLocal: false,
            hasAvatar: {{ $user->avatar_path ? 'true' : 'false' }},
            avatarUrl: @js($user->avatarUrl()),
            async save(e) {
                e.preventDefault();
                this.errors = [];
                this.saved = false;
                this.saving = true;

                try {
                    const meta = document.querySelector('meta[name=csrf-token]');
                    const res = await fetch(e.target.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': meta ? meta.content : '',
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        body: new FormData(e.target),
                    });

                    if (res.status === 419) {
                        this.errors = ['Your session expired. Please refresh the page and try again.'];
                        return;
                    }

                    if (res.status === 422) {
                        const data = await res.json();
                        this.errors = Object.values(data.errors || {}).flat();
                        return;
                    }

                    const ct = res.headers.get('content-type') || '';
                    if (!res.ok || !ct.includes('application/json')) {
                        throw new Error('Server returned ' + res.status);
                    }

                    const data = await res.json();

                    this.hasAvatar = !!data.avatar_url;
                    this.removedLocal = false;
                    let broadcastUrl = null;
                    if (data.avatar_url) {
                        broadcastUrl = data.avatar_url + '?t=' + Date.now();
                        this.avatarUrl = broadcastUrl;
                    }
                    this.preview = null;
                    const inp = e.target.querySelector('#avatar');
                    if (inp) inp.value = '';
                    const rm = e.target.querySelector('#remove_avatar');
                    if (rm) rm.value = '';

                    window.dispatchEvent(new CustomEvent('avatar-updated', { detail: { url: broadcastUrl } }));

                    this.saved = true;
                    setTimeout(() => this.saved = false, 2500);
                } catch (err) {
                    console.error('Profile save failed:', err);
                    this.errors = [err && err.message ? err.message : 'Something went wrong. Please try again.'];
                } finally {
                    this.saving = false;
                }
            },
            pickFile(e) {
                if (e.target.files[0]) {
                    this.preview = URL.createObjectURL(e.target.files[0]);
                    this.removedLocal = false;
                    const rm = document.getElementById('remove_avatar');
                    if (rm) rm.value = '';
                }
            },
            clearPhoto() {
                this.preview = null;
                this.removedLocal = true;
                const inp = document.getElementById('avatar');
                if (inp) inp.value = '';
                const rm = document.getElementById('remove_avatar');
                if (rm) rm.value = '1';
            },
        }"
        @submit.prevent="save($event)">
        @csrf
        @method('patch')

        {{-- Avatar --}}
        <div class="flex items-center gap-4 pb-4 border-b border-secondary-100 dark:border-secondary-800">
            <div class="relative shrink-0">
                <img x-show="(hasAvatar && !removedLocal) || preview"
                    :src="preview || avatarUrl"
                    alt="{{ $user->name }}"
                    class="h-16 w-16 rounded-full object-cover ring-2 ring-primary-500/20 shadow-sm">
                <div x-show="!((hasAvatar && !removedLocal) || preview)"
                    class="h-16 w-16 rounded-full bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center text-white text-xl font-bold shadow-sm">
                    {{ $user->initials() }}
                </div>
            </div>

            <div class="flex-1 min-w-0 space-y-2">
                <label for="avatar"
                    class="inline-flex items-center gap-1.5 cursor-pointer rounded-lg border border-secondary-300 dark:border-secondary-600 bg-white dark:bg-secondary-800 px-3 py-1.5 text-xs font-medium text-secondary-700 dark:text-secondary-200 hover:border-primary-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0Zm-4.5 0h.008v.008H12v-.008Z"/></svg>
                    <span x-text="(hasAvatar && !removedLocal) || preview ? 'Change Photo' : 'Upload Photo'">{{ $user->avatar_path ? 'Change Photo' : 'Upload Photo' }}</span>
                </label>
                <input type="file" id="avatar" name="avatar" accept="image/png,image/jpeg,image/webp"
                    class="hidden" x-on:change="pickFile($event)">
                <x-input-error class="block" :messages="$errors->get('avatar')" />

                <button type="button" x-show="hasAvatar && !removedLocal" x-cloak
                    x-on:click="clearPhoto()"
                    class="block text-xs font-medium text-red-500 hover:text-red-600 transition-colors">Remove photo</button>
                <input type="hidden" name="remove_avatar" id="remove_avatar" value="">
                <p class="text-[11px] text-secondary-400 dark:text-secondary-500">JPG, PNG or WebP, up to 8MB — auto-cropped and optimized.</p>
            </div>
        </div>

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-secondary-600 dark:text-secondary-400">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="text-sm text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm text-green-600 dark:text-green-400 font-medium">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Inline feedback --}}
        <div x-show="errors.length" x-cloak class="rounded-lg border border-red-200 bg-red-50 dark:border-red-800 dark:bg-red-900/20 p-3">
            <ul class="list-disc list-inside space-y-0.5">
                <template x-for="error in errors" :key="error">
                    <li class="text-xs text-red-600 dark:text-red-400" x-text="error"></li>
                </template>
            </ul>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button x-bind:disabled="saving">
                <span x-show="!saving">{{ __('Save') }}</span>
                <span x-show="saving" x-cloak>{{ __('Saving...') }}</span>
            </x-primary-button>

            <span x-show="saved" x-transition x-cloak
                class="inline-flex items-center gap-1 text-sm text-green-600 dark:text-green-400 font-medium">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                {{ __('Saved.') }}
            </span>

            @if (session('status') === 'profile-updated')
                <span
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-secondary-500 dark:text-secondary-400"
                >{{ __('Saved.') }}</span>
            @endif
        </div>
    </form>
</section>
