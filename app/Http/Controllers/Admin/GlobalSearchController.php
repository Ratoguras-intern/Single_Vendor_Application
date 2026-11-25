<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Faq;
use App\Models\FaqCategory;
use App\Models\HomepageSection;
use App\Models\Job;
use App\Models\Order;
use App\Models\OrderReturn;
use App\Models\Page;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PressRelease;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\SaleBanner;
use App\Models\ShippingMethod;
use App\Models\User;
use Illuminate\Http\Request;

class GlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = trim((string) $request->input('q', ''));

        if (strlen($query) < 2) {
            return response()->json(['html' => '']);
        }

        $term = '%' . $query . '%';
        $results = collect();

        // Products
        $products = Product::query()
            ->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                    ->orWhere('sku', 'like', $term)
                    ->orWhere('slug', 'like', $term);
            })
            ->limit(5)
            ->get(['id', 'name', 'slug', 'price', 'sku', 'status']);
        if ($products->isNotEmpty()) {
            $results->put('products', $products);
        }

        // Orders
        $orders = Order::query()
            ->with('user:id,name')
            ->where(function ($q) use ($term) {
                $q->where('order_number', 'like', $term);
            })
            ->orWhereHas('user', function ($q) use ($term) {
                $q->where('name', 'like', $term);
            })
            ->limit(5)
            ->get(['id', 'order_number', 'total_amount', 'status', 'user_id']);
        if ($orders->isNotEmpty()) {
            $results->put('orders', $orders);
        }

        // Customers
        $customers = User::query()
            ->where('role', 'customer')
            ->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                    ->orWhere('email', 'like', $term)
                    ->orWhere('phone', 'like', $term);
            })
            ->limit(5)
            ->get(['id', 'name', 'email', 'phone', 'status', 'is_frozen']);
        if ($customers->isNotEmpty()) {
            $results->put('customers', $customers);
        }

        // Categories
        $categories = Category::query()
            ->where('name', 'like', $term)
            ->limit(5)
            ->get(['id', 'name', 'slug', 'status']);
        if ($categories->isNotEmpty()) {
            $results->put('categories', $categories);
        }

        // Brands
        $brands = Brand::query()
            ->where('name', 'like', $term)
            ->limit(5)
            ->get(['id', 'name', 'slug', 'status']);
        if ($brands->isNotEmpty()) {
            $results->put('brands', $brands);
        }

        // Pages
        $pages = Page::query()
            ->where('title', 'like', $term)
            ->limit(5)
            ->get(['id', 'title', 'slug', 'status']);
        if ($pages->isNotEmpty()) {
            $results->put('pages', $pages);
        }

        // Product Reviews
        $reviews = ProductReview::query()
            ->with('product:id,name')
            ->with('user:id,name')
            ->where(function ($q) use ($term) {
                $q->where('title', 'like', $term)
                    ->orWhere('comment', 'like', $term);
            })
            ->orWhereHas('product', function ($q) use ($term) {
                $q->where('name', 'like', $term);
            })
            ->orWhereHas('user', function ($q) use ($term) {
                $q->where('name', 'like', $term);
            })
            ->limit(5)
            ->get(['id', 'product_id', 'user_id', 'rating', 'title', 'comment', 'status']);
        if ($reviews->isNotEmpty()) {
            $results->put('reviews', $reviews);
        }

        // Returns
        $returns = OrderReturn::query()
            ->with('user:id,name')
            ->where(function ($q) use ($term) {
                $q->where('return_number', 'like', $term)
                    ->orWhere('tracking_number', 'like', $term)
                    ->orWhere('carrier', 'like', $term);
            })
            ->orWhereHas('user', function ($q) use ($term) {
                $q->where('name', 'like', $term);
            })
            ->limit(5)
            ->get(['id', 'return_number', 'tracking_number', 'status', 'user_id']);
        if ($returns->isNotEmpty()) {
            $results->put('returns', $returns);
        }

        // FAQs
        $faqs = Faq::query()
            ->where(function ($q) use ($term) {
                $q->where('question', 'like', $term)
                    ->orWhere('answer', 'like', $term);
            })
            ->limit(5)
            ->get(['id', 'question', 'is_published']);
        if ($faqs->isNotEmpty()) {
            $results->put('faqs', $faqs);
        }

        // FAQ Categories
        $faqCategories = FaqCategory::query()
            ->where('name', 'like', $term)
            ->limit(5)
            ->get(['id', 'name', 'slug', 'is_active']);
        if ($faqCategories->isNotEmpty()) {
            $results->put('faq-categories', $faqCategories);
        }

        // Blog Posts
        $posts = Post::query()
            ->where(function ($q) use ($term) {
                $q->where('title', 'like', $term)
                    ->orWhere('excerpt', 'like', $term);
            })
            ->limit(5)
            ->get(['id', 'title', 'slug', 'status']);
        if ($posts->isNotEmpty()) {
            $results->put('posts', $posts);
        }

        // Post Categories
        $postCategories = PostCategory::query()
            ->where('name', 'like', $term)
            ->limit(5)
            ->get(['id', 'name', 'slug']);
        if ($postCategories->isNotEmpty()) {
            $results->put('post-categories', $postCategories);
        }

        // Jobs
        $jobs = Job::query()
            ->where(function ($q) use ($term) {
                $q->where('title', 'like', $term)
                    ->orWhere('department', 'like', $term)
                    ->orWhere('location', 'like', $term);
            })
            ->limit(5)
            ->get(['id', 'title', 'department', 'location', 'status']);
        if ($jobs->isNotEmpty()) {
            $results->put('jobs', $jobs);
        }

        // Press Releases
        $pressReleases = PressRelease::query()
            ->where(function ($q) use ($term) {
                $q->where('title', 'like', $term)
                    ->orWhere('summary', 'like', $term);
            })
            ->limit(5)
            ->get(['id', 'title', 'slug', 'status']);
        if ($pressReleases->isNotEmpty()) {
            $results->put('press-releases', $pressReleases);
        }

        // Shipping Methods
        $shippingMethods = ShippingMethod::query()
            ->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                    ->orWhere('description', 'like', $term);
            })
            ->limit(5)
            ->get(['id', 'name', 'description', 'price', 'is_active']);
        if ($shippingMethods->isNotEmpty()) {
            $results->put('shipping-methods', $shippingMethods);
        }

        // Contact Messages
        $contactMessages = ContactMessage::query()
            ->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                    ->orWhere('email', 'like', $term)
                    ->orWhere('subject', 'like', $term)
                    ->orWhere('message', 'like', $term);
            })
            ->limit(5)
            ->get(['id', 'name', 'email', 'subject', 'status']);
        if ($contactMessages->isNotEmpty()) {
            $results->put('contact-messages', $contactMessages);
        }

        // Banners
        $banners = Banner::query()
            ->where(function ($q) use ($term) {
                $q->where('title', 'like', $term)
                    ->orWhere('subtitle', 'like', $term)
                    ->orWhere('badge', 'like', $term);
            })
            ->limit(5)
            ->get(['id', 'title', 'badge', 'position', 'is_enabled']);
        if ($banners->isNotEmpty()) {
            $results->put('banners', $banners);
        }

        // Sale Banners
        $saleBanners = SaleBanner::query()
            ->where(function ($q) use ($term) {
                $q->where('title', 'like', $term)
                    ->orWhere('subtitle', 'like', $term)
                    ->orWhere('badge', 'like', $term);
            })
            ->limit(5)
            ->get(['id', 'title', 'badge', 'is_enabled']);
        if ($saleBanners->isNotEmpty()) {
            $results->put('sale-banners', $saleBanners);
        }

        // Homepage Sections
        $homepageSections = HomepageSection::query()
            ->where(function ($q) use ($term) {
                $q->where('title', 'like', $term)
                    ->orWhere('subtitle', 'like', $term)
                    ->orWhere('slug', 'like', $term);
            })
            ->limit(5)
            ->get(['id', 'title', 'slug', 'is_enabled']);
        if ($homepageSections->isNotEmpty()) {
            $results->put('homepage-sections', $homepageSections);
        }

        if ($results->isEmpty()) {
            return response()->json(['html' => view('admin.search._no-results', ['query' => $query])->render()]);
        }

        $html = view('admin.search._results', ['results' => $results, 'query' => $query])->render();

        return response()->json(['html' => $html]);
    }
}