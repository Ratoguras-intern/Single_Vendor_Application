@php
    $settings = ($banner ?? null) ? ($banner->style_settings ?? []) : [];
    $dv = function ($key, $default = null) use ($settings) {
        return old($key, $settings[$key] ?? $default);
    };
    $previewSrc = ($banner ?? null)?->image_url ?? '';
    $imagePosition = old('image_position', ($banner ?? null)?->image_position_css ?? 'center center');
@endphp

<div x-data="bannerDisplayPreview()" @input="refresh()" @change="refresh()">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="space-y-4">

            <div x-data="{ open: true }" class="rounded-lg border border-gray-200 dark:border-gray-800">
                <button type="button" @click="open = !open" class="flex w-full items-center justify-between px-4 py-3">
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Image</span>
                    <svg class="h-4 w-4 shrink-0 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                </button>
                <div x-show="open" x-collapse>
                    <div class="space-y-4 px-4 pb-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="image_fit" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Image Fit</label>
                                <select name="image_fit" id="image_fit"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                    <option value="cover" {{ $dv('image_fit', 'cover') === 'cover' ? 'selected' : '' }}>Cover</option>
                                    <option value="contain" {{ $dv('image_fit') === 'contain' ? 'selected' : '' }}>Contain</option>
                                    <option value="fill" {{ $dv('image_fit') === 'fill' ? 'selected' : '' }}>Fill</option>
                                    <option value="none" {{ $dv('image_fit') === 'none' ? 'selected' : '' }}>None</option>
                                    <option value="scale-down" {{ $dv('image_fit') === 'scale-down' ? 'selected' : '' }}>Scale Down</option>
                                </select>
                            </div>
                            <div>
                                <label for="image_repeat" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Image Repeat</label>
                                <select name="image_repeat" id="image_repeat"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                    <option value="no-repeat" {{ $dv('image_repeat', 'no-repeat') === 'no-repeat' ? 'selected' : '' }}>No Repeat</option>
                                    <option value="repeat" {{ $dv('image_repeat') === 'repeat' ? 'selected' : '' }}>Repeat</option>
                                    <option value="repeat-x" {{ $dv('image_repeat') === 'repeat-x' ? 'selected' : '' }}>Repeat X</option>
                                    <option value="repeat-y" {{ $dv('image_repeat') === 'repeat-y' ? 'selected' : '' }}>Repeat Y</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label for="image_position" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Image Position</label>
                            <select name="image_position" id="image_position"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                <option value="center center" {{ $imagePosition === 'center center' ? 'selected' : '' }}>Center Center</option>
                                <option value="top center" {{ $imagePosition === 'top center' ? 'selected' : '' }}>Top Center</option>
                                <option value="bottom center" {{ $imagePosition === 'bottom center' ? 'selected' : '' }}>Bottom Center</option>
                                <option value="center left" {{ $imagePosition === 'center left' ? 'selected' : '' }}>Center Left</option>
                                <option value="center right" {{ $imagePosition === 'center right' ? 'selected' : '' }}>Center Right</option>
                                <option value="top left" {{ $imagePosition === 'top left' ? 'selected' : '' }}>Top Left</option>
                                <option value="top right" {{ $imagePosition === 'top right' ? 'selected' : '' }}>Top Right</option>
                                <option value="bottom left" {{ $imagePosition === 'bottom left' ? 'selected' : '' }}>Bottom Left</option>
                                <option value="bottom right" {{ $imagePosition === 'bottom right' ? 'selected' : '' }}>Bottom Right</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div x-data="{ open: true }" class="rounded-lg border border-gray-200 dark:border-gray-800">
                <button type="button" @click="open = !open" class="flex w-full items-center justify-between px-4 py-3">
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Layout</span>
                    <svg class="h-4 w-4 shrink-0 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                </button>
                <div x-show="open" x-collapse>
                    <div class="space-y-4 px-4 pb-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="banner_height" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Banner Height</label>
                                <select name="banner_height" id="banner_height" x-model="heightCustom"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                    <option value="" {{ $dv('banner_height') === null ? 'selected' : '' }}>Auto (responsive)</option>
                                    <option value="small" {{ $dv('banner_height') === 'small' ? 'selected' : '' }}>Small (300px)</option>
                                    <option value="medium" {{ $dv('banner_height') === 'medium' ? 'selected' : '' }}>Medium (450px)</option>
                                    <option value="large" {{ $dv('banner_height') === 'large' ? 'selected' : '' }}>Large (600px)</option>
                                    <option value="xlarge" {{ $dv('banner_height') === 'xlarge' ? 'selected' : '' }}>Extra Large (750px)</option>
                                    <option value="full_screen" {{ $dv('banner_height') === 'full_screen' ? 'selected' : '' }}>Full Screen (100vh)</option>
                                    <option value="custom" {{ $dv('banner_height') === 'custom' ? 'selected' : '' }}>Custom</option>
                                </select>
                                <p class="mt-1 text-xs text-gray-400">Not applied to sidebar banners (they use aspect ratio).</p>
                            </div>
                            <div x-show="heightCustom === 'custom'" x-cloak>
                                <label for="banner_height_custom" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Custom Height (px)</label>
                                <input type="number" name="banner_height_custom" id="banner_height_custom" value="{{ $dv('banner_height_custom') }}" min="50" max="2000" placeholder="400"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="border_radius" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Border Radius</label>
                                <select name="border_radius" id="border_radius" x-model="radiusCustom"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                    <option value="" {{ $dv('border_radius') === null ? 'selected' : '' }}>Auto (default)</option>
                                    <option value="none" {{ $dv('border_radius') === 'none' ? 'selected' : '' }}>None</option>
                                    <option value="small" {{ $dv('border_radius') === 'small' ? 'selected' : '' }}>Small</option>
                                    <option value="medium" {{ $dv('border_radius') === 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="large" {{ $dv('border_radius') === 'large' ? 'selected' : '' }}>Large</option>
                                    <option value="xlarge" {{ $dv('border_radius') === 'xlarge' ? 'selected' : '' }}>Extra Large</option>
                                    <option value="custom" {{ $dv('border_radius') === 'custom' ? 'selected' : '' }}>Custom</option>
                                </select>
                            </div>
                            <div x-show="radiusCustom === 'custom'" x-cloak>
                                <label for="border_radius_custom" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Custom Radius (px)</label>
                                <input type="number" name="border_radius_custom" id="border_radius_custom" value="{{ $dv('border_radius_custom') }}" min="0" max="200" placeholder="12"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            </div>
                        </div>
                        <div class="border-t border-gray-200 dark:border-gray-800 pt-4">
                            <h5 class="mb-3 text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">Spacing</h5>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                <div>
                                    <label for="padding_top" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Padding Top (px)</label>
                                    <input type="number" name="padding_top" id="padding_top" value="{{ $dv('padding_top') }}" min="0" max="200" placeholder="Auto"
                                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                </div>
                                <div>
                                    <label for="padding_bottom" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Padding Bottom (px)</label>
                                    <input type="number" name="padding_bottom" id="padding_bottom" value="{{ $dv('padding_bottom') }}" min="0" max="200" placeholder="Auto"
                                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                </div>
                                <div>
                                    <label for="padding_left" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Padding Left (px)</label>
                                    <input type="number" name="padding_left" id="padding_left" value="{{ $dv('padding_left') }}" min="0" max="200" placeholder="Auto"
                                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                </div>
                                <div>
                                    <label for="padding_right" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Padding Right (px)</label>
                                    <input type="number" name="padding_right" id="padding_right" value="{{ $dv('padding_right') }}" min="0" max="200" placeholder="Auto"
                                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                </div>
                            </div>
                            <div class="mt-4 grid grid-cols-2 gap-4">
                                <div>
                                    <label for="margin_top" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Margin Top (px)</label>
                                    <input type="number" name="margin_top" id="margin_top" value="{{ $dv('margin_top') }}" min="0" max="200" placeholder="Auto"
                                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                </div>
                                <div>
                                    <label for="margin_bottom" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Margin Bottom (px)</label>
                                    <input type="number" name="margin_bottom" id="margin_bottom" value="{{ $dv('margin_bottom') }}" min="0" max="200" placeholder="Auto"
                                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div x-data="{ open: true }" class="rounded-lg border border-gray-200 dark:border-gray-800">
                <button type="button" @click="open = !open" class="flex w-full items-center justify-between px-4 py-3">
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Overlay</span>
                    <svg class="h-4 w-4 shrink-0 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                </button>
                <div x-show="open" x-collapse>
                    <div class="space-y-4 px-4 pb-4">
                        <div class="flex items-center gap-2">
                            <input type="hidden" name="overlay_enabled" value="0">
                            <input type="checkbox" name="overlay_enabled" id="overlay_enabled" value="1" {{ old('overlay_enabled', $dv('overlay_enabled', true) ? '1' : '0') === '1' ? 'checked' : '' }}
                                class="h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-brand-500 focus:ring-brand-500">
                            <label for="overlay_enabled" class="text-sm font-medium text-gray-700 dark:text-gray-300">Enable Overlay</label>
                        </div>
                        <div>
                            <label for="overlay_color" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Overlay Color</label>
                            <div class="flex items-center gap-2">
                                <input type="color" name="overlay_color" id="overlay_color" value="{{ $dv('overlay_color', '#000000') }}"
                                    class="h-10 w-14 cursor-pointer rounded-lg border border-gray-300 bg-white p-1 dark:border-gray-700 dark:bg-gray-900">
                                <span class="text-xs text-gray-400">{{ $dv('overlay_color', '#000000') }}</span>
                            </div>
                        </div>
                        <div>
                            <label for="overlay_opacity" class="mb-1.5 flex items-center justify-between text-sm font-medium text-gray-700 dark:text-gray-300">
                                <span>Overlay Opacity</span>
                                <span class="text-xs text-gray-400" x-text="opacityVal"></span>
                            </label>
                            <input type="range" name="overlay_opacity" id="overlay_opacity" value="{{ old('overlay_opacity', ($banner ?? null)?->overlay_opacity ?? 40) }}" min="0" max="100" step="1"
                                class="w-full accent-brand-500">
                        </div>
                    </div>
                </div>
            </div>

            <div x-data="{ open: true }" class="rounded-lg border border-gray-200 dark:border-gray-800">
                <button type="button" @click="open = !open" class="flex w-full items-center justify-between px-4 py-3">
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Content</span>
                    <svg class="h-4 w-4 shrink-0 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                </button>
                <div x-show="open" x-collapse>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 px-4 pb-4">
                        <div>
                            <label for="text_alignment" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Content Alignment</label>
                            <select name="text_alignment" id="text_alignment"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                <option value="left" {{ old('text_alignment', ($banner ?? null)?->text_alignment ?? 'left') === 'left' ? 'selected' : '' }}>Left</option>
                                <option value="center" {{ old('text_alignment', ($banner ?? null)?->text_alignment) === 'center' ? 'selected' : '' }}>Center</option>
                                <option value="right" {{ old('text_alignment', ($banner ?? null)?->text_alignment) === 'right' ? 'selected' : '' }}>Right</option>
                            </select>
                        </div>
                        <div>
                            <label for="content_vertical" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Vertical Content Position</label>
                            <select name="content_vertical" id="content_vertical"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                <option value="" {{ $dv('content_vertical') === null ? 'selected' : '' }}>Auto (per position)</option>
                                <option value="top" {{ $dv('content_vertical') === 'top' ? 'selected' : '' }}>Top</option>
                                <option value="center" {{ $dv('content_vertical') === 'center' ? 'selected' : '' }}>Center</option>
                                <option value="bottom" {{ $dv('content_vertical') === 'bottom' ? 'selected' : '' }}>Bottom</option>
                            </select>
                        </div>
                        <div>
                            <label for="text_width" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Text Width</label>
                            <select name="text_width" id="text_width"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                <option value="narrow" {{ $dv('text_width', 'wide') === 'narrow' ? 'selected' : '' }}>Narrow</option>
                                <option value="medium" {{ $dv('text_width', 'wide') === 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="wide" {{ $dv('text_width', 'wide') === 'wide' ? 'selected' : '' }}>Wide</option>
                                <option value="full" {{ $dv('text_width', 'wide') === 'full' ? 'selected' : '' }}>Full</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div x-data="{ open: true }" class="rounded-lg border border-gray-200 dark:border-gray-800">
                <button type="button" @click="open = !open" class="flex w-full items-center justify-between px-4 py-3">
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Effects</span>
                    <svg class="h-4 w-4 shrink-0 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                </button>
                <div x-show="open" x-collapse>
                    <div class="space-y-4 px-4 pb-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <label for="grayscale" class="text-sm font-medium text-gray-700 dark:text-gray-300">Grayscale</label>
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="hidden" name="grayscale" value="0">
                                <input type="checkbox" name="grayscale" id="grayscale" value="1" {{ old('grayscale', $dv('grayscale', false) ? '1' : '0') === '1' ? 'checked' : '' }}
                                    class="h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-brand-500 focus:ring-brand-500">
                            </div>
                        </div>
                        <div>
                            <label for="zoom" class="mb-1.5 flex items-center justify-between text-sm font-medium text-gray-700 dark:text-gray-300">
                                <span>Image Zoom</span>
                                <span class="text-xs text-gray-400" x-text="zoomVal + '%'"></span>
                            </label>
                            <input type="range" name="zoom" id="zoom" value="{{ $dv('zoom', 100) }}" min="50" max="200" step="1"
                                class="w-full accent-brand-500">
                        </div>
                        <div>
                            <label for="brightness" class="mb-1.5 flex items-center justify-between text-sm font-medium text-gray-700 dark:text-gray-300">
                                <span>Brightness</span>
                                <span class="text-xs text-gray-400" x-text="brightnessVal + '%'"></span>
                            </label>
                            <input type="range" name="brightness" id="brightness" value="{{ $dv('brightness', 100) }}" min="0" max="200" step="1"
                                class="w-full accent-brand-500">
                        </div>
                        <div>
                            <label for="contrast" class="mb-1.5 flex items-center justify-between text-sm font-medium text-gray-700 dark:text-gray-300">
                                <span>Contrast</span>
                                <span class="text-xs text-gray-400" x-text="contrastVal + '%'"></span>
                            </label>
                            <input type="range" name="contrast" id="contrast" value="{{ $dv('contrast', 100) }}" min="0" max="200" step="1"
                                class="w-full accent-brand-500">
                        </div>
                        <div>
                            <label for="saturation" class="mb-1.5 flex items-center justify-between text-sm font-medium text-gray-700 dark:text-gray-300">
                                <span>Saturation</span>
                                <span class="text-xs text-gray-400" x-text="saturationVal + '%'"></span>
                            </label>
                            <input type="range" name="saturation" id="saturation" value="{{ $dv('saturation', 100) }}" min="0" max="200" step="1"
                                class="w-full accent-brand-500">
                        </div>
                        <div>
                            <label for="blur" class="mb-1.5 flex items-center justify-between text-sm font-medium text-gray-700 dark:text-gray-300">
                                <span>Blur</span>
                                <span class="text-xs text-gray-400" x-text="blurVal + 'px'"></span>
                            </label>
                            <input type="range" name="blur" id="blur" value="{{ $dv('blur', 0) }}" min="0" max="20" step="1"
                                class="w-full accent-brand-500">
                        </div>
                    </div>
                </div>
            </div>

            <div x-data="{ open: true }" class="rounded-lg border border-gray-200 dark:border-gray-800">
                <button type="button" @click="open = !open" class="flex w-full items-center justify-between px-4 py-3">
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Responsive</span>
                    <svg class="h-4 w-4 shrink-0 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                </button>
                <div x-show="open" x-collapse>
                    <div class="space-y-3 px-4 pb-4">
                        <div class="flex items-center gap-2">
                            <input type="hidden" name="show_desktop" value="0">
                            <input type="checkbox" name="show_desktop" id="show_desktop" value="1" {{ old('show_desktop', $dv('show_desktop', true) ? '1' : '0') === '1' ? 'checked' : '' }}
                                class="h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-brand-500 focus:ring-brand-500">
                            <label for="show_desktop" class="text-sm font-medium text-gray-700 dark:text-gray-300">Show on Desktop</label>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="hidden" name="show_tablet" value="0">
                            <input type="checkbox" name="show_tablet" id="show_tablet" value="1" {{ old('show_tablet', $dv('show_tablet', true) ? '1' : '0') === '1' ? 'checked' : '' }}
                                class="h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-brand-500 focus:ring-brand-500">
                            <label for="show_tablet" class="text-sm font-medium text-gray-700 dark:text-gray-300">Show on Tablet</label>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="hidden" name="show_mobile" value="0">
                            <input type="checkbox" name="show_mobile" id="show_mobile" value="1" {{ old('show_mobile', $dv('show_mobile', true) ? '1' : '0') === '1' ? 'checked' : '' }}
                                class="h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-brand-500 focus:ring-brand-500">
                            <label for="show_mobile" class="text-sm font-medium text-gray-700 dark:text-gray-300">Show on Mobile</label>
                        </div>
                        <p class="text-xs text-gray-400">Unchecked devices will hide this banner entirely.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:sticky lg:top-6 self-start">
            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Live Preview</label>
            <div class="relative w-full overflow-hidden border border-gray-200 dark:border-gray-700 bg-secondary-900 cursor-pointer"
                :style="containerStyle" @click="showOverlay = !showOverlay">
                <img id="display-preview-img" :src="previewSrc" alt="" data-src="{{ $previewSrc }}"
                    class="absolute inset-0 w-full h-full object-cover hidden md:block" :style="imgStyle">
                <img :src="previewSrc" alt=""
                    class="absolute inset-0 w-full h-full object-cover md:hidden" :style="imgStyle">
                <div class="absolute inset-0" :style="overlayStyle" x-show="showOverlay"></div>
                <div class="relative" :class="contentWrapClass" :style="contentStyle">
                    <div class="flex w-full flex-col text-white px-6 py-8">
                        <span class="inline-flex w-max items-center rounded-full bg-green-500 px-3 py-1 mb-3">
                            <span class="text-xs font-bold text-white tracking-wider">PREVIEW</span>
                        </span>
                        <h3 class="text-2xl font-extrabold leading-tight">Sample Banner Title</h3>
                        <p class="mt-2 text-sm text-white/80">This is how your banner content will appear with the selected settings.</p>
                        <div class="mt-4 inline-flex w-max items-center rounded-lg bg-white px-4 py-2 text-xs font-bold text-gray-900">Shop Now</div>
                    </div>
                </div>
                <div x-show="!previewSrc" class="absolute inset-0 z-10 flex items-center justify-center bg-gradient-to-br from-secondary-800 to-secondary-900">
                    <span class="text-xs text-white/40">Upload an image in the Images tab to preview</span>
                </div>
            </div>
            <p class="mt-2 text-xs text-gray-400">Click the preview to toggle the overlay effect.</p>
        </div>
    </div>
</div>

@push('scripts')
<script type="text/turbo-script">
    window.bannerDisplayPreview = function() {
        return {
            previewSrc: '',
            imgStyle: '',
            overlayStyle: '',
            showOverlay: false,
            contentWrapClass: 'h-full flex items-center justify-start',
            contentStyle: '',
            containerStyle: '',
            zoomVal: '{{ $dv('zoom', 100) }}',
            brightnessVal: '{{ $dv('brightness', 100) }}',
            contrastVal: '{{ $dv('contrast', 100) }}',
            saturationVal: '{{ $dv('saturation', 100) }}',
            blurVal: '{{ $dv('blur', 0) }}',
            opacityVal: '{{ old('overlay_opacity', ($banner ?? null)?->overlay_opacity ?? 40) }}%',
            heightCustom: '{{ $dv('banner_height') ?? '' }}',
            radiusCustom: '{{ $dv('border_radius') ?? '' }}',
            init() {
                this.previewSrc = document.getElementById('display-preview-img')?.getAttribute('data-src') || '';
                window.addEventListener('banner-preview-src', (e) => { this.previewSrc = e.detail; });
                this.refresh();
            },
            refresh() {
                const v = (id) => (document.getElementById(id)?.value ?? '');
                const fit = v('image_fit') || 'cover';
                const pos = v('image_position') || 'center center';
                const bHeight = v('banner_height');
                const bHeightC = v('banner_height_custom');
                const radius = v('border_radius');
                const radiusC = v('border_radius_custom');
                const enabled = document.getElementById('overlay_enabled')?.checked ?? true;
                const color = v('overlay_color') || '#000000';
                const opacity = Math.min(100, Math.max(0, parseInt(v('overlay_opacity')) || 40)) / 100;
                const vert = v('content_vertical');
                const align = v('text_alignment') || 'left';
                const tw = v('text_width') || 'wide';
                const zoom = parseInt(v('zoom')) || 100;
                const brightness = parseInt(v('brightness')) || 100;
                const contrast = parseInt(v('contrast')) || 100;
                const saturation = parseInt(v('saturation')) || 100;
                const blur = parseInt(v('blur')) || 0;
                const grayscale = document.getElementById('grayscale')?.checked ?? false;
                const pt = v('padding_top'), pr = v('padding_right'), pb = v('padding_bottom'), pl = v('padding_left');
                const mt = v('margin_top'), mb = v('margin_bottom');

                this.zoomVal = zoom + '%';
                this.brightnessVal = brightness + '%';
                this.contrastVal = contrast + '%';
                this.saturationVal = saturation + '%';
                this.blurVal = blur + 'px';
                this.opacityVal = Math.round(opacity * 100) + '%';

                let filter = 'brightness(' + brightness + '%) contrast(' + contrast + '%) saturate(' + saturation + '%) blur(' + blur + 'px)';
                if (grayscale) filter += ' grayscale(1)';

                this.imgStyle = 'object-fit:' + fit + ';object-position:' + pos + ';scale:' + (zoom / 100) + ';filter:' + filter + ';';

                let heightCss = '240px';
                if (bHeight) {
                    const map = { small: 300, medium: 450, large: 600, xlarge: 750 };
                    if (bHeight === 'full_screen') heightCss = '100vh';
                    else if (bHeight === 'custom') heightCss = ((parseInt(bHeightC) || 0) || 240) + 'px';
                    else heightCss = map[bHeight] + 'px';
                }
                const radiusMap = { none: 0, small: 8, medium: 16, large: 24, xlarge: 32 };
                const radiusPx = radius === 'custom' ? ((parseInt(radiusC) || 0) || 16) : (radius ? radiusMap[radius] : 16);
                this.containerStyle = 'min-height:' + heightCss + ';border-radius:' + radiusPx + 'px;margin-top:' + (mt || 0) + 'px;margin-bottom:' + (mb || 0) + 'px;';

                this.overlayStyle = enabled
                    ? 'background:linear-gradient(to right,' + this.hexToRgb(color, opacity) + ' 40%,transparent 100%);'
                    : 'display:none;';

                const vcls = vert ? (vert === 'top' ? 'items-start' : (vert === 'bottom' ? 'items-end' : 'items-center')) : 'items-center';
                const hcls = align === 'center' ? 'justify-center' : (align === 'right' ? 'justify-end' : 'justify-start');
                this.contentWrapClass = 'relative h-full flex ' + vcls + ' ' + hcls;

                const twPx = { narrow: 320, medium: 448, wide: 576, full: null };
                const maxW = twPx[tw] ?? null;
                let pad = '';
                if (pt !== '' || pr !== '' || pb !== '' || pl !== '') {
                    pad = 'padding:' + (pt || 0) + 'px ' + (pr || 0) + 'px ' + (pb || 0) + 'px ' + (pl || 0) + 'px;';
                }
                this.contentStyle = 'text-align:' + align + ';' + pad + (maxW ? 'max-width:' + maxW + 'px;' : '');
            },
            hexToRgb(hex, alpha) {
                let c = hex.replace('#', '');
                if (c.length === 3) c = c.split('').map((x) => x + x).join('');
                const r = parseInt(c.substring(0, 2), 16) || 0;
                const g = parseInt(c.substring(2, 4), 16) || 0;
                const b = parseInt(c.substring(4, 6), 16) || 0;
                return 'rgba(' + r + ',' + g + ',' + b + ',' + alpha + ')';
            }
        };
    };
</script>
@endpush
