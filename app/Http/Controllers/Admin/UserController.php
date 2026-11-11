<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q'));

        $users = User::withCount('articles')
            ->when($q !== '', fn ($query) => $query->where(function ($w) use ($q) {
                $like = '%'.$q.'%';
                $w->where('nama', 'like', $like)->orWhere('username', 'like', $like)->orWhere('role', 'like', $like);
            }))
            ->orderByDesc('created_at')->orderByDesc('id')
            ->get();

        return view('admin.users.index', compact('users', 'q'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        User::create($this->validated($request));

        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $this->validated($request, $user);

        if ($user->id === $request->user()->id && $data['role'] !== 'admin') {
            return back()->withInput()->withErrors(['role' => 'Akun Anda tidak dapat menurunkan role sendiri dari admin.']);
        }
        if ($user->isAdmin() && $data['role'] !== 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()->withInput()->withErrors(['role' => 'Admin terakhir tidak dapat diturunkan rolenya.']);
        }

        $user->update($data);

        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->with('danger', 'Akun Anda sendiri tidak dapat dihapus.');
        }
        if ($user->isAdmin() && User::where('role', 'admin')->count() <= 1) {
            return back()->with('danger', 'Admin terakhir tidak dapat dihapus. Tambahkan admin lain terlebih dahulu.');
        }

        $user->delete(); // berita tetap ada (author_id menjadi NULL)

        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    private function validated(Request $request, ?User $user = null): array
    {
        $request->merge([
            'nama' => trim((string) $request->input('nama')),
            'username' => trim((string) $request->input('username')),
        ]);

        $v = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'username' => ['required', 'regex:/^[A-Za-z0-9._-]{3,50}$/', Rule::unique('users', 'username')->ignore($user)],
            'role' => ['required', Rule::in(['admin', 'operator'])],
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:6'],
        ], [
            'username.regex' => 'Username hanya boleh berisi huruf, angka, titik, garis bawah, atau tanda minus (3–50 karakter).',
            'username.unique' => 'Username sudah digunakan.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.required' => 'Password minimal 6 karakter.',
        ]);

        if (($v['password'] ?? '') === '') {
            unset($v['password']); // kosong = tidak diubah
        }

        return $v;
    }
}
