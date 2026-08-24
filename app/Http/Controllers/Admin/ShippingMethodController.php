<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;

class ShippingMethodController extends Controller
{
    public function index()
    {
        $shippingMethods = ShippingMethod::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.shipping-methods.index', compact('shippingMethods'));
    }

    public function create()
    {
        return view('admin.shipping-methods.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateMethod($request);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['price'] = $validated['price'] ?? 0;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        ShippingMethod::create($validated);

        return redirect()->route('admin.shipping-methods.index')
            ->with('success', 'Shipping method created successfully.');
    }

    public function edit(ShippingMethod $shippingMethod)
    {
        return view('admin.shipping-methods.edit', compact('shippingMethod'));
    }

    public function update(Request $request, ShippingMethod $shippingMethod)
    {
        $validated = $this->validateMethod($request);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['price'] = $validated['price'] ?? 0;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $shippingMethod->update($validated);

        return redirect()->route('admin.shipping-methods.index')
            ->with('success', 'Shipping method updated successfully.');
    }

    public function destroy(ShippingMethod $shippingMethod)
    {
        $shippingMethod->delete();

        return redirect()->route('admin.shipping-methods.index')
            ->with('success', 'Shipping method deleted successfully.');
    }

    protected function validateMethod(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'nullable|numeric|min:0',
            'delivery_estimate' => 'nullable|string|max:100',
            'availability' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);
    }
}
