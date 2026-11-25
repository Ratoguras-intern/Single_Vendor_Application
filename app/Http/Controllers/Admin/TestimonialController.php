<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSection;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    protected function section(): HomepageSection
    {
        return HomepageSection::firstOrCreate(
            ['slug' => 'testimonials'],
            [
                'title' => 'What Our Customers Say',
                'subtitle' => 'Real reviews from real people',
                'is_enabled' => true,
                'sort_order' => 12,
                'config' => ['testimonials' => []],
            ]
        );
    }

    protected function items(HomepageSection $section): array
    {
        $items = $section->config['testimonials'] ?? [];
        if (! is_array($items)) {
            $items = [];
        }

        return collect($items)
            ->map(fn ($t, $index) => array_merge([
                'id' => 't'.($index + 1),
                'name' => '',
                'role' => '',
                'review' => '',
                'rating' => 5,
                'avatar' => '',
                'active' => true,
                'sort_order' => $index,
            ], (array) $t))
            ->sortBy('sort_order')
            ->values()
            ->all();
    }

    public function index()
    {
        $section = $this->section();

        return view('admin.testimonials.index', [
            'items' => $this->items($section),
            'section' => $section,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'review' => 'required|string|max:2000',
            'rating' => 'required|integer|min:1|max:5',
            'avatar' => 'nullable|string|max:1000',
            'active' => 'nullable',
        ]);

        $section = $this->section();
        $items = $this->items($section);

        $items[] = [
            'id' => 't'.(count($items) + 1),
            'name' => $validated['name'],
            'role' => $validated['role'] ?? '',
            'review' => $validated['review'],
            'rating' => (int) $validated['rating'],
            'avatar' => $validated['avatar'] ?? '',
            'active' => $request->boolean('active', true),
            'sort_order' => count($items),
        ];

        $section->setConfig('testimonials', $items);
        $section->save();
        HomepageSection::clearCache();

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial added.');
    }

    public function update(Request $request, string $testimonial)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'review' => 'required|string|max:2000',
            'rating' => 'required|integer|min:1|max:5',
            'avatar' => 'nullable|string|max:1000',
            'active' => 'nullable',
        ]);

        $section = $this->section();
        $items = collect($this->items($section))->map(function ($t) use ($testimonial, $validated, $request) {
            if ($t['id'] === $testimonial) {
                $t['name'] = $validated['name'];
                $t['role'] = $validated['role'] ?? '';
                $t['review'] = $validated['review'];
                $t['rating'] = (int) $validated['rating'];
                $t['avatar'] = $validated['avatar'] ?? $t['avatar'];
                $t['active'] = $request->boolean('active', $t['active']);
            }

            return $t;
        })->values()->all();

        $section->setConfig('testimonials', $items);
        $section->save();
        HomepageSection::clearCache();

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial updated.');
    }

    public function toggle(string $testimonial)
    {
        $section = $this->section();
        $target = null;
        $items = collect($this->items($section))->map(function ($t) use ($testimonial, &$target) {
            if ($t['id'] === $testimonial) {
                $t['active'] = ! $t['active'];
                $target = $t;
            }

            return $t;
        })->values()->all();

        if ($target) {
            $section->setConfig('testimonials', $items);
            $section->save();
            HomepageSection::clearCache();
        }

        return response()->json([
            'active' => $target['active'] ?? true,
            'message' => ($target['active'] ?? false) ? 'Testimonial approved and visible.' : 'Testimonial hidden.',
        ]);
    }

    public function updateOrder(Request $request)
    {
        $validated = $request->validate([
            'order' => 'required|array',
            'order.*' => 'string',
        ]);

        $section = $this->section();
        $items = $this->items($section);
        $byId = collect($items)->keyBy('id');
        $reordered = collect($validated['order'])
            ->map(fn ($id) => $byId->get($id, null))
            ->filter()
            ->values()
            ->map(fn ($t, $index) => array_merge($t, ['sort_order' => $index]))
            ->all();

        $section->setConfig('testimonials', $reordered);
        $section->save();
        HomepageSection::clearCache();

        return response()->json(['message' => 'Order updated.']);
    }

    public function destroy(string $testimonial)
    {
        $section = $this->section();
        $items = collect($this->items($section))
            ->reject(fn ($t) => $t['id'] === $testimonial)
            ->values()
            ->map(fn ($t, $index) => array_merge($t, ['sort_order' => $index]))
            ->all();

        $section->setConfig('testimonials', $items);
        $section->save();
        HomepageSection::clearCache();

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Testimonial deleted.');
    }
}
