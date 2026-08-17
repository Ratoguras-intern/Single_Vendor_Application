<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $query = Brand::query();

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

        $brands = $query->latest()->paginate(10)->withQueryString();

        return view('admin.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:brands,slug',
            'logo' => 'nullable|image|mimetypes:image/jpeg,image/png,image/webp,image/avif|mimes:jpg,jpeg,png,webp,avif|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('brands', 'public');
        }

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        Brand::create($validated);

        return redirect()->route('admin.brands.index')
            ->with('success', 'Brand created successfully.');
    }

    public function show(Brand $brand)
    {
        $brand->loadCount('products');

        return view('admin.brands.show', compact('brand'));
    }

    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:brands,slug,'.$brand->id,
            'logo' => 'nullable|image|mimetypes:image/jpeg,image/png,image/webp,image/avif|mimes:jpg,jpeg,png,webp,avif|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        if ($request->boolean('remove_logo')) {
            if ($brand->logo) {
                $disk = Storage::disk('public');
                if ($disk->exists($brand->logo)) {
                    $disk->delete($brand->logo);
                }
            }
            $validated['logo'] = null;
        } elseif ($request->hasFile('logo')) {
            if ($brand->logo) {
                $disk = Storage::disk('public');
                if ($disk->exists($brand->logo)) {
                    $disk->delete($brand->logo);
                }
            }
            $validated['logo'] = $request->file('logo')->store('brands', 'public');
        }

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $brand->update($validated);

        return redirect()->route('admin.brands.index')
            ->with('success', 'Brand updated successfully.');
    }

    public function toggleStatus(Brand $brand)
    {
        $brand->status = $brand->status === 'active' ? 'inactive' : 'active';
        $brand->save();

        return response()->json([
            'status' => $brand->status,
            'message' => 'Brand status updated.',
        ]);
    }

    public function destroy(Brand $brand)
    {
        if ($brand->logo) {
            $disk = Storage::disk('public');
            if ($disk->exists($brand->logo)) {
                $disk->delete($brand->logo);
            }
        }

        $brand->delete();

        return redirect()->route('admin.brands.index')
            ->with('success', 'Brand deleted successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'brand_ids' => ['required', 'array'],
            'brand_ids.*' => ['integer', 'exists:brands,id'],
        ]);

        $brands = Brand::whereIn('id', $validated['brand_ids'])->get();

        foreach ($brands as $brand) {
            if ($brand->logo) {
                $disk = Storage::disk('public');
                if ($disk->exists($brand->logo)) {
                    $disk->delete($brand->logo);
                }
            }
        }

        $count = Brand::whereIn('id', $validated['brand_ids'])->delete();

        return redirect()->route('admin.brands.index')
            ->with('success', "$count brands deleted successfully.");
    }
}
