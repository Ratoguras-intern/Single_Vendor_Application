<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NavigationItem;
use App\Models\NavigationMenu;
use App\Models\Product;
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

        $products = Product::active()->orderBy('name')->get(['id', 'name']);

        return view('admin.navigations.show', [
            'menu' => $navigation,
            'topItems' => $topItems,
            'availableIcons' => $availableIcons,
            'products' => $products,
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

    public function storeSlide(Request $request, NavigationMenu $navigation)
    {
        $data = $this->validateSlide($request);
        $slides = $navigation->getConfig('slides', []);

        $data['enabled'] = true;
        $data['sort_order'] = count($slides);

        $slides[] = $data;
        $navigation->update(['config' => array_merge($navigation->getConfig(), ['slides' => $slides])]);

        $this->clearNavCache();

        return redirect()->route('admin.navigations.show', $navigation)
            ->with('success', 'Slide added.');
    }

    public function updateSlide(Request $request, NavigationMenu $navigation, int $index)
    {
        $data = $this->validateSlide($request);
        $slides = $navigation->getConfig('slides', []);

        if (!isset($slides[$index])) {
            abort(404, 'Slide not found.');
        }

        $data['enabled'] = $slides[$index]['enabled'] ?? true;
        $data['sort_order'] = $slides[$index]['sort_order'] ?? $index;
        $slides[$index] = $data;

        $navigation->update(['config' => array_merge($navigation->getConfig(), ['slides' => $slides])]);

        $this->clearNavCache();

        return redirect()->route('admin.navigations.show', $navigation)
            ->with('success', 'Slide updated.');
    }

    public function toggleSlide(NavigationMenu $navigation, int $index): JsonResponse
    {
        $slides = $navigation->getConfig('slides', []);

        if (!isset($slides[$index])) {
            return response()->json(['message' => 'Slide not found.'], 404);
        }

        $slides[$index]['enabled'] = !($slides[$index]['enabled'] ?? true);
        $navigation->update(['config' => array_merge($navigation->getConfig(), ['slides' => $slides])]);

        $this->clearNavCache();

        return response()->json(['message' => 'Slide toggled.', 'enabled' => $slides[$index]['enabled']]);
    }

    public function destroySlide(NavigationMenu $navigation, int $index)
    {
        $slides = $navigation->getConfig('slides', []);

        if (isset($slides[$index])) {
            unset($slides[$index]);
            $slides = array_values($slides);
            $navigation->update(['config' => array_merge($navigation->getConfig(), ['slides' => $slides])]);

            $this->clearNavCache();
        }

        return redirect()->route('admin.navigations.show', $navigation)
            ->with('success', 'Slide deleted.');
    }

    public function resetSlides(NavigationMenu $navigation)
    {
        $defaults = [
            ['enabled' => true, 'data_scope' => 'featured', 'badge' => 'Curated', 'heading' => 'Curated Favorites', 'description' => 'Handpicked products selected for quality.', 'cta_text' => 'Shop Now', 'cta_url' => '', 'product_id' => null, 'image' => null],
            ['enabled' => true, 'data_scope' => 'onSale', 'badge' => 'Exclusive', 'heading' => 'Exclusive Deals', 'description' => 'Limited-time offers and special promotions.', 'cta_text' => 'View Deals', 'cta_url' => '', 'product_id' => null, 'image' => null],
            ['enabled' => true, 'data_scope' => 'trending', 'badge' => 'Trending', 'heading' => 'Trending Now', 'description' => 'Products currently getting attention from customers.', 'cta_text' => 'Explore Trending', 'cta_url' => '', 'product_id' => null, 'image' => null],
            ['enabled' => true, 'data_scope' => 'bestSeller', 'badge' => 'Best Selling', 'heading' => 'Best Sellers', 'description' => 'Popular products customers are buying most.', 'cta_text' => 'Shop Best Sellers', 'cta_url' => '', 'product_id' => null, 'image' => null],
        ];

        $navigation->update(['config' => array_merge($navigation->getConfig(), ['slides' => $defaults])]);

        $this->clearNavCache();

        return redirect()->route('admin.navigations.show', $navigation)
            ->with('success', 'Slides reset to defaults.');
    }

    public function reorderSlides(Request $request, NavigationMenu $navigation): JsonResponse
    {
        $validated = $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|min:0',
        ]);

        $slides = $navigation->getConfig('slides', []);
        $reordered = [];

        foreach ($validated['order'] as $index) {
            if (isset($slides[$index])) {
                $reordered[] = $slides[$index];
            }
        }

        $navigation->update(['config' => array_merge($navigation->getConfig(), ['slides' => $reordered])]);

        $this->clearNavCache();

        return response()->json(['message' => 'Slide order updated.']);
    }

    private function validateSlide(Request $request): array
    {
        return $request->validate([
            'badge' => 'nullable|string|max:100',
            'heading' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'cta_text' => 'nullable|string|max:100',
            'cta_url' => 'nullable|string|max:500',
            'data_scope' => 'nullable|string|max:50',
            'product_id' => 'nullable|integer|min:0',
            'image' => 'nullable|string|max:500',
        ]);
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
        Cache::forget('frontend_mega_promo_slides');
    }
}
