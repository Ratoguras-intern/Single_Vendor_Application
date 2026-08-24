<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\FaqCategory;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $faqs = Faq::query()
            ->with('category')
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('question', 'like', '%'.$request->search.'%');
            })
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->where('faq_category_id', $request->category);
            })
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        $categories = FaqCategory::ordered()->get(['id', 'name']);

        return view('admin.faqs.index', compact('faqs', 'categories'));
    }

    public function create()
    {
        $categories = FaqCategory::ordered()->get(['id', 'name']);

        return view('admin.faqs.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);

        $validated['is_published'] = $request->boolean('is_published');

        Faq::create($validated);

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ created successfully.');
    }

    public function edit(Faq $faq)
    {
        $categories = FaqCategory::ordered()->get(['id', 'name']);

        return view('admin.faqs.edit', compact('faq', 'categories'));
    }

    public function update(Request $request, Faq $faq)
    {
        $validated = $this->validateData($request);

        $validated['is_published'] = $request->boolean('is_published');

        $faq->update($validated);

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ updated successfully.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ deleted successfully.');
    }

    protected function validateData(Request $request): array
    {
        return $request->validate([
            'faq_category_id' => 'required|exists:faq_categories,id',
            'question' => 'required|string|max:500',
            'answer' => 'required|string|max:5000',
            'sort_order' => 'nullable|integer|min:0',
            'is_published' => 'boolean',
        ]);
    }
}
