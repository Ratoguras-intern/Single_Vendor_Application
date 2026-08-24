<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostCategoryController extends Controller
{
    public function index()
    {
        $categories = PostCategory::query()
            ->withCount('posts')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.post-categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateCategory($request);

        if (empty($validated['slug'])) {
            $validated['slug'] = $this->uniqueSlug(Str::slug($validated['name']));
        }

        PostCategory::create($validated);

        return redirect()->route('admin.post-categories.index')
            ->with('success', 'Post category created successfully.');
    }

    public function edit(PostCategory $postCategory)
    {
        return view('admin.post-categories.edit', compact('postCategory'));
    }

    public function update(Request $request, PostCategory $postCategory)
    {
        $validated = $this->validateCategory($request, $postCategory);

        if (empty($validated['slug'])) {
            $validated['slug'] = $this->uniqueSlug(Str::slug($validated['name']), $postCategory->id);
        }

        $postCategory->update($validated);

        return redirect()->route('admin.post-categories.index')
            ->with('success', 'Post category updated successfully.');
    }

    public function destroy(PostCategory $postCategory)
    {
        if ($postCategory->posts()->exists()) {
            return redirect()->route('admin.post-categories.index')
                ->with('error', 'This category has posts assigned and cannot be deleted.');
        }

        $postCategory->delete();

        return redirect()->route('admin.post-categories.index')
            ->with('success', 'Post category deleted successfully.');
    }

    protected function validateCategory(Request $request, ?PostCategory $category = null): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:post_categories,slug'.($category ? ','.$category->id : ''),
        ]);
    }

    protected function uniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $slug = $base !== '' ? $base : 'category';
        $attempt = $slug;
        $counter = 2;

        while (PostCategory::where('slug', $attempt)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $attempt = $slug.'-'.$counter++;
        }

        return $attempt;
    }
}
