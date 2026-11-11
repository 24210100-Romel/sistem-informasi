<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Penghitung pengunjung untuk halaman publik.
 * Alamat IP tidak disimpan (hanya hash dengan user-agent); bot diabaikan.
 */
class CountVisit
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('GET')) {
            $ua = (string) $request->userAgent();
            if ($ua !== '' && ! preg_match('/bot|crawl|spider|slurp|curl|wget|monitor/i', $ua)) {
                try {
                    DB::table('visit_log')->upsert(
                        [['tgl' => now()->toDateString(), 'vid' => md5($request->ip().'|'.$ua), 'views' => 1]],
                        ['tgl', 'vid'],
                        ['views' => DB::raw('views + 1')],
                    );
                } catch (Throwable $e) {
                    report($e);
                }
            }
        }

        return $next($request);
    }
}
