@extends('admin.layouts.app')

@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Homepage Sections', 'url' => route('admin.homepage-sections.index')],
        ['label' => ucwords(str_replace('-', ' ', $section->slug)), 'url' => null],
    ];
@endphp

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">{{ ucwords(str_replace('-', ' ', $section->slug)) }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                @if($section->subtitle)
                    {{ $section->subtitle }}
                @else
                    Edit section content and configuration
                @endif
            </p>
        </div>
        <div class="flex items-center gap-3">
            <form method="POST" action="{{ route('admin.homepage-sections.toggle', $section) }}">
                @csrf
                @method('PATCH')
                <button type="submit" class="relative inline-flex h-6 w-11 shrink-0 items-center overflow-hidden rounded-full transition-colors {{ $section->is_enabled ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600' }}">
                    <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform {{ $section->is_enabled ? 'translate-x-6' : 'translate-x-1' }}"></span>
                </button>
            </form>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.homepage-sections.update', $section) }}" x-data="sectionForm()" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Basic Fields --}}
        <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Basic Settings</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                    <input type="text" name="title" value="{{ old('title', $section->title) }}"
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Subtitle</label>
                    <input type="text" name="subtitle" value="{{ old('subtitle', $section->subtitle) }}"
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                </div>
            </div>
            @if(in_array($section->slug, ['featured-products','new-arrivals','trending-products','flash-sale','best-sellers','recommended-products','popular-products']))
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Max Products</label>
                        <input type="number" name="max_products" value="{{ old('max_products', $section->max_products) }}" min="1" max="50"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Layout</label>
                        <select name="layout" class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            <option value="grid" {{ $section->layout === 'grid' ? 'selected' : '' }}>Grid</option>
                            <option value="carousel" {{ $section->layout === 'carousel' ? 'selected' : '' }}>Carousel</option>
                            <option value="list" {{ $section->layout === 'list' ? 'selected' : '' }}>List</option>
                        </select>
                    </div>
                </div>
            @endif
        </div>

        {{-- Section-Specific Config --}}
        @if($section->slug === 'hero-carousel')
            @include('admin.homepage-sections._hero-carousel')
        @elseif($section->slug === 'trust-bar')
            @include('admin.homepage-sections._trust-bar')
        @elseif($section->slug === 'why-choose-us')
            @include('admin.homepage-sections._why-choose-us')
        @elseif($section->slug === 'testimonials')
            @include('admin.homepage-sections._testimonials')
        @elseif($section->slug === 'newsletter-cta')
            @include('admin.homepage-sections._newsletter-cta')
        @elseif($section->slug === 'instagram-gallery')
            @include('admin.homepage-sections._instagram-gallery')
        @elseif($section->slug === 'flash-sale')
            @include('admin.homepage-sections._flash-sale')
        @elseif($section->slug === 'sale-banner')
            @include('admin.homepage-sections._sale-banner')
        @elseif($section->slug === 'premium-footer')
            @include('admin.homepage-sections._premium-footer')
        @elseif(in_array($section->slug, ['featured-products','new-arrivals','trending-products','best-sellers','recommended-products','popular-products']))
            <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6 mb-6">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Manage products for this section via
                    <a href="{{ route('admin.product-sections.index', $section->slug) }}" class="text-brand-500 hover:text-brand-600 dark:text-brand-400 dark:hover:text-brand-300 font-medium">Product Sections</a>.
                </p>
            </div>
        @endif

        {{-- Save Button --}}
        <div class="flex items-center gap-3">
            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                Save Changes
            </button>
            <a href="{{ route('admin.homepage-sections.index') }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.03]">
                Back to Sections
            </a>
        </div>
    </form>
@endsection

@push('scripts')
<script type="text/turbo-script">
function sectionForm() {
    const normalizeSlides = (slides) => (slides || []).map(s => ({
        ...s,
        image_path: s.image_path || null,
        brightness: s.brightness ?? 100,
        overlay_enabled: s.overlay_enabled !== false,
        overlay_opacity: s.overlay_opacity ?? 40,
        overlay_color: s.overlay_color || '#000000',
        remove_image: false,
        preview: null,
    }));

    return {
        slides: normalizeSlides(@json($section->config['slides'] ?? [])),
        trustItems: @json($section->config['items'] ?? []),
        features: @json($section->config['features'] ?? []),
        testimonials: @json($section->config['testimonials'] ?? []),
        galleryImages: @json($section->config['images'] ?? []),
        footerColumns: @json($section->config['footer_columns'] ?? []),
        socialLinks: @json($section->config['social_links'] ?? []),

        slidePreview(slide) {
            if (slide.preview) return slide.preview;
            if (slide.image_path) return slide.image_path.startsWith('http') ? slide.image_path : '{{ asset('storage') }}' + '/' + slide.image_path;
            return slide.image || '';
        },

        addSlide() {
            this.slides.push({ badge: 'NEW', badge_color: 'bg-green-500', heading: '', description: '', image: '', image_path: null, brightness: 100, overlay_enabled: true, overlay_opacity: 40, overlay_color: '#000000', cta_primary: 'Shop Now', cta_secondary: 'Learn More', link_primary: '/shop', link_secondary: '/about', remove_image: false, preview: null });
        },
        removeSlide(i) { this.slides.splice(i, 1); },

        addTrustItem() {
            this.trustItems.push({ icon: 'truck', title: '', description: '' });
        },
        removeTrustItem(i) { this.trustItems.splice(i, 1); },

        addFeature() {
            this.features.push({ icon: 'quality', title: '', description: '' });
        },
        removeFeature(i) { this.features.splice(i, 1); },

        addTestimonial() {
            this.testimonials.push({ name: '', avatar: '', rating: 5, review: '', role: '' });
        },
        removeTestimonial(i) { this.testimonials.splice(i, 1); },

        addGalleryImage() {
            this.galleryImages.push({ url: '', span: 'col-span-1 row-span-1', alt: '' });
        },
        removeGalleryImage(i) { this.galleryImages.splice(i, 1); },

        addFooterColumn() {
            this.footerColumns.push({ heading: '', links: [] });
        },
        removeFooterColumn(i) { this.footerColumns.splice(i, 1); },
        addFooterLink(colIndex) {
            this.footerColumns[colIndex].links.push('');
        },
        removeFooterLink(colIndex, linkIndex) {
            this.footerColumns[colIndex].links.splice(linkIndex, 1);
        },

        addSocialLink() {
            this.socialLinks.push({ platform: '', url: '#' });
        },
        removeSocialLink(i) { this.socialLinks.splice(i, 1); },
    };
}
</script>
@endpush
