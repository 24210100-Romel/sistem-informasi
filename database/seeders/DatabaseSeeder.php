<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Data awal setara database.sql + isi bawaan Menu Manager dari sistem PHP native.
 * Aman dijalankan berulang kali (tidak menggandakan data).
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Kata sandi admin awal: ADMIN_PASSWORD di .env. Tanpa itu: 'password' (lokal/dev),
        // atau acak yang ditampilkan SEKALI di layar bila APP_ENV=production.
        $password = (string) config('diskominfo.admin_password', '');
        $generated = false;
        if ($password === '') {
            if (app()->isProduction()) {
                $password = \Illuminate\Support\Str::password(16, symbols: false);
                $generated = true;
            } else {
                $password = 'password';
            }
        }

        $admin = User::where('username', 'admin')->first();
        if (! $admin) {
            $admin = User::create(['nama' => 'Administrator DISKOMINFO', 'username' => 'admin', 'password' => $password, 'role' => 'admin']);
            if ($generated) {
                $this->command?->warn("Akun admin dibuat. Username: admin  |  Password: {$password}  (catat sekarang, tidak ditampilkan lagi)");
            }
        }

        foreach (['Berita', 'Informasi', 'Program'] as $nama) {
            Category::firstOrCreate(['nama' => $nama]);
        }

        $items = [
            ['Diskominfo Manado Dorong Transformasi Digital di Era Modern', 'Berita', '2026-10-29 09:00', true],
            ['Pemkot Manado Tingkatkan Pelayanan Informasi Publik', 'Informasi', '2026-10-28 09:00', false],
            ['Diskominfo Gelar Sosialisasi Literasi Digital', 'Informasi', '2026-10-27 09:00', false],
            ['Manado Menuju Kota Cerdas dan Terintegrasi', 'Program', '2026-10-25 09:00', false],
        ];
        foreach ($items as [$title, $cat, $at, $feat]) {
            $slug = \Illuminate\Support\Str::slug($title);
            if (Article::where('slug', $slug)->exists()) {
                continue;
            }
            $a = Article::create([
                'title' => $title, 'slug' => $slug,
                'content' => '<p>'.e($title).'. Dinas Komunikasi dan Informatika Kota Manado terus berkomitmen memberikan informasi terbaik untuk masyarakat.</p>',
                'category_id' => Category::where('nama', $cat)->value('id'),
                'author_id' => $admin->id, 'status' => 'published', 'is_featured' => $feat,
            ]);
            $a->forceFill(['created_at' => $at, 'updated_at' => $at])->save();
        }

        // Total pengguna = 12 (admin + 11 pengguna contoh).
        foreach (['Andi Saputra', 'Budi Santoso', 'Citra Lestari', 'Dewi Pangemanan', 'Eko Wowor', 'Fina Mokodompit', 'Gerry Tumbelaka', 'Hana Lumentut', 'Ivan Rondonuwu', 'Joyce Kawulur', 'Kevin Sumampouw'] as $nama) {
            $u = \Illuminate\Support\Str::slug($nama, '.');
            if (! User::where('username', $u)->exists()) {
                User::create(['nama' => $nama, 'username' => $u, 'password' => $password, 'role' => 'operator']);
            }
        }

        foreach (['telepon' => '(0431) 123456', 'email' => 'diskominfo@manadokota.go.id', 'alamat' => 'Jl. Balai Kota No.1, Manado', 'nama_situs' => 'DISKOMINFO Kota Manado'] as $k => $v) {
            \App\Models\Setting::firstOrCreate(['k' => $k], ['v' => $v]);
        }

        $this->seedMenus();
    }

    private function seedMenus(): void
    {
        if (! Menu::where('jenis', 'utama')->exists()) {
            $tree = [
                ['Beranda', '/', []],
                ['Pemerintahan', '#', [
                    ['Visi dan Misi', 'halaman/visi-dan-misi'],
                    ['Profil Walikota', 'halaman/profil-walikota'],
                    ['Profil Wakil Walikota', 'halaman/profil-wakil-walikota'],
                    ['Perangkat Daerah', 'halaman/perangkat-daerah'],
                    ['Produk Hukum', 'halaman/produk-hukum'],
                ]],
                ['Mengenal Manado', '#', [
                    ['Selayang Pandang', 'halaman/selayang-pandang'],
                    ['Sejarah', 'halaman/sejarah'],
                    ['Arti Lambang', 'halaman/arti-lambang'],
                ]],
                ['Layanan dan Informasi', '#', [
                    ['Layanan Publik', 'halaman/layanan-publik'],
                    ['Berita', 'berita'],
                    ['Pengumuman', 'halaman/pengumuman'],
                ]],
                ['Datang dan Kunjungi', '#', []],
            ];
            foreach ($tree as $i => [$title, $url, $kids]) {
                $parent = Menu::create(['jenis' => 'utama', 'title' => $title, 'url' => $url, 'urutan' => $i + 1]);
                foreach ($kids as $j => [$ct, $cu]) {
                    Menu::create(['jenis' => 'utama', 'parent_id' => $parent->id, 'title' => $ct, 'url' => $cu, 'urutan' => $j + 1]);
                }
            }
        }

        if (! Menu::where('jenis', 'footer')->exists()) {
            foreach ([['Beranda', '/'], ['Visi dan Misi', 'halaman/visi-dan-misi'], ['Selayang Pandang', 'halaman/selayang-pandang'],
                ['Perangkat Daerah', 'halaman/perangkat-daerah'], ['Berita', 'berita'], ['Kontak', 'kontak']] as $i => [$t, $u]) {
                Menu::create(['jenis' => 'footer', 'title' => $t, 'url' => $u, 'urutan' => $i + 1]);
            }
        }

        if (! Menu::where('jenis', 'layanan')->exists()) {
            foreach ([['Informasi Publik PPID', 'berita', 'bi-megaphone-fill'], ['Berita & Pengumuman', 'berita', 'bi-file-earmark-text-fill'],
                ['Layanan Digital', 'berita', 'bi-laptop'], ['Peta & Lokasi', 'kontak', 'bi-geo-alt-fill'],
                ['Hubungi Kami', 'kontak', 'bi-headset'], ['Pengaduan Masyarakat', 'kontak', 'bi-chat-dots-fill']] as $i => [$t, $u, $ic]) {
                Menu::create(['jenis' => 'layanan', 'title' => $t, 'url' => $u, 'urutan' => $i + 1, 'ikon' => $ic, 'aktif' => $i < 5]);
            }
        }
    }
}
