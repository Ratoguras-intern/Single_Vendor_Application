<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSection;
use Illuminate\Http\Request;

class FooterController extends Controller
{
    public function edit()
    {
        $section = HomepageSection::firstOrCreate(
            ['slug' => 'premium-footer'],
            [
                'title' => 'Premium Footer',
                'is_enabled' => true,
                'sort_order' => 16,
                'config' => [
                    'company_name' => 'NBK Vertex',
                    'company_description' => '',
                    'address' => '',
                    'phone' => '',
                    'email' => '',
                    'copyright_text' => 'All Rights Reserved.',
                    'social_links' => [],
                    'footer_columns' => [],
                ],
            ]
        );

        return view('admin.footer.edit', ['section' => $section]);
    }

    public function update(Request $request, HomepageSection $homepageSection)
    {
        $validated = $request->validate([
            'config.company_name' => 'nullable|string|max:255',
            'config.company_description' => 'nullable|string|max:1000',
            'config.address' => 'nullable|string|max:500',
            'config.phone' => 'nullable|string|max:100',
            'config.email' => 'nullable|string|max:255',
            'config.copyright_text' => 'nullable|string|max:500',
        ]);

        $config = $homepageSection->config ?? [];

        $fields = ['company_name', 'company_description', 'address', 'phone', 'email', 'copyright_text', 'social_links', 'footer_columns'];
        foreach ($fields as $field) {
            if ($request->filled("config.{$field}")) {
                $config[$field] = $request->input("config.{$field}");
            }
        }

        $homepageSection->update(['config' => $config]);
        HomepageSection::clearCache();

        return redirect()->route('admin.footer.edit')
            ->with('success', 'Footer updated successfully.');
    }
}
