<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Menu;
use App\Models\User;

class DashboardController extends Controller
{
    public function __invoke()
    {
        // Jumlah artikel dibuat per bulan, 12 bulan terakhir.
        $perMonth = Article::where('created_at', '>=', now()->subMonths(11)->startOfMonth())->pluck('created_at')
            ->countBy(fn ($d) => $d->format('Y-m'));
        $days = [];
        $counts = [];
        for ($i = 11; $i >= 0; $i--) {
            $m = now()->subMonths($i);
            $days[] = $m->translatedFormat('M');
            $counts[] = (int) ($perMonth[$m->format('Y-m')] ?? 0);
        }

        $activity = collect();
        foreach (Article::orderByDesc('created_at')->limit(3)->get() as $a) {
            $activity->push(['icon' => 'bi-file-earmark-text', 'text' => 'Artikel baru ditambahkan "'.\Illuminate\Support\Str::limit($a->title, 28).'"', 'when' => $a->created_at->diffForHumans(), 'at' => $a->created_at]);
        }
        foreach (User::orderByDesc('created_at')->limit(2)->get() as $u) {
            $activity->push(['icon' => 'bi-person', 'text' => 'Pengguna baru mendaftar '.($u->nama ?: $u->username), 'when' => $u->created_at->diffForHumans(), 'at' => $u->created_at]);
        }

        $user = auth()->user();

        return view('admin.dashboard', [
            'userName' => $user->nama ?: $user->username,
            'articleCount' => Article::count(),
            'userCount' => User::count(),
            'serviceCount' => Menu::where('jenis', 'layanan')->count(),
            'days' => $days,
            'counts' => $counts,
            'activity' => $activity->sortByDesc('at')->take(3)->values()->all(),
        ]);
    }
}
