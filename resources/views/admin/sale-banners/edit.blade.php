@extends('admin.layouts.app')

@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Sale Banners', 'url' => route('admin.sale-banners.index')],
        ['label' => 'Edit', 'url' => null],
    ];
    $banner = $saleBanner;
@endphp

@section('content')
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white">Edit Sale Banner</h2>
    </div>

    <div x-data="{ tab: 'content', tabs: ['content','sale','images','buttons','schedule','display','style'], nextTab() { const i = this.tabs.indexOf(this.tab); if (i < this.tabs.length - 1) { this.tab = this.tabs[i + 1]; } } }" class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="border-b border-gray-200 dark:border-gray-800">
            <nav class="flex flex-wrap gap-0 px-6" role="tablist">
                <button @click="tab = 'content'" :class="tab === 'content' ? 'border-brand-500 text-brand-600 dark:text-brand-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'" class="px-4 py-3 text-sm font-medium border-b-2 transition-colors whitespace-nowrap">Content</button>
                <button @click="tab = 'sale'" :class="tab === 'sale' ? 'border-brand-500 text-brand-600 dark:text-brand-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'" class="px-4 py-3 text-sm font-medium border-b-2 transition-colors whitespace-nowrap">Sale</button>
                <button @click="tab = 'images'" :class="tab === 'images' ? 'border-brand-500 text-brand-600 dark:text-brand-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'" class="px-4 py-3 text-sm font-medium border-b-2 transition-colors whitespace-nowrap">Images</button>
                <button @click="tab = 'buttons'" :class="tab === 'buttons' ? 'border-brand-500 text-brand-600 dark:text-brand-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'" class="px-4 py-3 text-sm font-medium border-b-2 transition-colors whitespace-nowrap">Buttons</button>
                <button @click="tab = 'schedule'" :class="tab === 'schedule' ? 'border-brand-500 text-brand-600 dark:text-brand-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'" class="px-4 py-3 text-sm font-medium border-b-2 transition-colors whitespace-nowrap">Schedule</button>
                <button @click="tab = 'display'" :class="tab === 'display' ? 'border-brand-500 text-brand-600 dark:text-brand-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'" class="px-4 py-3 text-sm font-medium border-b-2 transition-colors whitespace-nowrap">Display</button>
                <button @click="tab = 'style'" :class="tab === 'style' ? 'border-brand-500 text-brand-600 dark:text-brand-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'" class="px-4 py-3 text-sm font-medium border-b-2 transition-colors whitespace-nowrap">Style</button>
            </nav>
        </div>

        <form action="{{ route('admin.sale-banners.update', $saleBanner) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="p-6">
                <div x-show="tab === 'content'" x-cloak>
                    <div class="space-y-5">
                        <div>
                            <label for="title" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $saleBanner->title) }}"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label for="subtitle" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Subtitle</label>
                            <input type="text" name="subtitle" id="subtitle" value="{{ old('subtitle', $saleBanner->subtitle) }}"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label for="description" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                            <textarea name="description" id="description" rows="3"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">{{ old('description', $saleBanner->description) }}</textarea>
                        </div>
                    </div>
                </div>

                <div x-show="tab === 'sale'" x-cloak>
                    @include('admin.banners._sale_settings', ['banner' => $banner, 'products' => $products])
                </div>

                <div x-show="tab === 'images'" x-cloak>
                    <div class="space-y-6">
                        @php
                            $imageMedia = $saleBanner->image
                                ? \App\Models\Media::where('path', $saleBanner->image)->first()
                                : null;
                        @endphp
                        <div x-data
                            @media-picker:select.window="if ($event.detail.name === 'image_media_id' && $event.detail.media[0]) { window.dispatchEvent(new CustomEvent('banner-preview-src', { detail: $event.detail.media[0].url })); }">
                            <x-media-picker.picker name="image_media_id" label="Background Image" folder="banners"
                                :value="$imageMedia?->id" :preview="$saleBanner->image_url"
                                remove-name="remove_image"
                                help="Recommended width: 1920px. Selecting a new image replaces the current one." />
                            @error('image_media_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div x-show="tab === 'buttons'" x-cloak>
                    <div class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="button_text" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Primary Button Text</label>
                                <input type="text" name="button_text" id="button_text" value="{{ old('button_text', $saleBanner->button_text) }}" placeholder="Shop Now"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            </div>
                            <div>
                                <label for="link" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Primary Button Link</label>
                                <input type="text" name="link" id="link" value="{{ old('link', $saleBanner->link) }}" placeholder="/shop"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            </div>
                        </div>
                        <div class="border-t border-gray-200 dark:border-gray-800 pt-5">
                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Secondary Button (Optional)</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label for="secondary_button_text" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Button Text</label>
                                    <input type="text" name="secondary_button_text" id="secondary_button_text" value="{{ old('secondary_button_text', $saleBanner->secondary_button_text) }}" placeholder="Learn More"
                                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                </div>
                                <div>
                                    <label for="secondary_button_url" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">URL</label>
                                    <input type="text" name="secondary_button_url" id="secondary_button_url" value="{{ old('secondary_button_url', $saleBanner->secondary_button_url) }}" placeholder="/about"
                                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="tab === 'schedule'" x-cloak>
                    <div class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div>
                                <label for="sort_order" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Sort Order</label>
                                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $saleBanner->sort_order ?? 0) }}" min="0"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            </div>
                            <div>
                                <label for="starts_at" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Start Date</label>
                                <input type="datetime-local" name="starts_at" id="starts_at" value="{{ old('starts_at', $saleBanner->starts_at?->format('Y-m-d\TH:i')) }}"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            </div>
                            <div>
                                <label for="ends_at" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">End Date</label>
                                <input type="datetime-local" name="ends_at" id="ends_at" value="{{ old('ends_at', $saleBanner->ends_at?->format('Y-m-d\TH:i')) }}"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            </div>
                        </div>
                        <div class="flex items-center gap-2 mt-4">
                            <input type="hidden" name="show_countdown" value="0">
                            <input type="checkbox" name="show_countdown" id="show_countdown" value="1" {{ old('show_countdown', $saleBanner->show_countdown) ? 'checked' : '' }}
                                class="h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-brand-500 focus:ring-brand-500">
                            <label for="show_countdown" class="text-sm font-medium text-gray-700 dark:text-gray-300">Show countdown timer based on end date</label>
                        </div>
                    </div>
                </div>

                <div x-show="tab === 'display'" x-cloak>
                    @include('admin.banners._display_settings', ['banner' => $banner])
                </div>

                <div x-show="tab === 'style'" x-cloak>
                    <div class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="badge" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Badge Text</label>
                                <input type="text" name="badge" id="badge" value="{{ old('badge', $saleBanner->badge) }}" placeholder="FLASH SALE"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            </div>
                            <div>
                                <label for="badge_color" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Badge Color</label>
                                <select name="badge_color" id="badge_color"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                    <option value="bg-green-500" {{ old('badge_color', $saleBanner->badge_color) === 'bg-green-500' ? 'selected' : '' }}>Green</option>
                                    <option value="bg-blue-500" {{ old('badge_color', $saleBanner->badge_color) === 'bg-blue-500' ? 'selected' : '' }}>Blue</option>
                                    <option value="bg-purple-500" {{ old('badge_color', $saleBanner->badge_color) === 'bg-purple-500' ? 'selected' : '' }}>Purple</option>
                                    <option value="bg-orange-500" {{ old('badge_color', $saleBanner->badge_color) === 'bg-orange-500' ? 'selected' : '' }}>Orange</option>
                                    <option value="bg-red-500" {{ old('badge_color', $saleBanner->badge_color) === 'bg-red-500' ? 'selected' : '' }}>Red</option>
                                    <option value="bg-pink-500" {{ old('badge_color', $saleBanner->badge_color) === 'bg-pink-500' ? 'selected' : '' }}>Pink</option>
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div>
                                <label for="text_color" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Text Color</label>
                                <select name="text_color" id="text_color"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                    <option value="">Default (White)</option>
                                    <option value="text-white" {{ old('text_color', $saleBanner->text_color) === 'text-white' ? 'selected' : '' }}>White</option>
                                    <option value="text-gray-900" {{ old('text_color', $saleBanner->text_color) === 'text-gray-900' ? 'selected' : '' }}>Dark</option>
                                    <option value="text-primary-500" {{ old('text_color', $saleBanner->text_color) === 'text-primary-500' ? 'selected' : '' }}>Primary</option>
                                    <option value="text-amber-400" {{ old('text_color', $saleBanner->text_color) === 'text-amber-400' ? 'selected' : '' }}>Amber</option>
                                    <option value="text-green-400" {{ old('text_color', $saleBanner->text_color) === 'text-green-400' ? 'selected' : '' }}>Green</option>
                                </select>
                            </div>
                            <div>
                                <label for="is_enabled" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">&nbsp;</label>
                                <div class="flex items-center gap-2 pt-1.5">
                                    <input type="checkbox" name="is_enabled" id="is_enabled" value="1" {{ old('is_enabled', $saleBanner->is_enabled ? '1' : '0') === '1' ? 'checked' : '' }}
                                        class="h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-brand-500 focus:ring-brand-500">
                                    <label for="is_enabled" class="text-sm font-medium text-gray-700 dark:text-gray-300">Enabled</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 border-t border-gray-200 dark:border-gray-800 px-6 py-4">
                <button type="submit" x-show="tab === 'style'" x-cloak class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">Save Sale Banner</button>
                <button type="button" x-show="tab !== 'style'" @click="nextTab()" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">Next</button>
                <a href="{{ route('admin.sale-banners.index') }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.03]">Cancel</a>
            </div>
        </form>
    </div>

    @push('scripts')
    @endpush
@endsection
