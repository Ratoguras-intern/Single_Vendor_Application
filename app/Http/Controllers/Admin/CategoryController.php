<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Models\Category;
use App\Models\FeaturedHomepageCategory;
use App\Services\CategoryImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function __construct(protected CategoryImageService $imageService)
    {
    }

    public function index(Request $request)
    {
        $query = Category::with(['children', 'parent']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "{$search}%")
                    ->orWhere('slug', 'like', "{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status === 'active');
        }

        if ($request->filled('featured')) {
            $query->where('featured', $request->boolean('featured'));
        }

        if ($request->filled('parent')) {
            if ($request->parent === 'top') {
                $query->topLevel();
            } elseif ($request->parent === 'child') {
                $query->whereNotNull('parent_id');
            }
        }

        $query->ordered();

        $topLevel = $query->topLevel()->get();
        $trashed = Category::onlyTrashed()->count();

        if ($request->input('view') === 'flat') {
            $categories = $query->latest()->paginate(15)->withQueryString();
            return view('admin.categories.index', compact('categories', 'topLevel', 'trashed'));
        }

        return view('admin.categories.index', compact('topLevel', 'trashed'));
    }

    public function create(Request $request)
    {
        $parentCategories = Category::topLevel()->active()->ordered()->get();

        $selectedParent = null;
        if ($request->filled('parent')) {
            $selectedParent = $parentCategories->firstWhere('id', $request->integer('parent'));
        }

        return view('admin.categories.create', compact('parentCategories', 'selectedParent'));
    }

    public function store(StoreCategoryRequest $request)
    {
        $validated = $this->prepareValidated($request->validated());

        if ($request->hasFile('image')) {
            $validated = array_merge($validated, $this->imageService->storeMainImage($request->file('image')));
        }

        if ($request->hasFile('banner_image')) {
            $validated = array_merge($validated, $this->imageService->storeBanner(
                $request->file('banner_image'),
                $request->file('banner_mobile_image'),
            ));
        } elseif ($request->hasFile('banner_mobile_image')) {
            $validated['banner_mobile_image'] = $this->imageService->storeMobileBanner($request->file('banner_mobile_image'));
        }

        $validated['banner_image_fit'] = $validated['banner_image_fit'] ?? 'cover';
        $validated['banner_image_position'] = $validated['banner_image_position'] ?? 'center';

        Category::create($validated);
        Cache::forget('frontend_categories');
        \App\Models\FeaturedHomepageCategory::clearCache();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function show(Category $category)
    {
        $category->load(['children.products' => fn ($q) => $q->where('status', true), 'parent']);
        $category->loadCount('products');

        foreach ($category->children as $child) {
            $child->loadCount('products');
        }

        return view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        $parentCategories = Category::topLevel()->active()->ordered()->where('id', '!=', $category->id)->get();

        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $validated = $this->prepareValidated($request->validated());

        // Main image + auto-generated thumbnail.
        if ($request->boolean('remove_image')) {
            $this->imageService->deleteMainImage($category);
            $validated['image'] = null;
            $validated['thumbnail_image'] = null;
        } elseif ($request->hasFile('image')) {
            $this->imageService->deleteMainImage($category);
            $validated = array_merge($validated, $this->imageService->storeMainImage($request->file('image')));
        }

        // Banner (desktop + optional mobile).
        if ($request->boolean('remove_banner_image')) {
            $this->imageService->deleteBanner($category);
            $validated['banner_image'] = null;
            $validated['banner_mobile_image'] = null;
        } elseif ($request->hasFile('banner_image')) {
            $this->imageService->deleteBanner($category);
            $validated = array_merge($validated, $this->imageService->storeBanner(
                $request->file('banner_image'),
                $request->file('banner_mobile_image'),
            ));
        } elseif ($request->hasFile('banner_mobile_image')) {
            $this->imageService->deleteMobileBanner($category);
            $validated['banner_mobile_image'] = $this->imageService->storeMobileBanner($request->file('banner_mobile_image'));
        }

        if ($request->boolean('remove_banner_mobile_image')) {
            $this->imageService->deleteMobileBanner($category);
            $validated['banner_mobile_image'] = null;
        }

        $category->update($validated);
        Cache::forget('frontend_categories');
        \App\Models\FeaturedHomepageCategory::clearCache();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        if ($category->children()->count() > 0) {
            return back()->with('error', 'Cannot delete category with subcategories. Remove subcategories first.');
        }

        $this->imageService->deleteAll($category);

        $category->delete();
        Cache::forget('frontend_categories');
        \App\Models\FeaturedHomepageCategory::clearCache();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'category_ids' => ['required', 'array'],
            'category_ids.*' => ['integer', 'exists:categories,id'],
        ]);

        $categories = Category::withCount('children')->whereIn('id', $validated['category_ids'])->get();

        if ($categories->contains(fn ($category) => $category->children_count > 0)) {
            return response()->json([
                'message' => 'Cannot delete categories with subcategories. Remove subcategories first.',
            ], 422);
        }

        foreach ($categories as $category) {
            $this->imageService->deleteAll($category);
        }

        Category::whereIn('id', $validated['category_ids'])->delete();
        Cache::forget('frontend_categories');
        \App\Models\FeaturedHomepageCategory::clearCache();

        $count = count($validated['category_ids']);

        return redirect()->route('admin.categories.index')
            ->with('success', "$count categories deleted successfully.");
    }

    public function restore($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();
        Cache::forget('frontend_categories');
        \App\Models\FeaturedHomepageCategory::clearCache();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category restored successfully.');
    }

    public function toggleStatus(Request $request, Category $category)
    {
        $category->update(['status' => $category->attributes['status'] ? 'inactive' : 'active']);
        Cache::forget('frontend_categories');
        \App\Models\FeaturedHomepageCategory::clearCache();

        return response()->json([
            'status' => $category->status,
            'message' => 'Category ' . ($category->status === 'active' ? 'activated' : 'deactivated') . '.',
        ]);
    }

    public function toggleFeatured(Request $request, Category $category)
    {
        $category->update(['featured' => !$category->featured]);
        Cache::forget('frontend_categories');
        \App\Models\FeaturedHomepageCategory::clearCache();

        return response()->json([
            'featured' => $category->featured,
            'message' => 'Category ' . ($category->featured ? 'marked as featured' : 'removed from featured') . '.',
        ]);
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'required|integer|exists:categories,id',
        ]);

        foreach ($request->input('order') as $index => $id) {
            Category::where('id', $id)->update(['sort_order' => $index]);
        }
        Cache::forget('frontend_categories');
        \App\Models\FeaturedHomepageCategory::clearCache();

        return response()->json(['message' => 'Order updated.']);
    }

    protected function prepareValidated(array $validated): array
    {
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        return $validated;
    }
}
