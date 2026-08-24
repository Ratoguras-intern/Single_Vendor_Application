@extends('admin.layouts.app')

@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Content Settings', 'url' => null],
    ];
@endphp

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-800 dark:text-white">Content Settings</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage support info, returns process, shipping details, and press contacts shown across the storefront.</p>
        </div>
    </div>

    <form action="{{ route('admin.content-settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 max-w-5xl">

            {{-- Contact Support Info --}}
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Contact Support Info</h3>
                <div class="space-y-5">
                    <div>
                        <label for="business_hours" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Business Hours</label>
                        <textarea name="business_hours" id="business_hours" rows="2"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('business_hours') border-red-500 @enderror"
                            placeholder="e.g. Mon-Fri, 9am-6pm">{{ old('business_hours', $values['business_hours']) }}</textarea>
                        @error('business_hours')
                            <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="response_time" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Response Time</label>
                        <input type="text" name="response_time" id="response_time" value="{{ old('response_time', $values['response_time']) }}"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('response_time') border-red-500 @enderror"
                            placeholder="e.g. Within 24 hours">
                        @error('response_time')
                            <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Returns --}}
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Returns</h3>
                <div>
                    <label for="window_days" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Return Window (Days)</label>
                    <input type="number" name="window_days" id="window_days" value="{{ old('window_days', $values['window_days']) }}" min="0" max="365"
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('window_days') border-red-500 @enderror"
                        placeholder="e.g. 30">
                    @error('window_days')
                        <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Shipping Process Steps --}}
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03] lg:col-span-2">
                <h3 class="mb-1 text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Process Steps Editor</h3>
                <p class="mb-4 text-xs text-gray-400 dark:text-gray-500">One per line. Format: Step title | Short description</p>
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <label for="shipping_process_steps" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Shipping Process Steps</label>
                        <textarea name="shipping_process_steps" id="shipping_process_steps" rows="6"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white font-mono @error('shipping_process_steps') border-red-500 @enderror"
                            placeholder="Place order | We receive and confirm your order&#10;Pack | Items are packed securely">{{ old('shipping_process_steps', $values['shipping_steps_text']) }}</textarea>
                        @error('shipping_process_steps')
                            <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="returns_process_steps" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Returns Process Steps</label>
                        <textarea name="returns_process_steps" id="returns_process_steps" rows="6"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white font-mono @error('returns_process_steps') border-red-500 @enderror"
                            placeholder="Request | Submit a return request&#10;Ship back | Send the item within 7 days">{{ old('returns_process_steps', $values['returns_steps_text']) }}</textarea>
                        @error('returns_process_steps')
                            <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Shipping Extra --}}
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Shipping Extra</h3>
                <div class="space-y-5">
                    <div>
                        <label for="areas" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Shipping Areas</label>
                        <textarea name="areas" id="areas" rows="4"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('areas') border-red-500 @enderror"
                            placeholder="One area per line">{{ old('areas', $values['areas']) }}</textarea>
                        <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">One area per line.</p>
                        @error('areas')
                            <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="important_info" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Important Info</label>
                        <textarea name="important_info" id="important_info" rows="4"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('important_info') border-red-500 @enderror"
                            placeholder="One note per line">{{ old('important_info', $values['important_info']) }}</textarea>
                        <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">One line per note.</p>
                        @error('important_info')
                            <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="free_threshold" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Free Shipping Threshold</label>
                        <input type="number" name="free_threshold" id="free_threshold" value="{{ old('free_threshold', $values['free_threshold']) }}" min="0" step="0.01"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('free_threshold') border-red-500 @enderror"
                            placeholder="e.g. 75.00">
                        @error('free_threshold')
                            <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Storefront Page Copy --}}
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03] lg:col-span-2">
                <h3 class="mb-1 text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Storefront Page Copy</h3>
                <p class="mb-4 text-xs text-gray-400 dark:text-gray-500">Texts shown on the Contact, Help Center, Shipping, and About pages. Leave blank to use defaults.</p>
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div class="space-y-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-brand-500">Contact — Support Card</p>
                        <input type="text" name="support_card_title" value="{{ old('support_card_title', $values['support_card_title']) }}" placeholder="Real humans, real help"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('support_card_title') border-red-500 @enderror">
                        <textarea name="support_card_text" rows="2" placeholder="No bots, no runaround — your message goes straight to the people who can fix it."
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('support_card_text') border-red-500 @enderror">{{ old('support_card_text', $values['support_card_text']) }}</textarea>
                        <p class="pt-2 text-xs font-semibold uppercase tracking-wide text-brand-500">Help Center — Aside Card</p>
                        <input type="text" name="help_aside_title" value="{{ old('help_aside_title', $values['help_aside_title']) }}" placeholder="Can't find your answer?"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('help_aside_title') border-red-500 @enderror">
                        <textarea name="help_aside_text" rows="2" placeholder="Our team replies within one business day."
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('help_aside_text') border-red-500 @enderror">{{ old('help_aside_text', $values['help_aside_text']) }}</textarea>
                    </div>
                    <div class="space-y-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-brand-500">Shipping — Photo Captions</p>
                        <input type="text" name="caption_dispatch" value="{{ old('caption_dispatch', $values['caption_dispatch']) }}" placeholder="Fast dispatch, tracked to your door"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('caption_dispatch') border-red-500 @enderror">
                        <input type="text" name="caption_packing" value="{{ old('caption_packing', $values['caption_packing']) }}" placeholder="Packed with care, every order"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('caption_packing') border-red-500 @enderror">
                        <p class="pt-2 text-xs font-semibold uppercase tracking-wide text-brand-500">About — Values</p>
                        @foreach ([1, 2, 3] as $i)
                            <div class="flex gap-2">
                                <input type="text" name="value{{ $i }}_title" value="{{ old("value{$i}_title", $values["value{$i}_title"]) }}" placeholder="Value {{ $i }} title"
                                    class="w-1/3 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error("value{$i}_title") border-red-500 @enderror">
                                <input type="text" name="value{{ $i }}_text" value="{{ old("value{$i}_text", $values["value{$i}_text"]) }}" placeholder="Value {{ $i }} description"
                                    class="flex-1 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error("value{$i}_text") border-red-500 @enderror">
                            </div>
                        @endforeach
                    </div>
                </div>
                @error('support_card_title')<p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>@enderror
                @error('support_card_text')<p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>@enderror
                @error('help_aside_title')<p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>@enderror
                @error('help_aside_text')<p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>@enderror
                @error('caption_dispatch')<p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>@enderror
                @error('caption_packing')<p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="space-y-6">
                {{-- Press Contact --}}
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
                    <h3 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Press Contact</h3>
                    <div class="space-y-5">
                        <div>
                            <label for="press_contact_name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                            <input type="text" name="press_contact_name" id="press_contact_name" value="{{ old('press_contact_name', $values['press_contact_name']) }}"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('press_contact_name') border-red-500 @enderror">
                            @error('press_contact_name')
                                <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="press_contact_email" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                            <input type="email" name="press_contact_email" id="press_contact_email" value="{{ old('press_contact_email', $values['press_contact_email']) }}"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('press_contact_email') border-red-500 @enderror">
                            @error('press_contact_email')
                                <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="press_contact_phone" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                            <input type="text" name="press_contact_phone" id="press_contact_phone" value="{{ old('press_contact_phone', $values['press_contact_phone']) }}"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('press_contact_phone') border-red-500 @enderror">
                            @error('press_contact_phone')
                                <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- About --}}
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
                    <h3 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">About</h3>
                    <div class="space-y-5">
                        <div>
                            <label for="founded_year" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Founded Year</label>
                            <input type="number" name="founded_year" id="founded_year" value="{{ old('founded_year', $values['founded_year']) }}" min="1900" max="{{ date('Y') + 1 }}"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('founded_year') border-red-500 @enderror"
                                placeholder="e.g. 2015">
                            @error('founded_year')
                                <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="badge_title" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Highlight Tile Title</label>
                            <input type="text" name="badge_title" id="badge_title" value="{{ old('badge_title', $values['badge_title']) }}"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('badge_title') border-red-500 @enderror"
                                placeholder="Default: Serving customers since {year}">
                            @error('badge_title')
                                <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="badge_sub" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Highlight Tile Subtitle</label>
                            <input type="text" name="badge_sub" id="badge_sub" value="{{ old('badge_sub', $values['badge_sub']) }}"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('badge_sub') border-red-500 @enderror"
                                placeholder="One store, obsessed with getting it right.">
                            @error('badge_sub')
                                <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2">
                    Save Settings
                </button>
            </div>
        </div>
    </form>
@endsection
