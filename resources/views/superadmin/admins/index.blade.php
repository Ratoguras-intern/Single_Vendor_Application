@extends('admin.layouts.app')

@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('superadmin.dashboard')],
        ['label' => 'Admins', 'url' => null],
    ];
@endphp

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white">Admins</h2>
        <a href="{{ route('superadmin.admins.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600 transition-colors">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5V19M5 12H19"/></svg>
            Add Admin
        </a>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-800">
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Admin</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Email</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Role</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Joined</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse ($admins as $admin)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] {{ $admin->is_frozen ? 'bg-red-50/50 dark:bg-red-500/5' : '' }}">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-full flex items-center justify-center text-sm font-bold text-white {{ $admin->role === 'super_admin' ? 'bg-brand-500' : 'bg-gray-400' }}">
                                        {{ strtoupper(substr($admin->name, 0, 1)) }}
                                    </div>
                                    <span class="text-sm font-medium text-gray-800 dark:text-white">
                                        {{ $admin->name }}
                                        @if ($admin->id === Auth::id())
                                            <span class="ml-1 text-xs text-gray-400 dark:text-gray-500">(you)</span>
                                        @endif
                                        @if ($admin->is_frozen)
                                            <span class="ml-1.5 inline-flex items-center gap-1 rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700 dark:bg-red-500/10 dark:text-red-400">
                                                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="2" x2="22" y1="12" y2="12"/><path d="m20 16-4-4 4-4"/><path d="m4 8 4 4-4 4"/><path d="m16 4-4 4-4-4"/><path d="m8 20 4-4-4-4"/><line x1="12" x2="12" y1="2" y2="22"/></svg>
                                                Frozen
                                            </span>
                                        @endif
                                    </span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $admin->email }}</td>
                            <td class="px-5 py-4">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $admin->role === 'super_admin' ? 'bg-purple-50 text-purple-700 dark:bg-purple-500/10 dark:text-purple-400' : 'bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400' }}">
                                    {{ $admin->role === 'super_admin' ? 'Super Admin' : 'Admin' }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $admin->status === 'active' ? 'bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400' : 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400' }}">
                                    {{ ucfirst($admin->status) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $admin->created_at->format('M d, Y') }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('superadmin.admins.edit', $admin) }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" title="Edit Admin">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    </a>
                                    @if ($admin->id !== Auth::id())
                                        @if ($admin->is_frozen)
                                            <form action="{{ route('superadmin.admins.unfreeze', $admin) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-gray-400 hover:text-green-500 dark:hover:text-green-400" title="Unfreeze Admin">
                                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="2" x2="22" y1="12" y2="12"/><path d="m20 16-4-4 4-4"/><path d="m4 8 4 4-4 4"/><path d="m16 4-4 4-4-4"/><path d="m8 20 4-4-4-4"/><line x1="12" x2="12" y1="2" y2="22"/></svg>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('superadmin.admins.freeze', $admin) }}" method="POST" x-data="{ show: false }">
                                                @csrf
                                                <input type="hidden" name="frozen_reason" :value="reason || null">
                                                <button type="button" @click="show = !show" class="text-gray-400 hover:text-blue-500 dark:hover:text-blue-400" title="Freeze Admin">
                                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="2" x2="22" y1="12" y2="12"/><path d="m20 16-4-4 4-4"/><path d="m4 8 4 4-4 4"/><path d="m16 4-4 4-4-4"/><path d="m8 20 4-4-4-4"/><line x1="12" x2="12" y1="2" y2="22"/></svg>
                                                </button>
                                                <template x-if="show">
                                                    <div class="fixed inset-0 z-50 flex items-center justify-center" x-cloak>
                                                        <div class="fixed inset-0 bg-black/50" @click="show = false"></div>
                                                        <div x-transition class="relative z-10 w-full max-w-md rounded-xl border border-gray-200 bg-white p-6 shadow-2xl dark:border-gray-700 dark:bg-gray-900">
                                                            <h3 class="mb-1 text-lg font-semibold text-gray-800 dark:text-white">Freeze Admin</h3>
                                                            <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">Frozen admins cannot log in until unfrozen.</p>
                                                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Reason (optional)</label>
                                                            <input type="text" x-model="reason" maxlength="500" placeholder="Why freeze this admin?" class="mb-4 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                                                            <div class="flex justify-end gap-2">
                                                                <button type="button" @click="show = false" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800">Cancel</button>
                                                                <button type="submit" class="rounded-lg bg-red-500 px-4 py-2 text-sm font-medium text-white hover:bg-red-600">Freeze</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                            </form>
                                        @endif
                                        <form action="{{ route('superadmin.admins.toggleStatus', $admin) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-gray-400 hover:text-yellow-500 dark:hover:text-yellow-400" title="{{ $admin->status === 'active' ? 'Block Admin' : 'Activate Admin' }}">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                            </button>
                                        </form>
                                        <form action="{{ route('superadmin.admins.destroy', $admin) }}" method="POST" x-data @submit.prevent="$store.confirmModal.open({ title: 'Delete Admin', message: 'Are you sure you want to delete this admin?', form: $el })">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-500 dark:hover:text-red-400" title="Delete Admin">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6H21M8 6V4C8 3.44772 8.44772 3 9 3H15C15.5523 3 16 3.44772 16 4V6M19 6V20C19 21.1046 18.1046 22 17 22H7C5.89543 22 5 21.1046 5 20V6"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 mb-4">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-gray-400"><path d="M20 21V19C20 16.7909 18.2091 15 16 15H14"/><path d="M4 21V19C4 16.7909 5.79086 15 8 15H10"/><circle cx="12" cy="7" r="4"/></svg>
                                    </div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-white mb-1">No admins found</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Create your first admin to get started.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t border-gray-200 dark:border-gray-800">
            {{ $admins->links() }}
        </div>
    </div>
@endsection
