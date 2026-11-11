<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;

/**
 * Pembersih HTML untuk konten dari editor rich-text (berita, halaman, pengumuman).
 *
 * Hanya tag dan atribut aman yang dipertahankan; atribut event (onclick, dsb.),
 * <script>, <iframe>, dan URL berbahaya (javascript:, data:) dibuang.
 */
class HtmlSanitizer
{
    private const ALLOWED_TAGS = [
        'p', 'br', 'strong', 'b', 'em', 'i', 'u', 's', 'h2', 'h3', 'h4', 'blockquote', 'ul', 'ol', 'li',
        'a', 'hr', 'table', 'thead', 'tbody', 'tr', 'th', 'td', 'figure', 'figcaption', 'img', 'div', 'span',
    ];

    /** Tag yang dibuang beserta seluruh isinya. */
    private const DROP_TAGS = [
        'script', 'style', 'iframe', 'object', 'embed', 'form', 'input', 'button', 'textarea', 'select',
        'option', 'noscript', 'svg', 'math', 'link', 'meta', 'base', 'head', 'title', 'frame', 'frameset',
        'applet', 'audio', 'video', 'canvas', 'template',
    ];

    private const ALLOWED_ATTRS = [
        'a' => ['href', 'title', 'target', 'rel'],
        'img' => ['src', 'alt', 'title', 'width', 'height'],
        'td' => ['colspan', 'rowspan'],
        'th' => ['colspan', 'rowspan'],
    ];

    public static function clean(?string $html): string
    {
        $html = trim((string) $html);
        if ($html === '') {
            return '';
        }

        $dom = new DOMDocument('1.0', 'UTF-8');
        $prev = libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="UTF-8"><div id="sanitize-root">'.$html.'</div>', LIBXML_NOERROR | LIBXML_NOWARNING | LIBXML_NONET);
        libxml_clear_errors();
        libxml_use_internal_errors($prev);

        $root = $dom->getElementsByTagName('div')->item(0);
        if (! $root) {
            return '';
        }

        self::walk($root);

        $out = '';
        foreach ($root->childNodes as $child) {
            $out .= $dom->saveHTML($child);
        }

        return trim($out);
    }

    private static function walk(DOMNode $parent): void
    {
        foreach (iterator_to_array($parent->childNodes) as $node) {
            if (! $node instanceof DOMElement) {
                if ($node->nodeType === XML_COMMENT_NODE || $node->nodeType === XML_PI_NODE || $node->nodeType === XML_CDATA_SECTION_NODE) {
                    $parent->removeChild($node);
                }

                continue;
            }

            $tag = strtolower($node->tagName);

            if (in_array($tag, self::DROP_TAGS, true)) {
                $parent->removeChild($node);

                continue;
            }

            self::walk($node); // bersihkan anak lebih dulu

            if (! in_array($tag, self::ALLOWED_TAGS, true)) {
                // Tag tidak dikenal: pertahankan isinya saja.
                while ($node->firstChild) {
                    $parent->insertBefore($node->firstChild, $node);
                }
                $parent->removeChild($node);

                continue;
            }

            self::cleanAttributes($node, $tag);
        }
    }

    private static function cleanAttributes(DOMElement $el, string $tag): void
    {
        $allowed = self::ALLOWED_ATTRS[$tag] ?? [];
        $style = null;

        foreach (iterator_to_array($el->attributes) as $attr) {
            $name = strtolower($attr->name);
            if ($name === 'style') {
                $style = self::safeStyle($attr->value);
            }
            if (! in_array($name, $allowed, true)) {
                $el->removeAttribute($attr->name);
            }
        }

        if ($style) {
            $el->setAttribute('style', $style);
        } else {
            $el->removeAttribute('style');
        }

        if ($el->hasAttribute('href')) {
            $href = self::safeUrl($el->getAttribute('href'), ['http', 'https', 'mailto', 'tel']);
            $el->setAttribute('href', $href);
            if ($el->hasAttribute('target')) {
                $el->setAttribute('target', '_blank');
                $el->setAttribute('rel', 'noopener noreferrer');
            }
        }
        if ($tag === 'img') {
            $src = self::safeUrl($el->getAttribute('src'), ['http', 'https']);
            if ($src === '#') {
                $el->parentNode?->removeChild($el);

                return;
            }
            $el->setAttribute('src', $src);
            if (! $el->hasAttribute('alt')) {
                $el->setAttribute('alt', '');
            }
        }
    }

    /** Hanya izinkan "text-align" pada atribut style. */
    private static function safeStyle(string $style): ?string
    {
        if (preg_match('/(?:^|;)\s*text-align\s*:\s*(left|right|center|justify)\s*(?:;|$)/i', $style, $m)) {
            return 'text-align: '.strtolower($m[1]).';';
        }

        return null;
    }

    /** Izinkan skema aman, path relatif, dan jangkar (#); selain itu jadi "#". */
    private static function safeUrl(string $url, array $schemes): string
    {
        $url = trim(preg_replace('/[\x00-\x20]+/', '', $url) ?? '');
        if ($url === '') {
            return '#';
        }
        if (preg_match('/^([a-z][a-z0-9+.\-]*):/i', $url, $m)) {
            return in_array(strtolower($m[1]), $schemes, true) ? $url : '#';
        }

        return $url; // relatif: "/x", "assets/x", "#bagian", "storage/x"
    }
}
