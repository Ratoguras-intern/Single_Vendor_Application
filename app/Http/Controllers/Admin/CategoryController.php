<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::with(['children', 'parent']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
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

    public function create()
    {
        $parentCategories = Category::topLevel()->active()->ordered()->get();

        return view('admin.categories.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'banner_image' => 'nullable|image|mimetypes:image/jpeg,image/png,image/webp,image/avif|mimes:jpg,jpeg,png,webp,avif|max:4096',
            'thumbnail_image' => 'nullable|image|mimetypes:image/jpeg,image/png,image/webp,image/avif|mimes:jpg,jpeg,png,webp,avif|max:2048',
            'icon' => 'nullable|image|mimetypes:image/jpeg,image/png,image/webp,image/avif|mimes:jpg,jpeg,png,webp,avif|max:1024',
            'sort_order' => 'nullable|integer|min:0',
            'featured' => 'boolean',
            'status' => 'required|in:active,inactive',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['featured'] = $request->boolean('featured');
        $validated['status'] = $request->input('status') === 'active';

        if ($request->hasFile('banner_image')) {
            $validated['banner_image'] = $request->file('banner_image')->store('categories/banners', 'public');
        }
        if ($request->hasFile('thumbnail_image')) {
            $validated['thumbnail_image'] = $request->file('thumbnail_image')->store('categories/thumbnails', 'public');
        }
        if ($request->hasFile('icon')) {
            $validated['icon'] = $request->file('icon')->store('categories/icons', 'public');
        }

        Category::create($validated);
        Cache::forget('frontend_categories');

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

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'banner_image' => 'nullable|image|mimetypes:image/jpeg,image/png,image/webp,image/avif|mimes:jpg,jpeg,png,webp,avif|max:4096',
            'thumbnail_image' => 'nullable|image|mimetypes:image/jpeg,image/png,image/webp,image/avif|mimes:jpg,jpeg,png,webp,avif|max:2048',
            'icon' => 'nullable|image|mimetypes:image/jpeg,image/png,image/webp,image/avif|mimes:jpg,jpeg,png,webp,avif|max:1024',
            'sort_order' => 'nullable|integer|min:0',
            'featured' => 'boolean',
            'status' => 'required|in:active,inactive',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
        ]);

        if ($category->id == ($validated['parent_id'] ?? null)) {
            return back()->withErrors(['parent_id' => 'Category cannot be its own parent.'])->withInput();
        }

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['featured'] = $request->boolean('featured');
        $validated['status'] = $request->input('status') === 'active';

        $disk = Storage::disk('public');

        if ($request->boolean('remove_banner_image')) {
            if ($category->banner_image && $disk->exists($category->banner_image)) {
                $disk->delete($category->banner_image);
            }
            $validated['banner_image'] = null;
        } elseif ($request->hasFile('banner_image')) {
            if ($category->banner_image && $disk->exists($category->banner_image)) {
                $disk->delete($category->banner_image);
            }
            $validated['banner_image'] = $request->file('banner_image')->store('categories/banners', 'public');
        }

        if ($request->boolean('remove_thumbnail_image')) {
            if ($category->thumbnail_image && $disk->exists($category->thumbnail_image)) {
                $disk->delete($category->thumbnail_image);
            }
            $validated['thumbnail_image'] = null;
        } elseif ($request->hasFile('thumbnail_image')) {
            if ($category->thumbnail_image && $disk->exists($category->thumbnail_image)) {
                $disk->delete($category->thumbnail_image);
            }
            $validated['thumbnail_image'] = $request->file('thumbnail_image')->store('categories/thumbnails', 'public');
        }

        if ($request->boolean('remove_icon')) {
            if ($category->icon && $disk->exists($category->icon)) {
                $disk->delete($category->icon);
            }
            $validated['icon'] = null;
        } elseif ($request->hasFile('icon')) {
            if ($category->icon && $disk->exists($category->icon)) {
                $disk->delete($category->icon);
            }
            $validated['icon'] = $request->file('icon')->store('categories/icons', 'public');
        }

        $category->update($validated);
        Cache::forget('frontend_categories');

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        if ($category->children()->count() > 0) {
            return back()->with('error', 'Cannot delete category with subcategories. Remove subcategories first.');
        }

        $disk = Storage::disk('public');

        foreach (['banner_image', 'thumbnail_image', 'icon'] as $field) {
            if ($category->{$field} && $disk->exists($category->{$field})) {
                $disk->delete($category->{$field});
            }
        }

        $category->delete();
        Cache::forget('frontend_categories');

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }

    public function restore($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();
        Cache::forget('frontend_categories');

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category restored successfully.');
    }

    public function toggleStatus(Request $request, Category $category)
    {
        $category->update(['status' => !$category->attributes['status']]);
        Cache::forget('frontend_categories');

        return response()->json([
            'status' => $category->status,
            'message' => 'Category ' . ($category->status === 'active' ? 'activated' : 'deactivated') . '.',
        ]);
    }

    public function toggleFeatured(Request $request, Category $category)
    {
        $category->update(['featured' => !$category->featured]);
        Cache::forget('frontend_categories');

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

        return response()->json(['message' => 'Order updated.']);
    }
}
