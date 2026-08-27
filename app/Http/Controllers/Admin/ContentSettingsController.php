<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class ContentSettingsController extends Controller
{
    public function edit()
    {
        $values = [
            'business_hours' => (string) Setting::get('contact.business_hours', ''),
            'response_time' => (string) Setting::get('contact.response_time', ''),
            'window_days' => Setting::get('returns.window_days', ''),
            'returns_steps_text' => $this->stepsToText(Setting::get('returns.process_steps')),
            'shipping_steps_text' => $this->stepsToText(Setting::get('shipping.process_steps')),
            'areas' => (string) Setting::get('shipping.areas', ''),
            'important_info' => (string) Setting::get('shipping.important_info', ''),
            'free_threshold' => Setting::get('shipping.free_threshold', ''),
            'press_contact_name' => (string) Setting::get('press.contact_name', ''),
            'press_contact_email' => (string) Setting::get('press.contact_email', ''),
            'press_contact_phone' => (string) Setting::get('press.contact_phone', ''),
            'founded_year' => Setting::get('about.founded_year', ''),
            'support_card_title' => (string) Setting::get('contact.support_card_title', ''),
            'support_card_text' => (string) Setting::get('contact.support_card_text', ''),
            'help_aside_title' => (string) Setting::get('help.aside_title', ''),
            'help_aside_text' => (string) Setting::get('help.aside_text', ''),
            'caption_dispatch' => (string) Setting::get('shipping.caption_dispatch', ''),
            'caption_packing' => (string) Setting::get('shipping.caption_packing', ''),
            'value1_title' => (string) Setting::get('about.value1_title', ''),
            'value1_text' => (string) Setting::get('about.value1_text', ''),
            'value2_title' => (string) Setting::get('about.value2_title', ''),
            'value2_text' => (string) Setting::get('about.value2_text', ''),
            'value3_title' => (string) Setting::get('about.value3_title', ''),
            'value3_text' => (string) Setting::get('about.value3_text', ''),
            'badge_title' => (string) Setting::get('about.badge_title', ''),
            'badge_sub' => (string) Setting::get('about.badge_sub', ''),
            'chat_widget_enabled' => Setting::get('chat_widget.enabled', '1'),
        ];

        return view('admin.content-settings.edit', compact('values'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'business_hours' => 'nullable|string|max:500',
            'response_time' => 'nullable|string|max:255',
            'window_days' => 'nullable|integer|min:0|max:365',
            'returns_process_steps' => 'nullable|string|max:10000',
            'shipping_process_steps' => 'nullable|string|max:10000',
            'areas' => 'nullable|string|max:10000',
            'important_info' => 'nullable|string|max:10000',
            'free_threshold' => 'nullable|numeric|min:0',
            'press_contact_name' => 'nullable|string|max:255',
            'press_contact_email' => 'nullable|email|max:255',
            'press_contact_phone' => 'nullable|string|max:255',
            'founded_year' => 'nullable|integer|min:1900|max:' . ((int) date('Y') + 1),
            'support_card_title' => 'nullable|string|max:255',
            'support_card_text' => 'nullable|string|max:500',
            'help_aside_title' => 'nullable|string|max:255',
            'help_aside_text' => 'nullable|string|max:500',
            'caption_dispatch' => 'nullable|string|max:255',
            'caption_packing' => 'nullable|string|max:255',
            'value1_title' => 'nullable|string|max:255',
            'value1_text' => 'nullable|string|max:500',
            'value2_title' => 'nullable|string|max:255',
            'value2_text' => 'nullable|string|max:500',
            'value3_title' => 'nullable|string|max:255',
            'value3_text' => 'nullable|string|max:500',
            'badge_title' => 'nullable|string|max:255',
            'badge_sub' => 'nullable|string|max:500',
            'chat_widget_enabled' => 'nullable',
        ]);

        Setting::set('contact.business_hours', (string) ($validated['business_hours'] ?? ''));
        Setting::set('contact.response_time', (string) ($validated['response_time'] ?? ''));
        Setting::set('returns.window_days', $validated['window_days'] ?? null);
        Setting::set('returns.process_steps', $this->parseSteps($validated['returns_process_steps'] ?? ''));
        Setting::set('shipping.process_steps', $this->parseSteps($validated['shipping_process_steps'] ?? ''));
        Setting::set('shipping.areas', (string) ($validated['areas'] ?? ''));
        Setting::set('shipping.important_info', (string) ($validated['important_info'] ?? ''));
        Setting::set('shipping.free_threshold', $validated['free_threshold'] ?? null);
        Setting::set('press.contact_name', (string) ($validated['press_contact_name'] ?? ''));
        Setting::set('press.contact_email', (string) ($validated['press_contact_email'] ?? ''));
        Setting::set('press.contact_phone', (string) ($validated['press_contact_phone'] ?? ''));
        Setting::set('about.founded_year', $validated['founded_year'] ?? null);
        Setting::set('contact.support_card_title', (string) ($validated['support_card_title'] ?? ''));
        Setting::set('contact.support_card_text', (string) ($validated['support_card_text'] ?? ''));
        Setting::set('help.aside_title', (string) ($validated['help_aside_title'] ?? ''));
        Setting::set('help.aside_text', (string) ($validated['help_aside_text'] ?? ''));
        Setting::set('shipping.caption_dispatch', (string) ($validated['caption_dispatch'] ?? ''));
        Setting::set('shipping.caption_packing', (string) ($validated['caption_packing'] ?? ''));
        foreach (['value1_title','value1_text','value2_title','value2_text','value3_title','value3_text','badge_title','badge_sub'] as $field) {
            $val = (string) ($validated[$field] ?? '');
            filled($val) ? Setting::set("about.{$field}", $val) : Setting::forget("about.{$field}");
        }

        Setting::set('chat_widget.enabled', $request->boolean('chat_widget_enabled') ? '1' : '0');

        return redirect()->route('admin.content-settings.edit')
            ->with('success', 'Content settings saved successfully.');
    }

    protected function stepsToText(mixed $json): string
    {
        $steps = is_string($json) ? json_decode($json, true) : (is_array($json) ? $json : []);

        if (! is_array($steps)) {
            return '';
        }

        return collect($steps)
            ->map(fn ($step) => trim(($step['title'] ?? '').' | '.($step['description'] ?? '')))
            ->implode("\n");
    }

    protected function parseSteps(string $text): string
    {
        $steps = collect(preg_split('/\r\n|\r|\n/', $text))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->map(function ($line) {
                [$title, $description] = array_pad(explode('|', $line, 2), 2, '');

                return [
                    'title' => trim($title),
                    'description' => trim($description),
                ];
            })
            ->values()
            ->all();

        return json_encode($steps, JSON_UNESCAPED_UNICODE);
    }
}
