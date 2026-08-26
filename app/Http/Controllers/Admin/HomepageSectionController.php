<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSection;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

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
            'config.slides' => 'nullable|array',
            'config.slides.*.image' => 'nullable|string|max:2048',
            'config.slides.*.image_path' => 'nullable|string|max:255',
            'config.slides.*.image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp,avif,gif|max:10240',
            'config.slides.*.remove_image' => 'nullable|boolean',
            'config.slides.*.brightness' => 'nullable|integer|min:0|max:200',
            'config.slides.*.overlay_enabled' => 'nullable|boolean',
            'config.slides.*.overlay_opacity' => 'nullable|integer|min:0|max:100',
            'config.slides.*.overlay_color' => 'nullable|string|regex:/^#[0-9a-fA-F]{6}$/',
        ]);

        $config = $homepageSection->config ?? [];

        match ($homepageSection->slug) {
            'flash-sale' => $this->updateFlashSale($config, $request),
            'hero-carousel' => $this->updateHeroCarousel($config, $request),
            'sale-banner' => $this->updateSaleBanner($config, $request),
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
        $homepageSection->update(['is_enabled' => ! $homepageSection->is_enabled]);
        HomepageSection::clearCache();

        return response()->json([
            'is_enabled' => $homepageSection->is_enabled,
            'message' => 'Section '.($homepageSection->is_enabled ? 'enabled' : 'disabled').'.',
        ]);
    }

    public function destroy(HomepageSection $homepageSection)
    {
        $homepageSection->delete();
        HomepageSection::clearCache();

        return redirect()->route('admin.homepage-sections.index')
            ->with('success', 'Section deleted.');
    }

    public function updateOrder(Request $request)
    {
        $validated = $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:homepage_sections,id',
        ]);

        $oldOrder = HomepageSection::pluck('id')->map(fn ($id) => (int) $id)->values()->toArray();

        foreach ($validated['order'] as $index => $id) {
            HomepageSection::where('id', $id)->update(['sort_order' => $index]);
        }

        HomepageSection::clearCache();

        return response()->json(['message' => 'Section order updated.']);
    }

    private function updateFlashSale(array &$config, Request $request): void
    {
        if ($request->filled('config.ends_at')) {
            $config['ends_at'] = $request->input('config.ends_at');
        }
    }

    private function updateHeroCarousel(array &$config, Request $request): void
    {
        $slides = $request->input('config.slides', []);
        $files = $request->file('config.slides');

        if (empty($slides)) {
            foreach ((array) ($files ?? []) as $file) {
                if ($file instanceof UploadedFile && $file->isValid()) {
                    Storage::disk('public')->delete($file);
                }
            }

            return;
        }

        $storedPaths = collect($config['slides'] ?? [])
            ->pluck('image_path')
            ->filter()
            ->values()
            ->all();

        $newSlides = [];

        foreach ($slides as $index => $slide) {
            $imagePath = $slide['image_path'] ?? null;

            if (! empty($slide['remove_image'])) {
                if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
                $slide['image_path'] = null;
                $slide['image'] = null;
            } elseif (isset($files[$index]) && $files[$index] instanceof UploadedFile && $files[$index]->isValid()) {
                if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
                $slide['image_path'] = $files[$index]->store('homepage/hero', 'public');
                $slide['image'] = null;
            }

            $slide['image_path'] = $slide['image_path'] ?? null;
            $slide['image'] = $slide['image'] ?? null;
            $slide['brightness'] = isset($slide['brightness']) && $slide['brightness'] !== '' ? (int) $slide['brightness'] : 100;
            $slide['overlay_enabled'] = ! empty($slide['overlay_enabled']);
            $slide['overlay_opacity'] = isset($slide['overlay_opacity']) && $slide['overlay_opacity'] !== '' ? (int) $slide['overlay_opacity'] : 40;
            $slide['overlay_color'] = $slide['overlay_color'] ?? '#000000';

            unset($slide['image_file'], $slide['remove_image']);

            $newSlides[] = $slide;
        }

        $referenced = collect($newSlides)->pluck('image_path')->filter()->values()->all();

        foreach (array_diff($storedPaths, $referenced) as $path) {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        $config['slides'] = $newSlides;
    }

    private function updateTrustBar(array &$config, Request $request): void
    {
        if ($request->filled('config.items')) {
            $config['items'] = $request->input('config.items');
        }
    }

    private function updateSaleBanner(array &$config, Request $request): void
    {
        if ($request->filled('config.transition_speed')) {
            $config['transition_speed'] = max(2000, min(30000, (int) $request->input('config.transition_speed')));
        }
        $config['autoplay'] = (bool) $request->input('config.autoplay');
        $config['pause_on_hover'] = (bool) $request->input('config.pause_on_hover');
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
