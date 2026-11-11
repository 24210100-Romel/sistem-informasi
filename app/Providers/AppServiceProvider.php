<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\Content;
use App\Models\Menu;
use App\Models\Setting;
use App\Services\SiteAssistant;
use App\Support\MenuLink;
use App\Support\Media;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        if ($proxies = config('diskominfo.trusted_proxies')) {
            \Illuminate\Http\Middleware\TrustProxies::at($proxies === '*' ? '*' : array_map('trim', explode(',', $proxies)));
        }

        // Produksi dengan APP_URL https: semua URL (aset, form, redirect) memakai https.
        if ($this->app->isProduction() && str_starts_with((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        RateLimiter::for('api', fn (Request $r) => Limit::perMinute(60)->by($r->ip()));
        RateLimiter::for('chat', fn (Request $r) => [
            Limit::perMinute((int) config('diskominfo.ai.per_minute'))->by('chat-m|'.$r->ip()),
            Limit::perDay((int) config('diskominfo.ai.per_day'))->by('chat-d|'.$r->ip()),
        ]);
        // Login: 5 percobaan/menit per akun+IP, dan 30 per jam per IP (menahan tebak-sandi dan serangan berantai).
        RateLimiter::for('login', fn (Request $r) => [
            Limit::perMinute(5)->by('login-u|'.strtolower((string) $r->input('username')).'|'.$r->ip()),
            Limit::perHour(30)->by('login-ip|'.$r->ip()),
        ]);

        // Data bersama untuk kerangka halaman publik: navbar, footer, statistik pengunjung.
        View::composer('layouts.public', function ($view) {
            $navBy = Menu::groupByParent(
                Menu::where('jenis', 'utama')->where('aktif', true)->orderBy('urutan')->orderBy('id')->get()
            );

            $set = Setting::allKeyed();

            $footerMenu = Menu::where('jenis', 'footer')->where('aktif', true)->whereNull('parent_id')
                ->orderBy('urutan')->orderBy('id')->get(['title', 'url']);
            if ($footerMenu->isEmpty()) {
                $footerMenu = collect([
                    ['title' => 'Beranda', 'url' => '/'], ['title' => 'Profil', 'url' => 'profil'],
                    ['title' => 'Tentang Kami', 'url' => 'tentang-kami'], ['title' => 'Kontak', 'url' => 'kontak'],
                ])->map(fn ($m) => (object) $m);
            }

            $links = Content::ofType('sumber')->published()->whereNotNull('tautan')->where('tautan', '<>', '')
                ->orderBy('created_at')->orderBy('id')->limit(8)->get(['judul', 'tautan']);
            if ($links->isEmpty()) {
                $links = collect([
                    ['judul' => 'Republik Indonesia', 'tautan' => 'https://indonesia.go.id'],
                    ['judul' => 'Sekretariat Negara', 'tautan' => 'https://www.setneg.go.id'],
                    ['judul' => 'Sekretariat Kabinet', 'tautan' => 'https://setkab.go.id'],
                    ['judul' => 'Pemerintah Prov. Sulut', 'tautan' => 'https://sulutprov.go.id'],
                ])->map(fn ($m) => (object) $m);
            }

            $today = now()->toDateString();
            $stat = [
                'today' => (int) DB::table('visit_log')->where('tgl', $today)->count(),
                'total' => (int) DB::table('visit_log')->count(),
                'pages' => (int) DB::table('visit_log')->where('tgl', $today)->sum('views'),
            ];

            $view->with([
                'navBy' => $navBy,
                'site' => $set,
                'footerMenu' => $footerMenu,
                'footerLinks' => $links,
                'visitStat' => $stat,
                'aiEnabled' => ($set['ai_aktif'] ?? '1') !== '0',
                'siteName' => ($set['nama_situs'] ?? '') ?: 'Pemerintah Kota Manado',
            ]);
        });

        // Latar hero halaman dalam: foto yang sama dengan beranda (Konten > Slider).
        View::composer('components.page-banner', function ($view) {
            $bg = Content::ofType('slider')->published()
                ->whereNotNull('berkas')->where('berkas', '<>', '')
                ->orderByDesc('created_at')->orderByDesc('id')->limit(1)->pluck('berkas')
                ->map(fn ($f) => Media::url($f))->filter()->first();

            $view->with('bannerBg', $bg);
        });

        // Sidebar halaman dalam: Layanan Informasi + Berita Terbaru (sama seperti beranda).
        View::composer('partials.sidebar', function ($view) {
            $except = (int) ($view->getData()['exceptId'] ?? 0);

            $services = Menu::where('jenis', 'layanan')->where('aktif', true)->whereNull('parent_id')
                ->orderBy('urutan')->orderBy('id')->get(['title', 'url', 'ikon']);

            $recent = Article::latestPublished()->with('category')
                ->when($except, fn ($q) => $q->where('id', '<>', $except))
                ->limit(4)->get();

            $view->with(['sideServices' => $services, 'sideRecent' => $recent]);
        });

        View::share('menuHref', fn (?string $u) => MenuLink::href($u));
    }
}
