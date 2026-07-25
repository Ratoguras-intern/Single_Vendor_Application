<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = Favorite::where('user_id', Auth::id())
            ->with('product.images')
            ->latest()
            ->get()
            ->pluck('product');

        return view('frontend.favorites', compact('favorites'));
    }
}
