<?php

namespace App\Services;

/**
 * Allowlist-based HTML sanitizer for CMS content authored in the admin
 * rich-text editor. Strips scripts, event handlers, and unknown tags so
 * stored content is always safe to render on customer-facing pages.
 */
class HtmlSanitizer
{
    protected array $allowedTags = [
        'p', 'h1', 'h2', 'h3', 'h4', 'strong', 'b', 'em', 'i', 'u', 's',
        'br', 'hr', 'ul', 'ol', 'li', 'blockquote', 'a', 'img',
        'table', 'thead', 'tbody', 'tr', 'th', 'td', 'code', 'pre', 'span',
    ];

    public function clean(?string $html): string
    {
        $html = trim((string) $html);

        if ($html === '') {
            return '';
        }

        if (! class_exists('DOMDocument')) {
            return strip_tags($html, '<'.implode('><', $this->allowedTags).'>');
        }

        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML(
            '<?xml encoding="utf-8"?><div id="__sanitizer_root">'.$html.'</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();

        $this->sanitizeNode($dom->getElementById('__sanitizer_root'));

        $inner = '';
        foreach ($dom->getElementById('__sanitizer_root')->childNodes as $child) {
            $inner .= $dom->saveHTML($child);
        }

        return trim($inner);
    }

    protected function sanitizeNode(\DOMNode $node): void
    {
        $children = iterator_to_array($node->childNodes);

        foreach ($children as $child) {
            if ($child instanceof \DOMElement) {
                $tag = strtolower($child->tagName);

                if (! in_array($tag, $this->allowedTags, true)) {
                    if ($tag === 'script' || $tag === 'style' || $tag === 'iframe' || $tag === 'object' || $tag === 'embed') {
                        $child->parentNode?->removeChild($child);
                        continue;
                    }

                    // Keep harmless unknown containers but unwrap them.
                    $parent = $child->parentNode;
                    while ($child->firstChild) {
                        $parent->insertBefore($child->firstChild, $child);
                    }
                    $parent->removeChild($child);
                    continue;
                }

                $this->sanitizeAttributes($child, $tag);

                // Anchor headings so legal documents can build a TOC.
                if (in_array($tag, ['h2', 'h3'], true) && ! $child->getAttribute('id')) {
                    $text = trim(preg_replace('/\s+/', ' ', $child->textContent)) ?: 'section';
                    $child->setAttribute('id', \Illuminate\Support\Str::slug(\Illuminate\Support\Str::limit($text, 60)));
                }
            }

            if ($child->hasChildNodes()) {
                $this->sanitizeNode($child);
            }
        }
    }

    protected function sanitizeAttributes(\DOMElement $element, string $tag): void
    {
        $allowed = match ($tag) {
            'a' => ['href', 'title', 'rel', 'target'],
            'img' => ['src', 'alt', 'width', 'height', 'loading'],
            'th', 'td' => ['colspan', 'rowspan'],
            default => [],
        };

        foreach (iterator_to_array($element->attributes) as $attr) {
            $name = strtolower($attr->name);

            if ($name === 'style') {
                // Only text alignment survives sanitization.
                if (preg_match('/text-align\s*:\s*(left|center|right|justify)/i', $attr->value, $m)) {
                    $element->setAttribute('style', 'text-align: '.strtolower($m[1]).';');
                } else {
                    $element->removeAttribute('style');
                }
                continue;
            }

            if (! in_array($name, $allowed, true)) {
                $element->removeAttribute($attr->name);
                continue;
            }

            $value = trim($attr->value);

            if (preg_match('/(javascript|vbscript|data:text)\s*:/i', $value)) {
                $element->removeAttribute($attr->name);
                continue;
            }
        }

        if ($tag === 'a') {
            $href = trim($element->getAttribute('href'));
            $safeHref = $href !== '' && (
                str_starts_with($href, '/')
                || str_starts_with($href, '#')
                || preg_match('#^(https?:|mailto:|tel:)#i', $href)
            );

            if (! $safeHref) {
                $element->removeAttribute('href');
            }

            $element->setAttribute('rel', 'noopener noreferrer');
            $element->removeAttribute('target');
        }

        if ($tag === 'img') {
            $src = trim($element->getAttribute('src'));
            if ($src === '' || (! str_starts_with($src, asset('storage/')) && ! str_starts_with($src, '/storage/') && ! preg_match('#^https?://#i', $src))) {
                $element->removeAttribute('src');
            } else {
                $element->setAttribute('loading', 'lazy');
            }
        }
    }
}
