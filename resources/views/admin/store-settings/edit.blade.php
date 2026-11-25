@extends('admin.layouts.app')

@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Store Settings', 'url' => null],
    ];
@endphp

@section('content')
    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-800 dark:text-white">Store Settings</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage your store's contact information and social links. These values are used across the header, footer and contact pages.</p>
    </div>

    <form action="{{ route('admin.store-settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div x-data="socialEditor(@json($socials))" class="grid grid-cols-1 gap-6 max-w-5xl">

            {{-- Store / Contact Information --}}
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Store Information</h3>
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Store Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $values['name']) }}"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    </div>
                    <div>
                        <label for="email" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $values['email']) }}"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    </div>
                    <div>
                        <label for="phone" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Phone Number</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $values['phone']) }}"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    </div>
                    <div>
                        <label for="business_hours" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Business Hours</label>
                        <input type="text" name="business_hours" id="business_hours" value="{{ old('business_hours', $values['business_hours']) }}"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                            placeholder="e.g. Mon-Fri, 9am-6pm">
                    </div>
                    <div class="md:col-span-2">
                        <label for="address" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Address</label>
                        <input type="text" name="address" id="address" value="{{ old('address', $values['address']) }}"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                            placeholder="123 Fashion Street, Style City, SC 12345">
                    </div>
                    <div class="md:col-span-2">
                        <label for="description" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Store Description</label>
                        <textarea name="description" id="description" rows="3"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">{{ old('description', $values['description']) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Social Links --}}
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Social Links</h3>
                        <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">Links shown in the website footer.</p>
                    </div>
                    <button type="button" @click="add()" class="inline-flex items-center gap-1.5 rounded-lg bg-brand-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-brand-600">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Add Social Link
                    </button>
                </div>

                <template x-if="socials.length === 0">
                    <p class="text-sm text-gray-400 dark:text-gray-500 italic">No social links configured.</p>
                </template>

                <template x-for="(social, index) in socials" :key="index">
                    <div class="mb-3 flex flex-col gap-3 rounded-lg border border-gray-200 p-3 dark:border-gray-700 sm:flex-row sm:items-center">
                        <div class="sm:w-44">
                            <select x-model="social.platform" :name="'socials[' + index + '][platform]'"
                                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                <option value="">Select platform</option>
                                @foreach ($platforms as $key => $label)
                                    <option value="{{ $key }}" :selected="social.platform === '{{ $key }}'">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-1">
                            <input type="url" x-model="social.url" :name="'socials[' + index + '][url]'"
                                placeholder="https://"
                                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 focus:border-brand-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        </div>
                        <button type="button" @click="remove(index)" class="text-red-500 hover:text-red-600 text-sm font-medium">Remove</button>
                    </div>
                </template>
            </div>

            <div>
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7"/></svg>
                    Save Store Settings
                </button>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script type="text/turbo-script">
    {
        document.addEventListener('alpine:init', () => {
            Alpine.data('socialEditor', (initial) => ({
                socials: (initial || []).map(s => ({ platform: s.platform || '', url: s.url || '' })),
                add() {
                    this.socials.push({ platform: '', url: '' });
                },
                remove(index) {
                    this.socials.splice(index, 1);
                },
            }));
        });
    }
    </script>
@endpush
