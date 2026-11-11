<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Support\ApiToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Otentikasi Bearer token untuk API.
 * Pemakaian: "api.auth" (pengguna mana pun), "api.auth:staff" (admin/operator), "api.auth:admin".
 */
class ApiAuthenticate
{
    public function handle(Request $request, Closure $next, string $level = 'any'): Response
    {
        if (! $request->bearerToken()) {
            return $this->fail('Authorization Bearer token diperlukan.', 401);
        }

        $user = static::userFrom($request);
        if (! $user) {
            return $this->fail('Token tidak valid atau sudah kedaluwarsa.', 401);
        }

        if ($level === 'admin' && ! $user->isAdmin()) {
            return $this->fail('Akses hanya untuk admin.', 403);
        }
        if ($level === 'staff' && ! in_array($user->role, ['admin', 'operator'], true)) {
            return $this->fail('Akses ditolak.', 403);
        }

        $request->setUserResolver(fn () => $user);

        return $next($request);
    }

    /** Pengguna dari Bearer token (peran dibaca dari database, bukan dari klaim token). */
    public static function userFrom(Request $request): ?User
    {
        $token = $request->bearerToken();
        $claims = $token ? ApiToken::verify($token) : null;

        return $claims ? User::find((int) $claims['sub']) : null;
    }

    private function fail(string $message, int $status): Response
    {
        return response()->json(['success' => false, 'message' => $message], $status);
    }
}
