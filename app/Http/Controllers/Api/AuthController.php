<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Support\ApiToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends ApiController
{
    public function login(Request $request): JsonResponse
    {
        $username = trim((string) $request->input('username'));
        $password = (string) $request->input('password');

        if ($username === '' || $password === '') {
            return $this->fail('Username dan password wajib diisi.', 422);
        }

        $user = User::where('username', $username)->first();
        if (! $user || ! Hash::check($password, $user->password)) {
            return $this->fail('Username atau password salah.', 401);
        }

        return $this->ok([
            'message' => 'Login berhasil.',
            'token' => ApiToken::issue($user),
            'token_type' => 'Bearer',
            'expires_in' => (int) config('diskominfo.api_token_ttl'),
            'user' => $this->user($user),
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return $this->ok(['user' => $this->user($request->user())]);
    }
}
