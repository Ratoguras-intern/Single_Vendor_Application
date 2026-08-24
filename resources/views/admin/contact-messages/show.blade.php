@extends('admin.layouts.app')

@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Contact Messages', 'url' => route('admin.contact-messages.index')],
        ['label' => \Illuminate\Support\Str::limit($message->subject, 40), 'url' => null],
    ];
@endphp

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-800 dark:text-white">Message Details</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Received {{ $message->created_at?->format('M j, Y g:i A') }}</p>
        </div>
        <a href="{{ route('admin.contact-messages.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
            Back to Inbox
        </a>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03] max-w-3xl">
        <div class="flex flex-wrap items-start justify-between gap-4 pb-4 border-b border-gray-200 dark:border-gray-800">
            <div>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">{{ $message->subject }}</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    From <span class="font-medium text-gray-700 dark:text-gray-300">{{ $message->name }}</span>
                    &lt;<a href="mailto:{{ $message->email }}" class="text-brand-500 hover:text-brand-600 dark:text-brand-400">{{ $message->email }}</a>&gt;
                </p>
            </div>
            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                {{ $message->status === 'new'
                    ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'
                    : '' }}
                {{ $message->status === 'read'
                    ? 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400'
                    : '' }}
                {{ $message->status === 'replied'
                    ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400'
                    : '' }}">
                {{ ucfirst($message->status) }}
            </span>
        </div>

        <div class="py-6">
            <p class="whitespace-pre-line text-sm leading-relaxed text-gray-700 dark:text-gray-300">{{ $message->message }}</p>
        </div>

        <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-800">
            <a href="mailto:{{ $message->email }}?subject=Re: {{ rawurlencode($message->subject) }}"
                class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                Reply via Email
            </a>
            <form action="{{ route('admin.contact-messages.destroy', $message) }}" method="POST" x-data @submit.prevent="$store.confirmModal.open({ title: 'Delete Message', message: 'Are you sure you want to delete this message?', form: $el })">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-red-200 bg-white px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 dark:border-red-800 dark:bg-transparent dark:text-red-400 dark:hover:bg-red-900/20">
                    Delete
                </button>
            </form>
        </div>
    </div>
@endsection
