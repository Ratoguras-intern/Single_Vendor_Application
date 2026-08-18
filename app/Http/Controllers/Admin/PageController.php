<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $query = Page::query();

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

        if ($request->filled('footer_section')) {
            $query->where('footer_section', $request->footer_section);
        }

        if ($request->filled('show_in_footer')) {
            $query->where('show_in_footer', $request->show_in_footer === '1');
        }

        $pages = $query->latest()->paginate(15)->withQueryString();

        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug',
            'footer_section' => 'nullable|in:customer-care,company,legal',
            'short_description' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'featured_image' => 'nullable|image|mimetypes:image/jpeg,image/png,image/webp,image/avif|mimes:jpg,jpeg,png,webp,avif|max:5120',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published',
            'show_in_footer' => 'nullable|boolean',
            'footer_order' => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('pages', 'public');
        }

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $validated['show_in_footer'] = $request->boolean('show_in_footer');
        $validated['footer_order'] = $validated['footer_order'] ?? 0;

        Page::create($validated);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page created successfully.');
    }

    public function show(Page $page)
    {
        return view('admin.pages.show', compact('page'));
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug,'.$page->id,
            'footer_section' => 'nullable|in:customer-care,company,legal',
            'short_description' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'featured_image' => 'nullable|image|mimetypes:image/jpeg,image/png,image/webp,image/avif|mimes:jpg,jpeg,png,webp,avif|max:5120',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published',
            'show_in_footer' => 'nullable|boolean',
            'footer_order' => 'nullable|integer|min:0',
        ]);

        if ($request->boolean('remove_featured_image')) {
            if ($page->featured_image) {
                $disk = Storage::disk('public');
                if ($disk->exists($page->featured_image)) {
                    $disk->delete($page->featured_image);
                }
            }
            $validated['featured_image'] = null;
        } elseif ($request->hasFile('featured_image')) {
            if ($page->featured_image) {
                $disk = Storage::disk('public');
                if ($disk->exists($page->featured_image)) {
                    $disk->delete($page->featured_image);
                }
            }
            $validated['featured_image'] = $request->file('featured_image')->store('pages', 'public');
        }

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $validated['show_in_footer'] = $request->boolean('show_in_footer');
        $validated['footer_order'] = $validated['footer_order'] ?? 0;

        $page->update($validated);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page updated successfully.');
    }

    public function toggleStatus(Page $page)
    {
        $page->status = $page->status === 'published' ? 'draft' : 'published';
        $page->save();

        return response()->json([
            'status' => $page->status,
            'message' => 'Page status updated.',
        ]);
    }

    public function destroy(Page $page)
    {
        if ($page->featured_image) {
            $disk = Storage::disk('public');
            if ($disk->exists($page->featured_image)) {
                $disk->delete($page->featured_image);
            }
        }

        $page->delete();

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page deleted successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'page_ids' => ['required', 'array'],
            'page_ids.*' => ['integer', 'exists:pages,id'],
        ]);

        $pages = Page::whereIn('id', $validated['page_ids'])->get();

        foreach ($pages as $page) {
            if ($page->featured_image) {
                $disk = Storage::disk('public');
                if ($disk->exists($page->featured_image)) {
                    $disk->delete($page->featured_image);
                }
            }
        }

        $count = Page::whereIn('id', $validated['page_ids'])->delete();

        return redirect()->route('admin.pages.index')
            ->with('success', "$count pages deleted successfully.");
    }
}
