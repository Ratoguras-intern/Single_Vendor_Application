<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBannerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $positions = implode(',', config('banners.positions'));

        return array_merge([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,avif,gif|max:10240',
            'mobile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,avif,gif|max:10240',
            'remove_image' => 'nullable|boolean',
            'remove_mobile_image' => 'nullable|boolean',
            'remove_product_image' => 'nullable|boolean',
            'link' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:255',
            'secondary_button_text' => 'nullable|string|max:255',
            'secondary_button_url' => 'nullable|string|max:255',
            'badge' => 'nullable|string|max:255',
            'badge_color' => 'nullable|string|max:255',
            'featured_product_id' => 'nullable|integer|exists:products,id',
            'product_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,avif,gif|max:10240',
            'image_media_id' => 'nullable|integer|exists:media,id',
            'mobile_image_media_id' => 'nullable|integer|exists:media,id',
            'product_image_media_id' => 'nullable|integer|exists:media,id',
            'background_color' => 'nullable|string|max:20',
            'gradient_from' => 'nullable|string|max:20',
            'gradient_to' => 'nullable|string|max:20',
            'text_alignment' => 'nullable|in:left,center,right',
            'image_position' => 'nullable|in:center,top,bottom,left,right,left top,right top,left bottom,right bottom,center center,top center,bottom center,center left,center right,top left,top right,bottom left,bottom right',
            'overlay_opacity' => 'nullable|integer|min:0|max:100',
            'text_color' => 'nullable|string|max:50',
            'show_countdown' => 'nullable|boolean',
            'countdown_end_date' => 'nullable|date',
            'countdown_end_time' => 'nullable|date_format:H:i',
            'countdown_timezone' => 'nullable|string|max:64|timezone',
            'enable_badge' => 'nullable|boolean',
            'enable_product_image' => 'nullable|boolean',
            'enable_prices' => 'nullable|boolean',
            'enable_buttons' => 'nullable|boolean',
            'enable_overlay' => 'nullable|boolean',
            'position' => "required|in:{$positions}",
            'target_pages' => 'nullable|array',
            'target_pages.*' => 'string|in:category,shop',
            'is_enabled' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ], $this->displayRules());
    }

    protected function displayRules(): array
    {
        return [
            'image_fit' => 'nullable|in:cover,contain,fill,none,scale-down',
            'image_repeat' => 'nullable|in:no-repeat,repeat,repeat-x,repeat-y',
            'banner_height' => 'nullable|in:small,medium,large,xlarge,full_screen,custom',
            'banner_height_custom' => 'nullable|integer|min:50|max:2000',
            'overlay_enabled' => 'nullable|boolean',
            'overlay_color' => 'nullable|string|regex:/^#[0-9a-fA-F]{6}$/',
            'content_vertical' => 'nullable|in:top,center,bottom',
            'border_radius' => 'nullable|in:none,small,medium,large,xlarge,custom',
            'border_radius_custom' => 'nullable|integer|min:0|max:200',
            'padding_top' => 'nullable|integer|min:0|max:200',
            'padding_bottom' => 'nullable|integer|min:0|max:200',
            'padding_left' => 'nullable|integer|min:0|max:200',
            'padding_right' => 'nullable|integer|min:0|max:200',
            'margin_top' => 'nullable|integer|min:0|max:200',
            'margin_bottom' => 'nullable|integer|min:0|max:200',
            'zoom' => 'nullable|integer|min:50|max:200',
            'brightness' => 'nullable|integer|min:0|max:200',
            'contrast' => 'nullable|integer|min:0|max:200',
            'saturation' => 'nullable|integer|min:0|max:200',
            'blur' => 'nullable|integer|min:0|max:20',
            'grayscale' => 'nullable|boolean',
            'text_width' => 'nullable|in:narrow,medium,wide,full',
            'show_desktop' => 'nullable|boolean',
            'show_tablet' => 'nullable|boolean',
            'show_mobile' => 'nullable|boolean',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            if (! $this->boolean('is_enabled')) {
                return;
            }

            $title = trim((string) $this->input('title'));

            if (mb_strlen($title) < 3) {
                $validator->errors()->add('title', 'Title must be at least 3 characters for a published banner.');
            }

            $hasExistingVisual = $this->route('banner')
                && ($this->route('banner')->image || $this->route('banner')->mobile_image);

            $hasVisual = $hasExistingVisual
                || $this->file('image')
                || $this->file('mobile_image')
                || $this->file('product_image')
                || $this->filled('featured_product_id')
                || $this->filled('gradient_from')
                || $this->filled('background_color');

            if (! $hasVisual && ! $this->boolean('remove_image') && ! $this->boolean('remove_mobile_image')) {
                $validator->errors()->add('image', 'A banner image, product image, background color, or gradient is required for a published banner.');
            }
        });
    }
}
