<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSection;
use Illuminate\Http\Request;

class HomepageSectionController extends Controller
{
    public function index()
    {
        $sections = HomepageSection::orderBy('sort_order')->orderBy('id')->get();

        return view('admin.homepage-sections.index', compact('sections'));
    }

    public function update(Request $request, HomepageSection $homepageSection)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'max_products' => 'nullable|integer|min:1|max:50',
            'layout' => 'nullable|string|in:grid,carousel,list',
            'config' => 'nullable|array',
        ]);

        $config = $validated['config'] ?? $homepageSection->config ?? [];

        if ($homepageSection->slug === 'flash-sale' && $request->filled('config.ends_at')) {
            $config['ends_at'] = $request->input('config.ends_at');
        }

        if ($homepageSection->slug === 'hero-carousel' && $request->filled('config.slides')) {
            $config['slides'] = $request->input('config.slides');
        }

        if ($homepageSection->slug === 'trust-bar' && $request->filled('config.items')) {
            $config['items'] = $request->input('config.items');
        }

        if ($homepageSection->slug === 'why-choose-us' && $request->filled('config.features')) {
            $config['features'] = $request->input('config.features');
        }

        if ($homepageSection->slug === 'testimonials' && $request->filled('config.testimonials')) {
            $config['testimonials'] = $request->input('config.testimonials');
        }

        if ($homepageSection->slug === 'newsletter-cta' && $request->filled('config')) {
            $config = array_merge($config, $request->only(['bg_image', 'button_text']));
        }

        if ($homepageSection->slug === 'instagram-gallery' && $request->filled('config.images')) {
            $config['images'] = $request->input('config.images');
        }

        $homepageSection->update(array_merge($validated, ['config' => $config]));
        HomepageSection::clearCache();

        return redirect()->route('admin.homepage-sections.index')
            ->with('success', 'Section updated successfully.');
    }

    public function toggleEnabled(HomepageSection $homepageSection)
    {
        $homepageSection->update(['is_enabled' => !$homepageSection->is_enabled]);
        HomepageSection::clearCache();

        return response()->json([
            'is_enabled' => $homepageSection->is_enabled,
            'message' => 'Section ' . ($homepageSection->is_enabled ? 'enabled' : 'disabled') . '.',
        ]);
    }

    public function updateOrder(Request $request)
    {
        $validated = $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:homepage_sections,id',
        ]);

        foreach ($validated['order'] as $index => $id) {
            HomepageSection::where('id', $id)->update(['sort_order' => $index]);
        }

        HomepageSection::clearCache();

        return response()->json(['message' => 'Order updated.']);
    }
}
