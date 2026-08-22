<?php

use App\Services\CurrencyService;
use Illuminate\Support\Facades\Storage;

if (! function_exists('admin_currency')) {
    function admin_currency(): string
    {
        return session('admin_currency', config('currency.default', 'USD'));
    }
}

if (! function_exists('currency_symbol')) {
    function currency_symbol(?string $currency = null): string
    {
        $currency = $currency ?? admin_currency();

        return config("currency.supported.{$currency}.symbol", config('currency.supported.USD.symbol', '$'));
    }
}

if (! function_exists('convert_amount')) {
    function convert_amount(float $amount, ?string $currency = null): float
    {
        $currency = $currency ?? admin_currency();

        return app(CurrencyService::class)->convert($amount, 'USD', $currency);
    }
}

if (! function_exists('format_currency')) {
    function format_currency(?float $amount, ?string $currency = null): string
    {
        $currency = $currency ?? admin_currency();

        return app(CurrencyService::class)->format($amount, $currency, 'USD');
    }
}

if (! function_exists('product_image_url')) {
    function product_image_url(?string $path): string
    {
        if (! $path) {
            return asset('frontend-assets/images/no-image.jpg');
        }

        if (str_starts_with($path, 'http')) {
            return $path;
        }

        return Storage::disk('public')->url($path);
    }
}

if (! function_exists('media_url')) {
    function media_url(?string $path): string
    {
        if (! $path) {
            return asset('frontend-assets/images/no-image.jpg');
        }

        if (str_starts_with($path, 'http')) {
            return $path;
        }

        return Storage::disk(config('media.disk', 'public'))->url($path);
    }
}

if (! function_exists('media_for_path')) {
    /**
     * Resolve the media library record for a stored path, or null when the
     * file predates the library (legacy uploads).
     */
    function media_for_path(?string $path): ?\App\Models\Media
    {
        if (! $path) {
            return null;
        }

        return \App\Models\Media::where('path', $path)->first();
    }
}
