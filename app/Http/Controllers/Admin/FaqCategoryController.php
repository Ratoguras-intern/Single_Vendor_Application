<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FaqCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FaqCategoryController extends Controller
{
    public function index()
    {
        $categories = FaqCategory::query()
            ->withCount('faqs')
            ->ordered()
            ->paginate(15)
            ->withQueryString();

        return view('admin.faq-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.faq-categories.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);

        $validated['is_active'] = $request->boolean('is_active');

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        FaqCategory::create($validated);

        return redirect()->route('admin.faq-categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(FaqCategory $faqCategory)
    {
        return view('admin.faq-categories.edit', compact('faqCategory'));
    }

    public function update(Request $request, FaqCategory $faqCategory)
    {
        $validated = $this->validateData($faqCategory, $request);

        $validated['is_active'] = $request->boolean('is_active');

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $faqCategory->update($validated);

        return redirect()->route('admin.faq-categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(FaqCategory $faqCategory)
    {
        if ($faqCategory->faqs()->exists()) {
            return redirect()->route('admin.faq-categories.index')
                ->with('error', 'Category has FAQs. Move or delete them first.');
        }

        $faqCategory->delete();

        return redirect()->route('admin.faq-categories.index')
            ->with('success', 'Category deleted successfully.');
    }

    protected function validateData(?FaqCategory $faqCategory, Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:faq_categories,slug'.($faqCategory ? ','.$faqCategory->id : ''),
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);
    }
}
