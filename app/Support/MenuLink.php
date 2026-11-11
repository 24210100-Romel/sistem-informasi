<?php

namespace App\Support;

/**
 * Menerjemahkan URL yang diketik di Menu Manager menjadi tautan publik.
 *
 * Mendukung penulisan baru ("halaman/visi-dan-misi", "berita", "kontak", "https://...")
 * sekaligus penulisan lama dari sistem PHP native ("artikel.php", "halaman.php?slug=x", ...),
 * sehingga data menu lama tetap berfungsi.
 */
class MenuLink
{
    private const LEGACY = [
        'index.php' => '/',
        'artikel.php' => 'berita',
        'profil.php' => 'profil',
        'tentang-kami.php' => 'tentang-kami',
        'kontak.php' => 'kontak',
    ];

    public static function isUnsafe(string $url): bool
    {
        return (bool) preg_match('/^\s*(javascript|data|vbscript):/i', $url);
    }

    public static function href(?string $url): string
    {
        $u = trim((string) $url);
        if ($u === '' || $u === '#' || self::isUnsafe($u)) {
            return '#';
        }
        if (preg_match('#^(https?:|mailto:|tel:)#i', $u)) {
            return $u;
        }

        $u = ltrim($u, '/');
        if ($u === '') {
            return url('/');
        }
        if (preg_match('#^halaman\.php\?slug=([\w\-]+)$#', $u, $m)) {
            return url('halaman/'.$m[1]);
        }
        if (preg_match('#^artikel-detail\.php\?slug=([^&\#]+)$#', $u, $m)) {
            return url('berita/'.$m[1]);
        }

        $path = strtok($u, '?#');
        if (isset(self::LEGACY[$path])) {
            return url(self::LEGACY[$path]);
        }

        return url($u);
    }

    /** Apakah $url menunjuk ke halaman yang sedang dibuka? */
    public static function isCurrent(?string $url): bool
    {
        $h = self::href($url);
        if ($h === '#') {
            return false;
        }

        return rtrim((string) strtok($h, '?#'), '/') === rtrim(url()->current(), '/');
    }

    /** Apakah menu atau salah satu turunannya sedang aktif? */
    public static function branchActive(array $by, object $item): bool
    {
        if (self::isCurrent($item->url)) {
            return true;
        }
        foreach ($by[(int) $item->id] ?? [] as $child) {
            if (self::branchActive($by, $child)) {
                return true;
            }
        }

        return false;
    }
}
