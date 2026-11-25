<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSection;
use App\Models\Setting;
use Illuminate\Http\Request;

class StoreSettingsController extends Controller
{
    protected array $socialPlatforms = [
        'Facebook' => 'Facebook',
        'Instagram' => 'Instagram',
        'Twitter'   => 'X / Twitter',
        'LinkedIn'  => 'LinkedIn',
        'YouTube'   => 'YouTube',
        'TikTok'    => 'TikTok',
    ];

    public function edit()
    {
        $values = [
            'name' => (string) Setting::get('store.name', site_name()),
            'email' => (string) Setting::get('store.email', ''),
            'phone' => (string) Setting::get('store.phone', ''),
            'address' => (string) Setting::get('store.address', ''),
            'business_hours' => (string) Setting::get('store.business_hours', ''),
            'description' => (string) Setting::get('store.description', ''),
        ];

        $socials = Setting::get('store.social_links', []);
        if (! is_array($socials)) {
            $socials = json_decode((string) $socials, true) ?: [];
        }

        return view('admin.store-settings.edit', [
            'values' => $values,
            'socials' => $socials,
            'platforms' => $this->socialPlatforms,
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'business_hours' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:1000',
            'socials' => 'nullable|array',
            'socials.*.platform' => 'nullable|string|max:50',
            'socials.*.url' => 'nullable|url|max:500',
        ]);

        Setting::set('store.name', (string) ($validated['name'] ?? ''));
        Setting::set('store.email', (string) ($validated['email'] ?? ''));
        Setting::set('store.phone', (string) ($validated['phone'] ?? ''));
        Setting::set('store.address', (string) ($validated['address'] ?? ''));
        Setting::set('store.business_hours', (string) ($validated['business_hours'] ?? ''));
        Setting::set('store.description', (string) ($validated['description'] ?? ''));

        $socials = collect($request->input('socials', []))
            ->map(fn ($s) => [
                'platform' => (string) ($s['platform'] ?? ''),
                'url' => (string) ($s['url'] ?? ''),
            ])
            ->filter(fn ($s) => $s['platform'] !== '' && $s['url'] !== '')
            ->values()
            ->all();

        Setting::set('store.social_links', json_encode($socials));

        // Keep the legacy premium-footer section in sync so the existing
        // footer controller UI and any fallbacks stay consistent.
        $section = HomepageSection::firstOrCreate(
            ['slug' => 'premium-footer'],
            ['title' => 'Premium Footer', 'is_enabled' => true, 'sort_order' => 16, 'config' => []]
        );
        $config = $section->config ?? [];
        $config['phone'] = $section->getConfig('phone') ?: $validated['phone'] ?? '';
        $config['email'] = $section->getConfig('email') ?: $validated['email'] ?? '';
        $config['address'] = $section->getConfig('address') ?: $validated['address'] ?? '';
        $config['company_name'] = $section->getConfig('company_name') ?: $validated['name'] ?? '';
        $config['social_links'] = $socials;
        $section->update(['config' => $config]);
        HomepageSection::clearCache();

        return redirect()->route('admin.store-settings.edit')
            ->with('success', 'Store information saved.');
    }
}
