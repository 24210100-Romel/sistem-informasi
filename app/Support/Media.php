<?php

namespace App\Support;

use Illuminate\Support\Str;

class Media
{
    /**
     * URL publik untuk path berkas yang tersimpan di database.
     * - "uploads/x.jpg"          -> disk public (storage:link)
     * - "assets/uploads/x.jpg"   -> berkas lama dari sistem PHP native (folder public/assets/uploads)
     * - "https://..." atau "/..." -> dipakai apa adanya
     */
    public static function url(?string $path): ?string
    {
        $path = trim((string) $path);
        if ($path === '') {
            return null;
        }
        if (preg_match('#^(https?:)?//#i', $path) || str_starts_with($path, '/')) {
            return $path;
        }
        if (str_starts_with($path, 'assets/')) {
            return asset($path);
        }

        return asset('storage/'.ltrim($path, '/'));
    }

    /** Ringkasan teks polos dari HTML. */
    public static function excerpt(string $html, int $limit): string
    {
        $text = trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($html), ENT_QUOTES, 'UTF-8')));

        return mb_strlen($text) > $limit ? mb_substr($text, 0, $limit).'…' : $text;
    }

    /** Slug dari teks (ASCII, huruf kecil, tanda minus). */
    public static function slug(string $text): string
    {
        return Str::slug($text);
    }
}
