<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSection;
use Illuminate\Http\Request;

class WhyChooseUsController extends Controller
{
    protected array $icons = ['quality', 'delivery', 'checkout', 'returns', 'support'];

    protected function section(): HomepageSection
    {
        return HomepageSection::firstOrCreate(
            ['slug' => 'why-choose-us'],
            [
                'title' => 'Why Choose Us',
                'subtitle' => "We're committed to delivering the best experience",
                'is_enabled' => true,
                'sort_order' => 10,
                'config' => ['features' => []],
            ]
        );
    }

    protected function features(HomepageSection $section): array
    {
        $features = $section->config['features'] ?? [];
        if (! is_array($features)) {
            $features = [];
        }

        return collect($features)
            ->map(fn ($f, $index) => array_merge([
                'id' => 'f'.($index + 1),
                'icon' => 'quality',
                'title' => '',
                'description' => '',
                'active' => true,
                'sort_order' => $index,
            ], (array) $f))
            ->sortBy('sort_order')
            ->values()
            ->all();
    }

    public function index()
    {
        $section = $this->section();

        return view('admin.why-choose-us.index', [
            'features' => $this->features($section),
            'section' => $section,
            'icons' => $this->icons,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'icon' => 'nullable|string|max:50',
            'active' => 'nullable',
        ]);

        $section = $this->section();
        $features = $this->features($section);
        $nextId = 'f'.(collect($features)->max('sort_order') + 1);

        $features[] = [
            'id' => $nextId,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'icon' => $validated['icon'] ?? 'quality',
            'active' => $request->boolean('active', true),
            'sort_order' => count($features),
        ];

        $section->setConfig('features', $features);
        $section->save();
        HomepageSection::clearCache();

        return redirect()->route('admin.why-choose-us.index')
            ->with('success', 'Feature added.');
    }

    public function update(Request $request, string $feature)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'icon' => 'nullable|string|max:50',
            'active' => 'nullable',
        ]);

        $section = $this->section();
        $features = collect($this->features($section))->map(function ($f) use ($feature, $validated, $request) {
            if ($f['id'] === $feature) {
                $f['title'] = $validated['title'];
                $f['description'] = $validated['description'];
                $f['icon'] = $validated['icon'] ?? $f['icon'];
                $f['active'] = $request->boolean('active', $f['active']);
            }

            return $f;
        })->values()->all();

        $section->setConfig('features', $features);
        $section->save();
        HomepageSection::clearCache();

        return redirect()->route('admin.why-choose-us.index')
            ->with('success', 'Feature updated.');
    }

    public function toggle(string $feature)
    {
        $section = $this->section();
        $target = null;
        $features = collect($this->features($section))->map(function ($f) use ($feature, &$target) {
            if ($f['id'] === $feature) {
                $f['active'] = ! $f['active'];
                $target = $f;
            }

            return $f;
        })->values()->all();

        if ($target) {
            $section->setConfig('features', $features);
            $section->save();
            HomepageSection::clearCache();
        }

        return response()->json([
            'active' => $target['active'] ?? true,
            'message' => ($target['active'] ?? false) ? 'Feature enabled.' : 'Feature disabled.',
        ]);
    }

    public function updateOrder(Request $request)
    {
        $validated = $request->validate([
            'order' => 'required|array',
            'order.*' => 'string',
        ]);

        $section = $this->section();
        $features = $this->features($section);
        $byId = collect($features)->keyBy('id');
        $reordered = collect($validated['order'])
            ->map(fn ($id) => $byId->get($id, null))
            ->filter()
            ->values()
            ->map(fn ($f, $index) => array_merge($f, ['sort_order' => $index]))
            ->all();

        $section->setConfig('features', $reordered);
        $section->save();
        HomepageSection::clearCache();

        return response()->json(['message' => 'Order updated.']);
    }

    public function destroy(string $feature)
    {
        $section = $this->section();
        $features = collect($this->features($section))
            ->reject(fn ($f) => $f['id'] === $feature)
            ->values()
            ->map(fn ($f, $index) => array_merge($f, ['sort_order' => $index]))
            ->all();

        $section->setConfig('features', $features);
        $section->save();
        HomepageSection::clearCache();

        return redirect()->route('admin.why-choose-us.index')
            ->with('success', 'Feature deleted.');
    }
}
