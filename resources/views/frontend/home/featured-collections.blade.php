@if(!empty($featuredCollections) && count($featuredCollections) > 0)
    @php
        $collectionCount = count($featuredCollections);
    @endphp
    <section class="home-section">
        <div class="section">
            <div class="flex items-end justify-between mb-8">
                <div>
                    <p class="section-eyebrow">Curated</p>
                    <h2 class="section-heading">Featured Collections</h2>
                    <p class="section-subheading">Handpicked edits, refreshed weekly</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 {{ $collectionCount > 2 ? 'lg:grid-cols-3' : 'lg:grid-cols-2' }} gap-4 sm:gap-6">
                @foreach($featuredCollections as $collection)
                    @php
                        $collectionClasses = $collection['display_style'] === 'list'
                            ? 'col-span-1 sm:col-span-2 aspect-[16/9]' . ($collectionCount > 2 ? ' lg:col-span-3' : '')
                            : 'aspect-[4/5]';
                    @endphp
                    <a href="{{ $collection['url'] }}"
                        class="group relative overflow-hidden rounded-card {{ $collectionClasses }}">
                        <img src="{{ $collection['image'] }}" alt="{{ $collection['name'] }}"
                            loading="lazy" decoding="async" onerror="this.src='{{ asset('frontend-assets/images/no-image.jpg') }}'"
                            class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" />

                        <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/25 to-transparent"></div>

                        <div class="absolute inset-0 flex flex-col justify-end p-5 sm:p-7">
                            <p class="text-[11px] font-semibold text-white/80 uppercase tracking-[0.18em]">
                                {{ $collection['products_count'] }} {{ $collection['products_count'] === 1 ? 'Product' : 'Products' }}
                            </p>
                            <h3 class="mt-1 font-display text-2xl sm:text-3xl font-semibold text-white">
                                {{ $collection['name'] }}
                            </h3>
                            <span class="mt-3 inline-flex items-center gap-1.5 text-sm font-semibold text-white/90 group-hover:text-white">
                                Shop Collection
                                <svg class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif
