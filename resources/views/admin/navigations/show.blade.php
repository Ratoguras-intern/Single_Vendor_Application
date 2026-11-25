@extends('admin.layouts.app')

@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Navigation', 'url' => route('admin.navigations.index')],
        ['label' => $menu->name, 'url' => null],
    ];

    $megaSlides = $menu->getConfig('slides', []);
    $dataScopes = [
        'featured'   => 'Featured products (auto)',
        'onSale'     => 'On-sale products (auto)',
        'trending'   => 'Trending products (auto)',
        'bestSeller' => 'Best sellers (auto)',
    ];
    $slideDefaults = [
        ['enabled' => true, 'data_scope' => 'featured', 'badge' => 'Curated', 'heading' => 'Curated Favorites', 'description' => 'Handpicked products selected for quality.', 'cta_text' => 'Shop Now', 'cta_url' => '', 'product_id' => null, 'image' => null],
        ['enabled' => true, 'data_scope' => 'onSale', 'badge' => 'Exclusive', 'heading' => 'Exclusive Deals', 'description' => 'Limited-time offers and special promotions.', 'cta_text' => 'View Deals', 'cta_url' => '', 'product_id' => null, 'image' => null],
        ['enabled' => true, 'data_scope' => 'trending', 'badge' => 'Trending', 'heading' => 'Trending Now', 'description' => 'Products currently getting attention from customers.', 'cta_text' => 'Explore Trending', 'cta_url' => '', 'product_id' => null, 'image' => null],
        ['enabled' => true, 'data_scope' => 'bestSeller', 'badge' => 'Best Selling', 'heading' => 'Best Sellers', 'description' => 'Popular products customers are buying most.', 'cta_text' => 'Shop Best Sellers', 'cta_url' => '', 'product_id' => null, 'image' => null],
    ];
    $displaySlides = count($megaSlides) ? $megaSlides : $slideDefaults;
