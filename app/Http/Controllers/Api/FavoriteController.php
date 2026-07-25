<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function index(): JsonResponse
    {
        $favoriteIds = Favorite::where('user_id', Auth::id())
            ->pluck('product_id')
            ->toArray();

        return response()->json(['items' => $favoriteIds]);
    }

    public function toggle(int $productId): JsonResponse
    {
        $userId = Auth::id();

        $existing = Favorite::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            $existing->delete();
            $favorited = false;
        } else {
            Favorite::create([
                'user_id' => $userId,
                'product_id' => $productId,
            ]);
            $favorited = true;
        }

        $count = Favorite::where('user_id', $userId)->count();

        return response()->json([
            'favorited' => $favorited,
            'count' => $count,
        ]);
    }

    public function destroy(int $productId): JsonResponse
    {
        Favorite::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->delete();

        $count = Favorite::where('user_id', Auth::id())->count();

        return response()->json(['count' => $count]);
    }
}
