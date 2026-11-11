<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends ApiController
{
    public function index(): JsonResponse
    {
        return $this->ok(['data' => User::orderByDesc('id')->get()->map(fn (User $u) => $this->user($u))]);
    }

    public function store(Request $request): JsonResponse
    {
        $user = User::create($this->validated($request));

        return $this->ok(['message' => 'Pengguna berhasil ditambahkan.', 'id' => $user->id], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $user = User::find($id);
        if (! $user) {
            return $this->fail('Pengguna tidak ditemukan.', 404);
        }

        $data = $this->validated($request, $user);
        if ($user->isAdmin() && $data['role'] !== 'admin' && User::where('role', 'admin')->count() <= 1) {
            return $this->fail('Admin terakhir tidak dapat diturunkan rolenya.', 422);
        }
        $user->update($data);

        return $this->ok(['message' => 'Pengguna berhasil diperbarui.']);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $user = User::find($id);
        if (! $user) {
            return $this->fail('Pengguna tidak ditemukan.', 404);
        }
        if ($user->id === $request->user()->id) {
            return $this->fail('Akun yang sedang digunakan tidak boleh dihapus.', 422);
        }
        if ($user->isAdmin() && User::where('role', 'admin')->count() <= 1) {
            return $this->fail('Admin terakhir tidak dapat dihapus.', 422);
        }
        $user->delete();

        return $this->ok(['message' => 'Pengguna berhasil dihapus.']);
    }

    /** Pada PUT/PATCH, field yang tidak dikirim mempertahankan nilai lama. */
    private function validated(Request $request, ?User $user = null): array
    {
        $in = $request->all();
        $request->merge([
            'nama' => trim((string) ($in['nama'] ?? $user?->nama ?? '')),
            'username' => trim((string) ($in['username'] ?? $user?->username ?? '')),
            'role' => $in['role'] ?? $user?->role ?? 'operator',
        ]);

        $v = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'username' => ['required', 'regex:/^[A-Za-z0-9._-]{3,50}$/', Rule::unique('users', 'username')->ignore($user)],
            'role' => ['required', Rule::in(['admin', 'operator'])],
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:6'],
        ], [
            'username.regex' => 'username hanya boleh berisi huruf, angka, titik, garis bawah, atau tanda minus (3–50 karakter).',
            'password.required' => 'nama, username, password wajib diisi.',
        ]);

        if (($v['password'] ?? '') === '') {
            unset($v['password']);
        }

        return $v;
    }
}
