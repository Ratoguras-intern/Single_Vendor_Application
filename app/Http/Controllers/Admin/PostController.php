<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\InteractsWithMedia;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    use InteractsWithMedia;

    public function index(Request $request)
    {
        $query = Post::query()->with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('post_category_id', $request->category);
        }

        $posts = $query->latest('published_at')->paginate(12)->withQueryString();
        $categories = PostCategory::orderBy('name')->get();

        return view('admin.posts.index', compact('posts', 'categories'));
    }

    public function create()
    {
        $categories = PostCategory::orderBy('name')->get();

        return view('admin.posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $this->validatePost($request);

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('posts', 'public');
        } elseif ($path = $this->mediaPath($request, 'featured_image')) {
            $validated['featured_image'] = $path;
        }

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $validated['author_id'] = auth()->id();
        $validated['author_name'] = $validated['author_name'] ?? auth()->user()?->name;
        $validated['is_featured'] = $request->boolean('is_featured');

        $validated['content'] = app(\App\Services\HtmlSanitizer::class)->clean((string) ($validated['content'] ?? ''));

        if (($validated['status'] ?? null) === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        Post::create($validated);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post created successfully.');
    }

    public function edit(Post $post)
    {
        $categories = PostCategory::orderBy('name')->get();

        return view('admin.posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $this->validatePost($request, $post);

        if ($request->boolean('remove_featured_image')) {
            $this->deleteImageSafe($post->featured_image);
            $validated['featured_image'] = null;
        } elseif ($request->hasFile('featured_image')) {
            $this->deleteImageSafe($post->featured_image);
            $validated['featured_image'] = $request->file('featured_image')->store('posts', 'public');
        } elseif ($path = $this->mediaPath($request, 'featured_image')) {
            $this->deleteImageSafe($post->featured_image);
            $validated['featured_image'] = $path;
        }

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $validated['is_featured'] = $request->boolean('is_featured');

        $validated['content'] = app(\App\Services\HtmlSanitizer::class)->clean((string) ($validated['content'] ?? ''));

        if (($validated['status'] ?? null) === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $post->update($validated);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
        $this->deleteImageSafe($post->featured_image);

        $post->delete();

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post deleted successfully.');
    }

    protected function validatePost(Request $request, ?Post $post = null): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'post_category_id' => 'nullable|exists:post_categories,id',
            'slug' => 'nullable|string|max:255|unique:posts,slug'.($post ? ','.$post->id : ''),
            'excerpt' => 'nullable|string|max:1000',
            'content' => 'nullable|string',
            'featured_image' => 'nullable|image|mimetypes:image/jpeg,image/png,image/webp,image/avif|mimes:jpg,jpeg,png,webp,avif|max:5120',
            'featured_image_media_id' => 'nullable|integer|exists:media,id',
            'author_name' => 'nullable|string|max:255',
            'is_featured' => 'nullable|boolean',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
        ]);
    }
}
