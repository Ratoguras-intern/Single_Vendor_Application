<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\FaqCategory;
use App\Models\Job;
use App\Models\Page;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PressRelease;
use App\Models\Product;
use App\Models\ShippingMethod;
use App\Models\User;
use App\Services\HtmlSanitizer;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show(string $slug, HtmlSanitizer $sanitizer)
    {
        $page = Page::where('slug', $slug)->published()->firstOrFail();

        $data = array_merge($this->seoData($page), [
            'page' => $page,
            'content' => $sanitizer->clean($page->content),
        ]);

        return match ($page->template) {
            'contact' => $this->renderContact($data),
            'help-center' => $this->renderHelpCenter($data),
            'shipping-info' => $this->renderShippingInfo($data),
            'returns' => $this->renderReturns($data),
            'about' => $this->renderAbout($data),
            'careers' => $this->renderCareers($data),
            'blog' => $this->renderBlog($data),
            'press' => $this->renderPress($data),
            default => response()->view('frontend.page', $data),
        };
    }

    public function blogPost(Post $post)
    {
        abort_unless($post->status === 'published' && $post->published_at?->lte(now()), 404);

        $related = Post::latestPublished()
            ->published()
            ->where('id', '!=', $post->id)
            ->when($post->post_category_id, fn ($q) => $q->where('post_category_id', $post->post_category_id))
            ->take(3)
            ->get();

        if ($related->isEmpty()) {
            $related = Post::latestPublished()->where('id', '!=', $post->id)->take(3)->get();
        }

        return view('frontend.blog-post', [
            'post' => $post,
            'content' => app(HtmlSanitizer::class)->clean($post->content),
            'related' => $related,
            'seoTitle' => $post->seo_title ?: $post->title,
            'seoDescription' => $post->seo_description ?: \Illuminate\Support\Str::limit($post->excerpt ?: $post->title),
        ]);
    }

    public function career(Job $job)
    {
        abort_unless($job->status === 'published', 404);

        $otherJobs = Job::published()->where('id', '!=', $job->id)->take(3)->get();

        return view('frontend.career-details', [
            'job' => $job,
            'otherJobs' => $otherJobs,
            'seoTitle' => "{$job->title} - Careers",
            'seoDescription' => \Illuminate\Support\Str::limit(strip_tags((string) $job->description)),
        ]);
    }

    public function pressRelease(PressRelease $release)
    {
        abort_unless($release->status === 'published', 404);

        $recent = PressRelease::published()->where('id', '!=', $release->id)->take(3)->get();

        return view('frontend.press-release', [
            'release' => $release,
            'content' => app(HtmlSanitizer::class)->clean($release->content),
            'recent' => $recent,
            'seoTitle' => $release->seo_title ?: $release->title,
            'seoDescription' => $release->seo_description ?: \Illuminate\Support\Str::limit($release->summary ?? ''),
        ]);
    }

    protected function renderContact(array $data)
    {
        $footerConfig = \App\Models\HomepageSection::getCached()->get('premium-footer')?->config ?? [];

        return view('frontend.contact', array_merge($data, [
            'companyContact' => [
                'email' => $footerConfig['email'] ?? null,
                'phone' => $footerConfig['phone'] ?? null,
                'address' => $footerConfig['address'] ?? null,
                'hours' => \App\Models\Setting::get('contact.business_hours'),
                'response_time' => \App\Models\Setting::get('contact.response_time'),
            ],
            'popularFaqs' => Faq::published()->ordered()->with('category')->take(4)->get(),
        ]));
    }

    protected function renderHelpCenter(array $data)
    {
        $query = trim((string) request('q'));

        $categories = FaqCategory::active()->ordered()
            ->with(['faqs' => fn ($q) => $q->published()->ordered()])
            ->get();

        $searchResults = collect();
        if ($query !== '') {
            $searchResults = Faq::published()
                ->where(function ($q) use ($query) {
                    $q->where('question', 'like', "%{$query}%")
                        ->orWhere('answer', 'like', "%{$query}%");
                })
                ->ordered()
                ->with('category')
                ->get();
        }

        return view('frontend.help-center', array_merge($data, [
            'categories' => $categories->filter(fn ($c) => $c->faqs->isNotEmpty())->values(),
            'allFaqs' => Faq::published()->ordered()->with('category')->get(),
            'searchQuery' => $query,
            'searchResults' => $searchResults,
        ]));
    }

    protected function renderShippingInfo(array $data)
    {
        return view('frontend.shipping-info', array_merge($data, [
            'methods' => ShippingMethod::active()->get(),
            'processSteps' => $this->processSteps('shipping.process_steps', [
                ['title' => 'Order confirmed', 'description' => 'We confirm your order and email the receipt straight away.'],
                ['title' => 'Packed with care', 'description' => 'Your items are checked, packed securely, and prepared for dispatch.'],
                ['title' => 'Handed to courier', 'description' => 'You receive tracking details as soon as your parcel leaves us.'],
                ['title' => 'Delivered to you', 'description' => 'Follow the delivery updates until your order arrives at your door.'],
            ]),
            'areas' => collect(preg_split('/\r\n|\r|\n/', (string) \App\Models\Setting::get('shipping.areas', '')))->filter()->values(),
            'importantNotes' => collect(preg_split('/\r\n|\r|\n/', (string) \App\Models\Setting::get('shipping.important_info', '')))->filter()->values(),
            'freeThreshold' => \App\Models\Setting::get('shipping.free_threshold'),
            'currency' => config('currency.default', 'USD'),
        ]));
    }

    protected function renderReturns(array $data)
    {
        return view('frontend.returns', array_merge($data, [
            'windowDays' => \App\Models\Setting::get('returns.window_days'),
            'processSteps' => $this->processSteps('returns.process_steps', [
                ['title' => 'Start a request', 'description' => 'Choose the delivered item from your orders and tell us why you are returning it.'],
                ['title' => 'Receive approval', 'description' => 'We review your request and send the return instructions by email.'],
                ['title' => 'Pack and send', 'description' => 'Pack the item securely with any included accessories and drop it off with the courier.'],
                ['title' => 'Inspection and refund', 'description' => 'Once received, we inspect the item and issue your refund to the original payment method.'],
            ]),
            'startReturnUrl' => auth()->check()
                ? route('customer.returns.index')
                : route('login'),
        ]));
    }

    protected function renderAbout(array $data)
    {
        $stats = collect([
            ['label' => 'Products', 'value' => Product::query()->where('status', true)->count()],
            ['label' => 'Customers', 'value' => User::where('role', 'customer')->count()],
            ['label' => 'Orders Placed', 'value' => \App\Models\Order::count()],
        ])->filter(fn ($stat) => $stat['value'] > 0)->values();

        return view('frontend.about', array_merge($data, [
            'stats' => $stats,
            'foundedYear' => \App\Models\Setting::get('about.founded_year'),
        ]));
    }

    protected function renderCareers(array $data)
    {
        return view('frontend.careers', array_merge($data, [
            'jobs' => Job::published()->get(),
        ]));
    }

    protected function renderBlog(array $data)
    {
        $posts = Post::latestPublished()->published();

        if ($category = request('category')) {
            $posts->whereHas('category', fn ($q) => $q->where('slug', $category));
        }

        if ($search = trim((string) request('q'))) {
            $posts->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        $featured = Post::latestPublished()->published()->where('is_featured', true)->first();

        return view('frontend.blog', array_merge($data, [
            'featuredPost' => $featured,
            'posts' => $posts->when($featured, fn ($q) => $q->where('id', '!=', $featured->id))
                ->paginate(9)
                ->withQueryString(),
            'categories' => PostCategory::orderBy('name')->get(),
            'activeCategory' => request('category'),
            'searchQuery' => request('q'),
        ]));
    }

    protected function renderPress(array $data)
    {
        $footerConfig = \App\Models\HomepageSection::getCached()->get('premium-footer')?->config ?? [];

        return view('frontend.press', array_merge($data, [
            'releases' => PressRelease::published()->paginate(8)->withQueryString(),
            'pressContact' => [
                'name' => \App\Models\Setting::get('press.contact_name'),
                'email' => \App\Models\Setting::get('press.contact_email') ?? ($footerConfig['email'] ?? null),
                'phone' => \App\Models\Setting::get('press.contact_phone') ?? ($footerConfig['phone'] ?? null),
            ],
        ]));
    }

    /**
     * Settings are stored as JSON text, while templates need an array of
     * process-step objects. Fall back to useful defaults until an admin saves
     * their own steps from Content Settings.
     */
    protected function processSteps(string $key, array $fallback)
    {
        $steps = \App\Models\Setting::get($key, []);

        if (is_string($steps)) {
            $steps = json_decode($steps, true);
        }

        $steps = is_array($steps) ? collect($steps)
            ->filter(fn ($step) => is_array($step) && filled($step['title'] ?? null))
            ->values() : collect();

        return $steps->isNotEmpty() ? $steps : collect($fallback);
    }

    protected function seoData(Page $page): array
    {
        return [
            'seoTitle' => $page->seo_title ?: $page->title,
            'seoDescription' => $page->seo_description ?: $page->short_description,
        ];
    }
}
