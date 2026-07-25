<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CurrencyService
{
    protected string $apiUrl;
    protected int $cacheTtl;

    public function __construct()
    {
        $this->apiUrl = config('currency.api_url');
        $this->cacheTtl = config('currency.cache_ttl', 86400);
    }

    public function getRates(): array
    {
        return Cache::remember('currency_rates', $this->cacheTtl, function () {
            try {
                $response = Http::timeout(10)->get($this->apiUrl);
                if ($response->successful()) {
                    return $response->json('rates', []);
                }
            } catch (\Exception $e) {
                report($e);
            }
            return ['USD' => 1, 'JPY' => 149.5, 'NPR' => 133.2];
        });
    }

    public function convert(float $amount, string $from, string $to): float
    {
        $rates = $this->getRates();
        if (!isset($rates[$from]) || !isset($rates[$to])) {
            return $amount;
        }
        $inUsd = $amount / $rates[$from];
        return $inUsd * $rates[$to];
    }

    public function format(float $amount, string $currency, string $from = 'USD'): string
    {
        $converted = $this->convert($amount, $from, $currency);
        $currencies = config('currency.supported', []);
        $currencyConfig = $currencies[$currency] ?? $currencies['USD'];
        $symbol = $currencyConfig['symbol'];

        if ($currency === 'JPY') {
            return $symbol . number_format($converted, 0);
        }

        return $symbol . number_format($converted, 2);
    }
}
