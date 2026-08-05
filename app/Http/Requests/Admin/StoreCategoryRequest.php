<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\In;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $image = (array) config('categories.image.main');

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('categories', 'slug')],
            'description' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'integer', 'exists:categories,id'],
            'image' => [
                'nullable',
                'image',
                'mimetypes:' . ($image['mimetypes'] ?? 'image/jpeg,image/png,image/webp,image/avif'),
                'mimes:' . ($image['mimes'] ?? 'jpg,jpeg,png,webp,avif'),
                'max:' . ($image['max_size_kb'] ?? 10240),
            ],
            'banner_image' => ['nullable', 'image', 'max:' . (config('categories.image.banner.max_size_kb') ?? 10240)],
            'banner_mobile_image' => ['nullable', 'image', 'max:' . (config('categories.image.banner_mobile.max_size_kb') ?? 10240)],
            'banner_image_fit' => ['nullable', Rule::in((array) config('categories.object_fits'))],
            'banner_image_position' => ['nullable', Rule::in((array) config('categories.object_positions'))],
            'icon' => ['nullable', 'string', 'max:100', new In(array_keys((array) config('categories.icons')))],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'featured' => ['sometimes', 'boolean'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'featured' => $this->boolean('featured'),
        ]);
    }
}
