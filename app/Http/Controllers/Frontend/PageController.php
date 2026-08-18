<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show(string $slug)
    {
        $page = Page::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $seoTitle = $page->seo_title ?: $page->title;
        $seoDescription = $page->seo_description ?: $page->short_description;

        return view('frontend.page', compact('page', 'seoTitle', 'seoDescription'));
    }
}
