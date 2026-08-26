<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NavigationItem;
use App\Models\NavigationMenu;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class NavigationController extends Controller
{
    public function index()
    {
        $menus = NavigationMenu::ordered()->withCount('items')->get();

        return view('admin.navigations.index', compact('menus'));
    }

    public function show(NavigationMenu $navigation)
    {
        $navigation->load(['items' => fn ($q) => $q->with('children')->ordered()]);

        $topItems = $navigation->items->whereNull('parent_id');

        $availableIcons = [
            'dashboard', 'category', 'brand', 'product', 'order', 'customer',
            'homepage', 'banner', 'featured', 'admins', 'users', 'sale',
            'navigation', 'home', 'shop', 'clock', 'fire', 'heart', 'cart',
            'mail', 'info',
        ];

        return view('admin.navigations.show', [
            'menu' => $navigation,
            'topItems' => $topItems,
            'availableIcons' => $availableIcons,
        ]);
    }

    public function storeItem(Request $request, NavigationMenu $navigation)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'nullable|string|max:500',
            'parent_id' => 'nullable|exists:navigation_items,id',
            'icon_key' => 'nullable|string|max:100',
            'target' => 'nullable|string|in:_self,_blank',
            'permission' => 'nullable|string|max:100',
            'badge' => 'nullable|string|max:50',
            'css_class' => 'nullable|string|max:500',
        ]);

        $maxOrder = NavigationItem::where('menu_id', $navigation->id)
            ->where('parent_id', $validated['parent_id'] ?? null)
            ->max('sort_order') ?? -1;

        $validated['menu_id'] = $navigation->id;
        $validated['sort_order'] = $maxOrder + 1;
        $validated['is_enabled'] = true;

        NavigationItem::create($validated);

        $this->clearNavCache();

        return redirect()->route('admin.navigations.show', $navigation)
            ->with('success', 'Item added.');
    }

    public function updateItem(Request $request, NavigationMenu $navigation, NavigationItem $item)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'nullable|string|max:500',
            'icon_key' => 'nullable|string|max:100',
            'target' => 'nullable|string|in:_self,_blank',
            'permission' => 'nullable|string|max:100',
            'badge' => 'nullable|string|max:50',
            'css_class' => 'nullable|string|max:500',
        ]);

        $item->update($validated);

        $this->clearNavCache();

        return redirect()->route('admin.navigations.show', $navigation)
            ->with('success', 'Item updated.');
    }

    public function destroyItem(NavigationMenu $navigation, NavigationItem $item)
    {
        $item->children()->delete();
        $item->delete();

        $this->clearNavCache();

        return redirect()->route('admin.navigations.show', $navigation)
            ->with('success', 'Item deleted.');
    }

    public function toggleItem(NavigationMenu $navigation, NavigationItem $item): JsonResponse
    {
        $item->update(['is_enabled' => !$item->is_enabled]);

        $this->clearNavCache();

        return response()->json(['message' => 'Item toggled.', 'is_enabled' => $item->is_enabled]);
    }

    public function updateOrder(Request $request, NavigationMenu $navigation): JsonResponse
    {
        $validated = $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:navigation_items,id',
        ]);

        foreach ($validated['order'] as $index => $id) {
            NavigationItem::where('id', $id)->update(['sort_order' => $index]);
        }

        $this->clearNavCache();

        return response()->json(['message' => 'Order updated.']);
    }

    public function updateConfig(Request $request, NavigationMenu $navigation)
    {
        $validated = $request->validate([
            'config' => 'required|array',
        ]);

        $navigation->update(['config' => $validated['config']]);

        $this->clearNavCache();

        return redirect()->route('admin.navigations.show', $navigation)
            ->with('success', 'Settings saved.');
    }

    public function destroyMenu(NavigationMenu $navigation)
    {
        $navigation->items()->each(function ($item) {
            $item->children()->delete();
        });
        $navigation->items()->delete();
        $navigation->delete();

        $this->clearNavCache();

        return redirect()->route('admin.navigations.index')
            ->with('success', 'Menu and all its items deleted.');
    }

    public function toggleMenu(NavigationMenu $navigation): JsonResponse
    {
        $navigation->update(['is_enabled' => !$navigation->is_enabled]);

        $this->clearNavCache();

        return response()->json(['message' => 'Menu toggled.', 'is_enabled' => $navigation->is_enabled]);
    }

    private function clearNavCache(): void
    {
        Cache::forget('admin_sidebar_nav');
        Cache::forget('frontend_header_nav');
        Cache::forget('frontend_mobile_nav');
        Cache::forget('frontend_mega_promo');
    }
}
