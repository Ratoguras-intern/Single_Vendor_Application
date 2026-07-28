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

    public function show(HomepageSection $homepageSection)
    {
        return view('admin.homepage-sections.show', ['section' => $homepageSection]);
    }

    public function update(Request $request, HomepageSection $homepageSection)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'max_products' => 'nullable|integer|min:1|max:50',
            'layout' => 'nullable|string|in:grid,carousel,list',
        ]);

        $config = $homepageSection->config ?? [];

        match ($homepageSection->slug) {
            'flash-sale' => $this->updateFlashSale($config, $request),
            'hero-carousel' => $this->updateHeroCarousel($config, $request),
            'trust-bar' => $this->updateTrustBar($config, $request),
            'why-choose-us' => $this->updateWhyChooseUs($config, $request),
            'testimonials' => $this->updateTestimonials($config, $request),
            'newsletter-cta' => $this->updateNewsletterCta($config, $request),
            'instagram-gallery' => $this->updateInstagramGallery($config, $request),
            'premium-footer' => $this->updatePremiumFooter($config, $request),
            default => null,
        };

        $homepageSection->update(array_merge($validated, ['config' => $config]));
        HomepageSection::clearCache();

        return redirect()->route('admin.homepage-sections.show', $homepageSection)
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

    private function updateFlashSale(array &$config, Request $request): void
    {
        if ($request->filled('config.ends_at')) {
            $config['ends_at'] = $request->input('config.ends_at');
        }
    }

    private function updateHeroCarousel(array &$config, Request $request): void
    {
        if ($request->filled('config.slides')) {
            $config['slides'] = $request->input('config.slides');
        }
    }

    private function updateTrustBar(array &$config, Request $request): void
    {
        if ($request->filled('config.items')) {
            $config['items'] = $request->input('config.items');
        }
    }

    private function updateWhyChooseUs(array &$config, Request $request): void
    {
        if ($request->filled('config.features')) {
            $config['features'] = $request->input('config.features');
        }
    }

    private function updateTestimonials(array &$config, Request $request): void
    {
        if ($request->filled('config.testimonials')) {
            $config['testimonials'] = $request->input('config.testimonials');
        }
    }

    private function updateNewsletterCta(array &$config, Request $request): void
    {
        if ($request->filled('config.bg_image')) {
            $config['bg_image'] = $request->input('config.bg_image');
        }
        if ($request->filled('config.button_text')) {
            $config['button_text'] = $request->input('config.button_text');
        }
    }

    private function updateInstagramGallery(array &$config, Request $request): void
    {
        if ($request->filled('config.images')) {
            $config['images'] = $request->input('config.images');
        }
    }

    private function updatePremiumFooter(array &$config, Request $request): void
    {
        $fields = ['company_name', 'company_description', 'address', 'phone', 'email', 'social_links', 'footer_columns', 'copyright_text'];
        foreach ($fields as $field) {
            if ($request->filled("config.{$field}")) {
                $config[$field] = $request->input("config.{$field}");
            }
        }
    }
}
