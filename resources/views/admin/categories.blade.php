@extends('layouts.admin')
@section('title', 'Manajemen Kategori')

@section('content')
<div class="row g-4"><div class="col-lg-5"><div class="card shadow-sm"><div class="card-body p-4">
  <h5 class="fw-bold mb-1">{{ $edit ? 'Edit Kategori' : 'Tambah Kategori' }}</h5><p class="text-muted small">Kelompokkan berita agar mudah dikelola dan ditampilkan.</p>
  <form method="post" action="{{ $edit ? route('admin.kategori.update', $edit) : route('admin.kategori.store') }}">
    @csrf @if($edit) @method('PUT') @endif
    <label class="form-label">Nama kategori</label>
    <input class="form-control mb-3" name="nama" value="{{ old('nama', $edit->nama ?? '') }}" maxlength="100" required>
    <button class="btn btn-primary">{{ $edit ? 'Simpan Perubahan' : 'Tambah Kategori' }}</button>
    @if($edit)<a href="{{ route('admin.kategori.index') }}" class="btn btn-light border">Batal</a>@endif
  </form>
</div></div></div>
<div class="col-lg-7"><div class="card shadow-sm"><div class="card-body p-0">
  <div class="p-4 border-bottom"><h5 class="fw-bold mb-0">Daftar Kategori</h5></div>
  <div class="table-responsive"><table class="table align-middle mb-0"><thead><tr><th class="ps-4">Kategori</th><th>Jumlah Berita</th><th class="text-end pe-4">Aksi</th></tr></thead><tbody>
  @forelse($categories as $c)
    <tr><td class="ps-4"><strong>{{ $c->nama }}</strong></td><td>{{ $c->articles_count }}</td><td class="text-end pe-4">
      <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.kategori.index', ['edit' => $c->id]) }}">Edit</a>
      <form method="post" action="{{ route('admin.kategori.destroy', $c) }}" class="d-inline" onsubmit="return confirm('Hapus kategori ini? Berita tidak akan ikut terhapus.')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Hapus</button></form>
    </td></tr>
  @empty
    <tr><td colspan="3" class="text-center text-muted py-4">Belum ada kategori.</td></tr>
  @endforelse
  </tbody></table></div>
</div></div></div></div>
@endsection