@endphp

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">{{ $menu->name }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Slug: <code class="text-xs bg-gray-100 dark:bg-gray-800 px-1.5 py-0.5 rounded">{{ $menu->slug }}</code></p>
        </div>
        <button onclick="toggleMenu({{ $menu->id }})"
            class="relative inline-flex h-6 w-11 shrink-0 items-center overflow-hidden rounded-full transition-colors
                {{ $menu->is_enabled ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600' }}">
            <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform
                {{ $menu->is_enabled ? 'translate-x-6' : 'translate-x-1' }}"></span>
        </button>
    </div>

    <div class="grid gap-6 xl:grid-cols-3">
        {{-- Items List --}}
        <div class="xl:col-span-2">
            <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-gray-800">
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Items</h3>
                    <button x-on:click="$dispatch('open-add-item')" class="inline-flex items-center gap-1.5 rounded-lg bg-brand-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-brand-600 transition-colors">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Add Item
                    </button>
                </div>

                <div id="sortable-container" class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($topItems as $item)
                        <div class="sortable-row flex items-center gap-3 px-5 py-3 hover:bg-gray-50 dark:hover:bg-white/[0.02]" data-id="{{ $item->id }}">
                            <span class="drag-handle cursor-grab text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 shrink-0">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
                            </span>
                            <div class="min-w-0 flex-1">
                                <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $item->name }}</span>
                                @if($item->url)
                                    <span class="ml-2 text-xs text-gray-400 dark:text-gray-500 font-mono">{{ $item->url }}</span>
                                @endif
                                @if($item->children->count())
                                    <div class="mt-1 ml-4 space-y-0.5">
                                        @foreach($item->children as $child)
                                            <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                                <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-600 shrink-0"></span>
                                                {{ $child->name }}
                                                @if($child->url)
                                                    <span class="font-mono text-gray-400 dark:text-gray-500">{{ $child->url }}</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="flex items-center gap-1 shrink-0">
                                <button onclick="toggleItem({{ $menu->id }}, {{ $item->id }})"
                                    class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors
                                        {{ $item->is_enabled ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600' }}">
                                    <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white shadow transition-transform
                                        {{ $item->is_enabled ? 'translate-x-[18px]' : 'translate-x-[3px]' }}"></span>
                                </button>
                                <button x-on:click="$dispatch('open-edit-item', { id: {{ $item->id }}, name: @js($item->name), url: @js($item->url ?? ''), iconKey: @js($item->icon_key ?? ''), target: @js($item->target), permission: @js($item->permission ?? ''), badge: @js($item->badge ?? ''), cssClass: @js($item->css_class ?? '') })"
                                    class="p-1.5 rounded-lg text-gray-400 hover:text-brand-500 hover:bg-brand-50 dark:hover:bg-brand-500/10 transition-colors">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                                <form action="{{ route('admin.navigations.destroyItem', [$menu, $item]) }}" method="POST" class="inline"
                                    x-data @submit.prevent="$store.confirmModal.open({ title: 'Delete Navigation Item', message: 'Delete this item and its children?', form: $el })">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="px-5 py-8 text-center text-sm text-gray-400 dark:text-gray-500">No items yet.</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Config Panel --}}
        <div class="xl:col-span-1">
            @if($menu->slug === 'mega-menu-promo')
                <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-gray-800">
                        <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Featured Carousel Slides</h3>
                        <div class="flex items-center gap-1.5">
                            <button x-on:click="$dispatch('open-add-slide')" class="inline-flex items-center gap-1 rounded-lg bg-brand-500 px-2.5 py-1.5 text-xs font-medium text-white hover:bg-brand-600 transition-colors">
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                Add
                            </button>
                            <form action="{{ route('admin.navigations.resetSlides', $menu) }}" method="POST" onsubmit="return confirm('Reset all slides to defaults?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-1 rounded-lg border border-gray-300 px-2.5 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/5 transition-colors">Reset</button>
                            </form>
                        </div>
                    </div>

                    @if(!count($megaSlides))
                        <div class="px-5 py-3 border-b border-dashed border-gray-200 dark:border-gray-800 text-xs text-gray-400 dark:text-gray-500">
                            No custom slides yet — showing the built-in defaults. Add or edit slides below to customise.
                        </div>
                    @endif

                    <div id="slides-sortable" class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach($displaySlides as $index => $slide)
                            <div class="slide-row flex items-center gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-white/[0.02]" data-index="{{ $index }}">
                                <span class="slide-handle cursor-grab text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 shrink-0">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
                                </span>
                                <div class="min-w-0 flex-1">
                                    @if(!empty($slide['badge']))
                                        <span class="inline-block rounded-full bg-brand-100 px-2 py-0.5 text-[10px] font-semibold text-brand-700 dark:bg-brand-500/15 dark:text-brand-300 mb-0.5">{{ $slide['badge'] }}</span>
                                    @endif
                                    <p class="text-sm font-medium text-gray-800 dark:text-white truncate">{{ $slide['heading'] ?? 'Untitled slide' }}</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 truncate">
                                        {{ $dataScopes[$slide['data_scope'] ?? ''] ?? 'Custom link' }}
                                        @if(!empty($slide['product_id'])) · Product #{{ $slide['product_id'] }} @endif
                                    </p>
                                </div>
                                <div class="flex items-center gap-1 shrink-0">
                                    <button onclick="toggleSlide({{ $menu->id }}, {{ $index }})"
                                        class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors {{ ($slide['enabled'] ?? true) ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600' }}">
                                        <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white shadow transition-transform {{ ($slide['enabled'] ?? true) ? 'translate-x-[18px]' : 'translate-x-[3px]' }}"></span>
                                    </button>
                                    <button x-on:click="$dispatch('open-edit-slide', { index: {{ $index }}, slide: @js($slide) })"
                                        class="p-1.5 rounded-lg text-gray-400 hover:text-brand-500 hover:bg-brand-50 dark:hover:bg-brand-500/10 transition-colors">
                                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </button>
                                    <form action="{{ route('admin.navigations.destroySlide', [$menu, $index]) }}" method="POST" class="inline" onsubmit="return confirm('Delete this slide?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <p class="px-5 py-3 text-[11px] text-gray-400 dark:text-gray-500">Drag rows to reorder. Slides auto-rotate in the featured panel; each slide can use an auto product (by scope) or a specific product.</p>
                </div>
            @endif

            <div class="mt-4 rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-800">
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Available Icons</h3>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-4 gap-2">
                        @foreach($availableIcons as $icon)
                            <div class="flex flex-col items-center gap-1 p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-white/5">
                                <span class="text-gray-500 dark:text-gray-400">{!! \App\Helpers\MenuHelper::getIconSvg($icon) !!}</span>
                                <span class="text-[10px] text-gray-400 dark:text-gray-500 truncate w-full text-center">{{ $icon }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Add/Edit Item Modal --}}
    <div x-data="navItemModal()" x-on:open-add-item.window="openAdd()" x-on:open-edit-item.window="openEdit($event.detail)"
        x-show="open" x-cloak class="fixed inset-0 z-[99999] flex items-center justify-center p-4" style="display:none">
        <div class="fixed inset-0 bg-black/50" @click="open = false"></div>
        <div class="relative w-full max-w-lg rounded-xl bg-white shadow-2xl dark:bg-gray-900 dark:border dark:border-gray-700 p-6 z-10">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4" x-text="editing ? 'Edit Item' : 'Add Item'"></h3>
            <form :action="editing ? '{{ url("admin/navigations/{$menu->id}/items") }}/' + editId : '{{ route("admin.navigations.storeItem", $menu) }}'" method="POST">
                @csrf
                <template x-if="editing"><input type="hidden" name="_method" value="PUT"></template>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Name *</label>
                        <input type="text" name="name" x-model="name" required
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-800 dark:text-white focus:border-brand-500 focus:ring-1 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">URL</label>
                        <input type="text" name="url" x-model="url"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-800 dark:text-white focus:border-brand-500 focus:ring-1 focus:ring-brand-500"
                            placeholder="/shop or admin.dashboard">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Icon Key</label>
                            <select name="icon_key" x-model="iconKey"
                                class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-800 dark:text-white focus:border-brand-500 focus:ring-1 focus:ring-brand-500">
                                <option value="">None</option>
                                @foreach($availableIcons as $icon)
                                    <option value="{{ $icon }}">{{ $icon }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Target</label>
                            <select name="target" x-model="target"
                                class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-800 dark:text-white focus:border-brand-500 focus:ring-1 focus:ring-brand-500">
                                <option value="_self">Same Tab</option>
                                <option value="_blank">New Tab</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Permission</label>
                        <input type="text" name="permission" x-model="permission"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-800 dark:text-white focus:border-brand-500 focus:ring-1 focus:ring-brand-500"
                            placeholder="e.g. super_admin">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Badge</label>
                            <input type="text" name="badge" x-model="badge"
                                class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-800 dark:text-white focus:border-brand-500 focus:ring-1 focus:ring-brand-500"
                                placeholder="e.g. new">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">CSS Class</label>
                            <input type="text" name="css_class" x-model="cssClass"
                                class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-800 dark:text-white focus:border-brand-500 focus:ring-1 focus:ring-brand-500"
                                placeholder="text-red-500">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" @click="open = false" class="rounded-lg border border-gray-300 dark:border-gray-700 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">Cancel</button>
                    <button type="submit" class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600 transition-colors" x-text="editing ? 'Update' : 'Add'"></button>
                </div>
            </form>
        </div>
    </div>

    {{-- Slide Modal --}}
    <div x-data="slideModal()" @open-add-slide.window="openAdd()" @open-edit-slide.window="openEdit($event.detail)">
        <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50" @keydown.escape.window="open=false">
            <div class="w-full max-w-lg rounded-xl bg-white dark:bg-gray-900 shadow-xl" x-show="open" x-transition @click.outside="open=false">
                <form :action="action" method="POST" class="p-6">
                    @csrf
                    <input type="hidden" name="_method" :value="method">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-sm font-bold text-gray-800 dark:text-white" x-text="isNew ? 'Add Slide' : 'Edit Slide'"></h3>
                        <button type="button" @click="open=false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Heading *</label>
                            <input type="text" name="heading" x-model="heading"
                                class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-800 dark:text-white focus:border-brand-500 focus:ring-1 focus:ring-brand-500">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Badge</label>
                                <input type="text" name="badge" x-model="badge"
                                    class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-800 dark:text-white focus:border-brand-500 focus:ring-1 focus:ring-brand-500"
                                    placeholder="e.g. Curated">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Data Scope</label>
                                <select name="data_scope" x-model="dataScope"
                                    class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-800 dark:text-white focus:border-brand-500 focus:ring-1 focus:ring-brand-500">
                                    <option value="">None (custom below)</option>
                                    @foreach($dataScopes as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Description</label>
                            <textarea name="description" rows="2" x-model="description"
                                class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-800 dark:text-white focus:border-brand-500 focus:ring-1 focus:ring-brand-500"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">CTA Text</label>
                                <input type="text" name="cta_text" x-model="ctaText"
                                    class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-800 dark:text-white focus:border-brand-500 focus:ring-1 focus:ring-brand-500"
                                    placeholder="Shop Now">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">CTA URL</label>
                                <input type="text" name="cta_url" x-model="ctaUrl"
                                    class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-800 dark:text-white focus:border-brand-500 focus:ring-1 focus:ring-brand-500"
                                    placeholder="/shop or route name">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Specific Product (optional)</label>
                            <select name="product_id" x-model="productId"
                                class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-800 dark:text-white focus:border-brand-500 focus:ring-1 focus:ring-brand-500">
                                <option value="">Auto (by scope)</option>
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}">#{{ $p->id }} - {{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" @click="open=false" class="rounded-lg border border-gray-300 dark:border-gray-700 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">Cancel</button>
                        <button type="submit" class="rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600 transition-colors" x-text="isNew ? 'Add Slide' : 'Update Slide'"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script type="text/turbo-script">
        function toggleMenu(id) {
            fetch(`/admin/navigations/${id}/toggle`, {
                method: 'PATCH',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
            }).then(r => r.json()).then(() => Turbo.visit(location.href, { action: 'replace' }));
        }

        function toggleItem(menuId, itemId) {
            fetch(`/admin/navigations/${menuId}/items/${itemId}/toggle`, {
                method: 'PATCH',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
            }).then(r => r.json()).then(() => Turbo.visit(location.href, { action: 'replace' }));
        }

        function toggleSlide(menuId, index) {
            fetch(`/admin/navigations/${menuId}/slides/${index}/toggle`, {
                method: 'PATCH',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
            }).then(r => r.json()).then(() => Turbo.visit(location.href, { action: 'replace' }));
        }

        {
            const container = document.getElementById('sortable-container');
            if (container && container.children.length > 1) {
                new Sortable(container, {
                    handle: '.drag-handle',
                    animation: 150,
                    onEnd: function() {
                        const order = [];
                        document.querySelectorAll('.sortable-row').forEach(row => {
                            order.push(parseInt(row.dataset.id));
                        });
                        fetch('{{ route("admin.navigations.updateOrder", $menu) }}', {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ order })
                        }).then(r => r.json()).then(d => {
                            if (d.message) console.log(d.message);
                        });
                    }
                });
            }
        }

        {
            const container = document.getElementById('slides-sortable');
            if (container && container.children.length > 1) {
                new Sortable(container, {
                    handle: '.slide-handle',
                    animation: 150,
                    onEnd: function() {
                        const order = [];
                        document.querySelectorAll('#slides-sortable .slide-row').forEach(row => {
                            order.push(parseInt(row.dataset.index));
                        });
                        fetch('{{ route("admin.navigations.reorderSlides", $menu) }}', {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ order })
                        }).then(r => r.json()).then(d => {
                            if (d.message) console.log(d.message);
                        }).then(() => Turbo.visit(location.href, { action: 'replace' }));
                    }
                });
            }
        }
    </script>
    @endpush
@endsection

<script>
    function navItemModal() {
        return {
            open: false,
            editing: false,
            editId: null,
            name: '',
            url: '',
            iconKey: '',
            target: '_self',
            permission: '',
            badge: '',
            cssClass: '',
            openAdd() {
                this.editing = false;
                this.editId = null;
                this.name = '';
                this.url = '';
                this.iconKey = '';
                this.target = '_self';
                this.permission = '';
                this.badge = '';
                this.cssClass = '';
                this.open = true;
            },
            openEdit(data) {
                this.editing = true;
                this.editId = data.id;
                this.name = data.name;
                this.url = data.url;
                this.iconKey = data.iconKey;
                this.target = data.target;
                this.permission = data.permission;
                this.badge = data.badge;
                this.cssClass = data.cssClass;
                this.open = true;
            }
        }
    }
</script>

<script>
    function slideModal() {
        return {
            open: false,
            isNew: true,
            index: null,
            method: '',
            action: '',
            heading: '',
            badge: '',
            description: '',
            dataScope: '',
            ctaText: '',
            ctaUrl: '',
            productId: '',
            openAdd() {
                this.isNew = true;
                this.method = '';
                this.action = '{{ route('admin.navigations.storeSlide', $menu) }}';
                this.heading = '';
                this.badge = '';
                this.description = '';
                this.dataScope = '';
                this.ctaText = '';
                this.ctaUrl = '';
                this.productId = '';
                this.open = true;
            },
            openEdit(detail) {
                this.isNew = false;
                this.index = detail.index;
                this.method = 'PUT';
                this.action = '{{ route('admin.navigations.updateSlide', [$menu, '__INDEX__']) }}'.replace('__INDEX__', detail.index);
                this.heading = detail.slide.heading || '';
                this.badge = detail.slide.badge || '';
                this.description = detail.slide.description || '';
                this.dataScope = detail.slide.data_scope || '';
                this.ctaText = detail.slide.cta_text || '';
                this.ctaUrl = detail.slide.cta_url || '';
                this.productId = detail.slide.product_id ? String(detail.slide.product_id) : '';
                this.open = true;
            }
        }
    }
</script>
