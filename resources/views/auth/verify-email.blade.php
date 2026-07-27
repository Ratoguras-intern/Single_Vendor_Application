<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-bold text-secondary-900">Verify email</h1>
        <p class="text-sm text-secondary-500 mt-1">Thanks for signing up! Please verify your email address by clicking the link we sent you.</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 p-3 rounded-card bg-green-50 border border-green-200 text-sm text-green-700 font-medium">
            {{ __('A new verification link has been sent to your email.') }}
        </div>
    @endif

    <div class="space-y-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn-primary w-full">
                {{ __('Resend Verification Email') }}
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-ghost w-full text-secondary-500 hover:text-secondary-900">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
