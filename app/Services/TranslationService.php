<?php

namespace App\Services;

use App\Models\Translation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TranslationService
{
    protected string $apiKey;
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('translation.api_key', '');
        $this->apiUrl = config('translation.api_url', 'https://translation.googleapis.com/language/translate/v2');
    }

    /**
     * Translate a batch of texts to target locale.
     * Returns array mapping source text -> translated text.
     */
    public function translateBatch(array $texts, string $targetLocale): array
    {
        if (empty($texts) || empty($this->apiKey)) {
            return array_fill_keys($texts, null);
        }

        $sourceLocale = config('translation.source_locale', 'en');
        $results = [];
        $uncached = [];

        foreach ($texts as $text) {
            $hash = md5($text . $sourceLocale . $targetLocale);
            $cached = $this->getFromCache($hash);
            if ($cached !== null) {
                $results[$text] = $cached;
            } else {
                $uncached[] = $text;
            }
        }

        if (!empty($uncached)) {
            $apiResults = $this->callGoogleApi($uncached, $targetLocale);
            foreach ($apiResults as $source => $translated) {
                $hash = md5($source . $sourceLocale . $targetLocale);
                $this->saveToCache($hash, $source, $sourceLocale, $targetLocale, $translated);
                $results[$source] = $translated;
            }
        }

        return $results;
    }

    /**
     * Translate a single text.
     */
    public function translate(string $text, string $targetLocale): ?string
    {
        $results = $this->translateBatch([$text], $targetLocale);
        return $results[$text] ?? null;
    }

    /**
     * Get translation from database cache.
     */
    protected function getFromCache(string $hash): ?string
    {
        $translation = Translation::where('source_hash', $hash)->first();
        return $translation?->translated_text;
    }

    /**
     * Save translation to database cache.
     */
    protected function saveToCache(string $hash, string $sourceText, string $sourceLocale, string $targetLocale, string $translated): void
    {
        Translation::updateOrCreate(
            ['source_hash' => $hash],
            [
                'source_text' => $sourceText,
                'source_locale' => $sourceLocale,
                'target_locale' => $targetLocale,
                'translated_text' => $translated,
            ]
        );
    }

    /**
     * Call Google Cloud Translation API v2.
     * Sends all texts in a single request using multiple q params.
     */
    protected function callGoogleApi(array $texts, string $targetLocale): array
    {
        $results = [];

        try {
            $chunks = array_chunk($texts, 50);

            foreach ($chunks as $chunk) {
                $queryParams = ['key' => $this->apiKey];
                foreach ($chunk as $text) {
                    $queryParams['q'][] = $text;
                }

                $response = Http::timeout(15)
                    ->asForm()
                    ->post($this->apiUrl, array_merge(
                        ['key' => $this->apiKey, 'target' => $targetLocale, 'source' => 'en'],
                        ['q' => $chunk]
                    ));

                if ($response->successful()) {
                    $data = $response->json('data.translations', []);
                    foreach ($data as $i => $translation) {
                        $results[$chunk[$i]] = $translation['translatedText'] ?? $chunk[$i];
                    }
                } else {
                    Log::warning('Google Translation API error', [
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]);
                    foreach ($chunk as $text) {
                        $results[$text] = $text;
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Google Translation API exception', ['message' => $e->getMessage()]);
            foreach ($texts as $text) {
                $results[$text] = $text;
            }
        }

        return $results;
    }

    /**
     * Check if API is configured.
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }
}
