<?php

return [
    'api_key' => env('GOOGLE_TRANSLATE_API_KEY'),
    'api_url' => 'https://translation.googleapis.com/language/translate/v2',
    'source_locale' => 'en',
    'supported_locales' => ['en', 'ja', 'ne'],
    'cache_ttl' => 86400 * 30,
    'max_texts_per_request' => 50,
    'max_text_length' => 500,
];
