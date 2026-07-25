<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TranslationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TranslationController extends Controller
{
    public function translate(Request $request, TranslationService $service): JsonResponse
    {
        if (!$service->isConfigured()) {
            return response()->json([
                'error' => 'Translation API not configured',
                'translations' => collect($request->input('texts', []))->mapWithKeys(fn($t) => [$t => $t])->all(),
            ], 200);
        }

        $validator = Validator::make($request->all(), [
            'texts' => 'required|array|max:50',
            'texts.*' => 'string|max:500',
            'target' => 'required|string|in:ja,ne',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Validation failed', 'messages' => $validator->errors()], 422);
        }

        $texts = array_unique($request->input('texts'));
        $target = $request->input('target');

        $translations = $service->translateBatch($texts, $target);

        return response()->json(['translations' => $translations]);
    }
}
