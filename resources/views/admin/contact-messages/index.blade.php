@extends('admin.layouts.app')

@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Contact Messages', 'url' => null],
    ];
@endphp

@section('content')
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white">Contact Messages</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Inbox for messages submitted through the contact form.</p>
    </div>

    <div class="flex flex-wrap items-center gap-2 mb-6">
        <a href="{{ route('admin.contact-messages.index') }}"
            class="inline-flex items-center gap-2 rounded-lg px-3.5 py-2 text-sm font-medium
            {{ request('status') ? 'border border-gray-300 bg-white text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300' : 'bg-brand-500 text-white' }}">
            All
        </a>
        @foreach (['new' => 'New', 'read' => 'Read', 'replied' => 'Replied'] as $value => $label)
            <a href="{{ route('admin.contact-messages.index', ['status' => $value]) }}"
                class="inline-flex items-center gap-2 rounded-lg px-3.5 py-2 text-sm font-medium
                {{ request('status') === $value ? 'bg-brand-500 text-white' : 'border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                {{ $label }}
            </a>
        @endforeach
        @if (isset($newCount) && $newCount > 0)
            <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-semibold text-red-700 dark:bg-red-900/30 dark:text-red-400">
                {{ $newCount }} new
            </span>
        @endif
    </div>

    <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-800">
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">From</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Email</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Subject</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Received</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Status</th>
                        <th class="px-5 py-3 text-right text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse($messages as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                            <td class="px-5 py-4">
                                <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $item->name }}</span>
                                @if ($item->status === 'new')
                                    <span class="ml-1.5 inline-block h-2 w-2 rounded-full bg-brand-500"></span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $item->email }}</td>
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400 max-w-xs truncate">{{ \Illuminate\Support\Str::limit($item->subject, 60) }}</td>
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $item->created_at?->format('M j, Y g:i A') }}</td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                    {{ $item->status === 'new'
                                        ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'
                                        : '' }}
                                    {{ $item->status === 'read'
                                        ? 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400'
                                        : '' }}
                                    {{ $item->status === 'replied'
                                        ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400'
                                        : '' }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.contact-messages.show', $item) }}" title="View" class="text-brand-500 hover:text-brand-600 dark:text-brand-400 dark:hover:text-brand-300">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M1.5 12S5 5 12 5s10.5 7 10.5 7-3.5 7-10.5 7S1.5 12 1.5 12Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.5"/></svg>
                                    </a>
                                    <form action="{{ route('admin.contact-messages.destroy', $item) }}" method="POST" x-data @submit.prevent="$store.confirmModal.open({ title: 'Delete Message', message: 'Are you sure you want to delete this message?', form: $el })">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-600 dark:hover:text-red-400">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M3 6H21M19 6V20C19 21.1 18.1 22 17 22H7C5.9 22 5 21.1 5 20V6M8 6V4C8 2.9 8.9 2 10 2H14C15.1 2 16 2.9 16 4V6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center">
                                <p class="text-sm font-medium text-gray-800 dark:text-white mb-1">No messages found</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Contact form submissions will appear here.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 border-t border-gray-200 dark:border-gray-800">
            {{ $messages->links() }}
        </div>
    </div>
@endsection
