@extends('layouts.admin')
@section('title', 'Tambah Pengguna')

@section('content')
<div class="row justify-content-center"><div class="col-xl-8"><div class="card border-0 shadow-sm"><div class="card-body p-4 p-lg-5">
<div class="d-flex align-items-center gap-3 mb-4"><span class="avatar" style="width:52px;height:52px"><i class="bi bi-person-plus-fill fs-5"></i></span><div><h5 class="fw-bold mb-1">Tambah pengguna</h5><p class="text-muted mb-0">Buat akun admin atau operator baru.</p></div></div>
<form method="post" action="{{ route('admin.pengguna.store') }}">@csrf
<div class="row g-3"><div class="col-md-6"><label class="form-label fw-semibold">Nama Lengkap</label><input name="nama" class="form-control" value="{{ old('nama') }}" required maxlength="100"></div>
<div class="col-md-6"><label class="form-label fw-semibold">Username</label><input name="username" class="form-control" value="{{ old('username') }}" required><div class="form-text">3–50 karakter: huruf, angka, titik, _ atau -.</div></div>
<div class="col-md-6"><label class="form-label fw-semibold">Role</label><select name="role" class="form-select"><option value="operator" @selected(old('role') === 'operator')>Operator</option><option value="admin" @selected(old('role') === 'admin')>Admin</option></select></div>
<div class="col-md-6"><label class="form-label fw-semibold">Password</label><input type="password" name="password" class="form-control" minlength="6" required autocomplete="new-password"><div class="form-text">Minimal 6 karakter.</div></div></div>
<div class="d-flex gap-2 mt-4"><button class="btn btn-primary"><i class="bi bi-check2 me-1"></i>Simpan Pengguna</button><a href="{{ route('admin.pengguna.index') }}" class="btn btn-light border">Batal</a></div>
</form></div></div></div></div>
@endsection
