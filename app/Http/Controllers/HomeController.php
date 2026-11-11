<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Content;
use App\Models\Menu;
use App\Models\Setting;
use App\Support\Media;

class HomeController extends Controller
{
    public function index()
    {
        $slides = Article::published()->with('category')
            ->orderByDesc('is_featured')->orderByDesc('created_at')->orderByDesc('id')
            ->limit(5)->get();

        $latest = Article::latestPublished()->with('category')->limit(6)->get();

        $services = Menu::where('jenis', 'layanan')->where('aktif', true)->whereNull('parent_id')
            ->orderBy('urutan')->orderBy('id')->get(['title', 'url', 'ikon']);

        $set = Setting::allKeyed();

        // Foto latar hero: gambar admin > Konten > Slider; bila kosong, memakai foto berita.
        $bg = Content::ofType('slider')->published()
            ->whereNotNull('berkas')->where('berkas', '<>', '')
            ->orderByDesc('created_at')->orderByDesc('id')->limit(6)->pluck('berkas')
            ->map(fn ($f) => Media::url($f))->filter()->values();
        if ($bg->isEmpty()) {
            $bg = $slides->map(fn ($a) => $a->image_url)->filter()->values();
        }

        return view('public.home', [
            'slides' => $slides,
            'bg' => $bg,
            'heroKecil' => ($set['hero_kecil'] ?? '') ?: 'Selamat Datang di',
            'heroJudul' => ($set['hero_judul'] ?? '') ?: 'Pemerintah Kota Manado',
            'heroTombol' => ($set['hero_tombol'] ?? '') ?: 'Jelajahi Berita',
            'heroUrl' => ($set['hero_tombol_url'] ?? '') ?: route('berita.index'),
            'latest' => $latest,
            'services' => $services,
            'aboutTitle' => ($set['tentang_judul'] ?? '') ?: 'DISKOMINFO Kota Manado',
            'aboutText' => ($set['tentang_isi'] ?? '') ?: 'Dinas Komunikasi dan Informatika Kota Manado mendukung pengelolaan komunikasi, informatika, informasi publik, dan layanan digital pemerintahan.',
        ]);
    }
}
