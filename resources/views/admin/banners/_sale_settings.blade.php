@php
    $banner = $banner ?? null;
    $productValue = old('featured_product_id', $banner?->featured_product_id ?? '');
    $timezone = old('countdown_timezone', $banner?->countdown_timezone ?? config('app.timezone'));
    $timezones = collect([
        'UTC',
        'America/New_York', 'America/Chicago', 'America/Denver', 'America/Los_Angeles', 'America/Sao_Paulo',
        'Europe/London', 'Europe/Berlin', 'Europe/Paris', 'Europe/Moscow',
        'Asia/Dubai', 'Asia/Kolkata', 'Asia/Kathmandu', 'Asia/Singapore', 'Asia/Shanghai', 'Asia/Tokyo',
        'Australia/Sydney', 'Africa/Cairo', 'Africa/Johannesburg',
    ])->push(config('app.timezone'))->unique()->sort()->values();
@endphp

<div class="space-y-6">
    <div>
        <label for="featured_product_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Featured Product</label>
        <select name="featured_product_id" id="featured_product_id"
            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
            <option value="">None</option>
            @foreach($products as $product)
                <option value="{{ $product->id }}" {{ $productValue == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
            @endforeach
        </select>
        <p class="mt-1 text-xs text-gray-400">Current price, old price and discount % are pulled from this product. Required for prices to show.</p>
    </div>

    <div>
        <x-media-picker.picker name="product_image_media_id" label="Product Image" folder="banners"
            :value="$banner?->product_image ? \App\Models\Media::where('path', $banner->product_image)->value('id') : null"
            :preview="$banner?->product_image_url"
            remove-name="remove_product_image"
            help="Overrides the featured product's photo." />
        @error('product_image_media_id')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <div class="border-t border-gray-200 dark:border-gray-800 pt-5">
        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Background</h4>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div>
                <label for="background_color" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Background Color</label>
                <input type="color" name="background_color" id="background_color" value="{{ old('background_color', $banner?->background_color ?? '#0f172a') }}"
                    class="h-10 w-full cursor-pointer rounded-lg border border-gray-300 bg-white dark:border-gray-700 dark:bg-gray-900">
                <p class="mt-1 text-xs text-gray-400">Used when no background image.</p>
            </div>
            <div>
                <label for="gradient_from" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Gradient From</label>
                <input type="color" name="gradient_from" id="gradient_from" value="{{ old('gradient_from', $banner?->gradient_from ?? '#4f46e5') }}"
                    class="h-10 w-full cursor-pointer rounded-lg border border-gray-300 bg-white dark:border-gray-700 dark:bg-gray-900">
            </div>
            <div>
                <label for="gradient_to" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Gradient To</label>
                <input type="color" name="gradient_to" id="gradient_to" value="{{ old('gradient_to', $banner?->gradient_to ?? '#db2777') }}"
                    class="h-10 w-full cursor-pointer rounded-lg border border-gray-300 bg-white dark:border-gray-700 dark:bg-gray-900">
            </div>
        </div>
        <p class="mt-2 text-xs text-gray-400">Gradient overrides solid color when both set. Background image always wins.</p>
    </div>

    <div class="border-t border-gray-200 dark:border-gray-800 pt-5">
        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Countdown</h4>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="md:col-span-3">
                <x-date-picker name="countdown_end_date" label="End Date"
                    value="{{ old('countdown_end_date', $banner?->countdown_end_date ?? '') }}"
                    help="Pick the last day of the countdown. Falls back to the banner end date when empty." />
            </div>
            <div>
                <label for="countdown_end_time" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">End Time</label>
                <input type="time" name="countdown_end_time" id="countdown_end_time" value="{{ old('countdown_end_time', $banner?->countdown_end_time ?? '') }}"
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
            </div>
            <div>
                <label for="countdown_timezone" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Timezone</label>
                <select name="countdown_timezone" id="countdown_timezone"
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    @foreach($timezones as $tz)
                        <option value="{{ $tz }}" {{ $timezone === $tz ? 'selected' : '' }}>{{ $tz }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <p class="mt-2 text-xs text-gray-400">Falls back to the banner end date when countdown date/time are empty. Sale is considered ended once this time passes.</p>
    </div>

    <div class="border-t border-gray-200 dark:border-gray-800 pt-5">
        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Visibility Toggles</h4>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
            @php
                $toggles = [
                    'enable_badge' => 'Badge',
                    'enable_product_image' => 'Product Image',
                    'enable_prices' => 'Prices',
                    'enable_buttons' => 'Buttons',
                    'enable_overlay' => 'Dark Overlay',
                ];
            @endphp
            @foreach($toggles as $key => $label)
                <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                    <input type="hidden" name="{{ $key }}" value="0">
                    <input type="checkbox" name="{{ $key }}" value="1" {{ old($key, $banner?->{$key} ?? true) ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-brand-500 focus:ring-brand-500">
                    Enable {{ $label }}
                </label>
            @endforeach
        </div>
    </div>
</div>
