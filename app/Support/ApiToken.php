<?php

namespace App\Support;

use App\Models\User;

/**
 * Token Bearer bertanda tangan HS256 (format JWT), tanpa tabel tambahan.
 */
class ApiToken
{
    private static function secret(): string
    {
        return (string) (config('diskominfo.api_token_secret') ?: config('app.key'));
    }

    private static function b64(string $v): string
    {
        return rtrim(strtr(base64_encode($v), '+/', '-_'), '=');
    }

    private static function unb64(string $v): string
    {
        $pad = strlen($v) % 4;
        if ($pad) {
            $v .= str_repeat('=', 4 - $pad);
        }

        return base64_decode(strtr($v, '-_', '+/')) ?: '';
    }

    public static function issue(User $user): string
    {
        $h = self::b64(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        $p = self::b64(json_encode([
            'sub' => (int) $user->id,
            'username' => $user->username,
            'role' => $user->role,
            'iat' => time(),
            'exp' => time() + (int) config('diskominfo.api_token_ttl'),
        ]));

        return $h.'.'.$p.'.'.self::b64(hash_hmac('sha256', $h.'.'.$p, self::secret(), true));
    }

    /** @return array<string, mixed>|null klaim token bila valid dan belum kedaluwarsa */
    public static function verify(string $token): ?array
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return null;
        }
        [$h, $p, $s] = $parts;
        $expected = self::b64(hash_hmac('sha256', $h.'.'.$p, self::secret(), true));
        if (! hash_equals($expected, $s)) {
            return null;
        }
        $claims = json_decode(self::unb64($p), true);
        if (! is_array($claims) || empty($claims['sub']) || empty($claims['exp']) || $claims['exp'] < time()) {
            return null;
        }

        return $claims;
    }
}
