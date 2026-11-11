@extends('layouts.admin')
@section('title', 'Manajemen Pengguna')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
  <div><p class="text-muted mb-0">Kelola akun yang dapat masuk ke dashboard dan mengelola konten.</p></div>
  <a href="{{ route('admin.pengguna.create') }}" class="btn btn-primary"><i class="bi bi-person-plus-fill me-1"></i> Tambah Pengguna</a>
</div>
<div class="card border-0 shadow-sm mb-4"><div class="card-body p-3">
<form class="row g-2" method="get">
  <div class="col-md-9"><div class="input-group"><span class="input-group-text bg-white"><i class="bi bi-search"></i></span><input name="q" value="{{ $q }}" class="form-control" placeholder="Cari nama, username, atau role..."></div></div>
  <div class="col-md-3 d-grid"><button class="btn btn-light border">Cari Pengguna</button></div>
</form></div></div>
<div class="card border-0 shadow-sm"><div class="card-body p-0"><div class="table-responsive"><table class="table mb-0 align-middle">
<thead><tr><th class="ps-4">Pengguna</th><th>Username</th><th>Role</th><th>Berita</th><th>Dibuat</th><th class="text-end pe-4">Aksi</th></tr></thead><tbody>
@forelse($users as $u)
<tr>
<td class="ps-4"><div class="d-flex align-items-center gap-3"><span class="avatar"><i class="bi bi-person-fill"></i></span><div><div class="fw-semibold">{{ $u->nama }} @if($u->id === auth()->id())<span class="badge bg-light text-dark border ms-1">Anda</span>@endif</div><small class="text-muted">ID #{{ $u->id }}</small></div></div></td>
<td>{{ $u->username }}</td>
<td><span class="badge {{ $u->role === 'admin' ? 'bg-danger' : 'bg-primary' }} text-capitalize">{{ $u->role }}</span></td>
<td>{{ $u->articles_count }}</td>
<td class="small text-muted">{{ $u->created_at?->translatedFormat('d M Y') }}</td>
<td class="text-end pe-4"><div class="btn-group">
<a class="btn btn-sm btn-light border" href="{{ route('admin.pengguna.edit', $u) }}" title="Edit"><i class="bi bi-pencil"></i></a>
@if($u->id !== auth()->id())<form method="post" action="{{ route('admin.pengguna.destroy', $u) }}" onsubmit="return confirm('Hapus pengguna ini? Akun tidak dapat dipulihkan.');">@csrf @method('DELETE')<button class="btn btn-sm btn-light border text-danger" title="Hapus"><i class="bi bi-trash"></i></button></form>@endif
</div></td></tr>
@empty
<tr><td colspan="6"><div class="empty-state"><i class="bi bi-person-x fs-2 d-block mb-2"></i>Pengguna tidak ditemukan.</div></td></tr>
@endforelse
</tbody></table></div></div></div>
@endsection
