<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\View\View;

class CategoryPageController extends Controller
{
    public function show(string $slug): View
    {
        $category = Category::where('slug', $slug)
            ->active()
            ->firstOrFail();

        return view('frontend.category', compact('category'));
    }
}
