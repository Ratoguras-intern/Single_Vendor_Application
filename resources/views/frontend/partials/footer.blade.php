@php
    $footerSect = app(\App\Models\HomepageSection::class)->getCached()->get('premium-footer');
    $companyName = $footerSect?->config['company_name'] ?? 'NBK Vertex';
    $companyDesc = $footerSect?->config['company_description'] ?? 'A powerful e-commerce management platform. Streamline operations, boost sales, and scale your business with confidence.';
    $address = $footerSect?->config['address'] ?? '123 Fashion Street, Style City, SC 12345';
    $phone = $footerSect?->config['phone'] ?? '+1 (555) 123-4567';
    $email = $footerSect?->config['email'] ?? 'hello@nbkvertex.com';
    $copyrightText = $footerSect?->config['copyright_text'] ?? 'All Rights Reserved.';
    $socialLinks = $footerSect?->config['social_links'] ?? [];
    $footerColumns = $footerSect?->config['footer_columns'] ?? [];

    $defaultSocials = [
        ['platform' => 'Facebook', 'url' => '#'],
        ['platform' => 'Twitter', 'url' => '#'],
        ['platform' => 'Instagram', 'url' => '#'],
        ['platform' => 'GitHub', 'url' => '#'],
    ];
    $socialLinks = !empty($socialLinks) ? $socialLinks : $defaultSocials;

    $defaultColumns = [
        ['heading' => 'Shop', 'links' => ['All Products', 'New Arrivals', 'Sale', 'Featured']],
        ['heading' => 'Customer Care', 'links' => ['Contact Us', 'Help Center', 'Shipping Info', 'Returns & Exchanges']],
        ['heading' => 'Company', 'links' => ['About Us', 'Careers', 'Blog', 'Press']],
        ['heading' => 'Legal', 'links' => ['Privacy Policy', 'Terms & Conditions', 'Cookie Policy', 'Accessibility']],
    ];
    $footerColumns = !empty($footerColumns) ? $footerColumns : $defaultColumns;
@endphp

<footer class="border-t border-secondary-200 bg-white dark:border-secondary-700 dark:bg-secondary-900">
    <div class="section">
        <div class="py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-8">
                <div class="lg:col-span-2">
                    <div class="mb-5">
                        <x-brand-logo subtitle="Commerce Suite" />
                    </div>

                    <p class="mb-6 max-w-sm text-sm text-secondary-500 dark:text-secondary-400 leading-relaxed">{{ $companyDesc }}</p>

                    <div class="space-y-3">
                        <div class="flex items-center gap-3 text-sm text-secondary-500 dark:text-secondary-400">
                            <svg class="h-4 w-4 shrink-0 text-primary-500 dark:text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                            <span>{{ $address }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-secondary-500 dark:text-secondary-400">
                            <svg class="h-4 w-4 shrink-0 text-primary-500 dark:text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/></svg>
                            <span>{{ $phone }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-secondary-500 dark:text-secondary-400">
                            <svg class="h-4 w-4 shrink-0 text-primary-500 dark:text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                            <span>{{ $email }}</span>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6">
                        @foreach($socialLinks as $social)
                            <a href="{{ $social['url'] }}" aria-label="{{ $social['platform'] }}" class="h-10 w-10 rounded-card bg-secondary-100 dark:bg-white/5 inline-flex items-center justify-center text-secondary-500 dark:text-secondary-400 hover:bg-primary-50 dark:hover:bg-primary-950/30 hover:text-primary-600 dark:hover:text-primary-300 transition-all duration-200">
                                @if($social['platform'] === 'Facebook')
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                                @elseif($social['platform'] === 'Twitter')
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/></svg>
                                @elseif($social['platform'] === 'Instagram')
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>
                                @elseif($social['platform'] === 'GitHub')
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 22v-4a4.8 4.8 0 0 0-1-3.5c3 0 6-2 6-5.5.08-1.25-.27-2.48-1-3.5.28-1.15.28-2.35 0-3.5 0 0-1 0-3 1.5-2.64-.5-5.36-.5-8 0C6 2 5 2 5 2c-.3 1.15-.3 2.35 0 3.5A5.403 5.403 0 0 0 4 9c0 3.5 3 5.5 6 5.5-.39.49-.68 1.05-.85 1.65-.17.6-.22 1.23-.15 1.85v4"/><path d="M9 18c-4.51 2-5-2-7-2"/></svg>
                                @elseif($social['platform'] === 'LinkedIn')
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect width="4" height="12" x="2" y="9"/><circle cx="4" cy="4" r="2"/></svg>
                                @elseif($social['platform'] === 'YouTube')
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.55 49.55 0 0 1-16.2 0A2 2 0 0 1 2.5 17"/><path d="m10 15 5-3-5-3z"/></svg>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>

                @foreach($footerColumns as $col)
                    <div>
                        <h4 class="text-sm font-semibold mb-4 uppercase tracking-wider text-secondary-900 dark:text-white">{{ $col['heading'] }}</h4>
                        <ul class="space-y-2.5">
                            @foreach($col['links'] as $link)
                                <li>
                                    <a href="#" class="text-sm text-secondary-500 dark:text-secondary-400 hover:text-secondary-900 dark:hover:text-white transition-colors inline-block">{{ $link }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="divider"></div>

        <div class="py-6 flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <div class="flex items-center gap-2 text-sm text-secondary-500 dark:text-secondary-400">
                    <span>&copy; {{ date('Y') }} {{ $companyName }}&trade;. {{ $copyrightText }}</span>
                </div>
                <p class="text-sm text-secondary-500 dark:text-secondary-400">Powered by <span class="font-bold text-primary-600 dark:text-primary-400">{{ $companyName }}</span></p>
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center gap-3 text-secondary-400 dark:bg-white/10 dark:rounded-lg dark:px-3 dark:py-1.5">
                    <svg class="h-8 w-auto" viewBox="0 0 36 24" fill="currentColor"><rect width="36" height="24" rx="4" fill="#1A1F71"/><text x="50%" y="55%" text-anchor="middle" dominant-baseline="middle" font-size="8" font-weight="bold" fill="white">VISA</text></svg>
                    <svg class="h-8 w-auto" viewBox="0 0 36 24" fill="currentColor"><rect width="36" height="24" rx="4" fill="#252525"/><circle cx="14" cy="12" r="7" fill="#EB001B"/><circle cx="22" cy="12" r="7" fill="#F79E1B"/></svg>
                    <svg class="h-8 w-auto" viewBox="0 0 36 24" fill="currentColor"><rect width="36" height="24" rx="4" fill="#000"/><text x="50%" y="55%" text-anchor="middle" dominant-baseline="middle" font-size="7" font-weight="bold" fill="white">PayPal</text></svg>
                    <svg class="h-8 w-auto" viewBox="0 0 36 24" fill="currentColor"><rect width="36" height="24" rx="4" fill="#635BFF"/><text x="50%" y="55%" text-anchor="middle" dominant-baseline="middle" font-size="7" font-weight="bold" fill="white">Stripe</text></svg>
                </div>
            </div>
        </div>
    </div>
</footer>
