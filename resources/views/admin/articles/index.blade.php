@extends('layouts.admin')
@section('title', 'Kelola Artikel')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-3"><div><h2 class="h4 fw-bold mb-0">Kelola Artikel</h2><p class="text-muted mb-0">Tambah, edit dan kelola artikel berita</p></div><a href="{{ route('admin.artikel.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Artikel</a></div>
<div class="ad-card p-0 overflow-hidden"><div class="table-responsive"><table class="ad-tbl"><thead><tr><th>No</th><th>Judul Artikel</th><th>Kategori</th><th>Tanggal</th><th>Aksi</th></tr></thead><tbody>
@forelse($articles as $a)
  <tr>
    <td>{{ $articles->firstItem() + $loop->index }}</td>
    <td>{{ $a->title }}</td>
    <td><span class="ad-cat">{{ $a->category->nama ?? '-' }}</span></td>
    <td>{{ $a->created_at->translatedFormat('d M Y') }}</td>
    <td class="text-nowrap"><a class="ad-ic e" href="{{ route('admin.artikel.edit', $a) }}" aria-label="Edit"><i class="bi bi-pencil-fill"></i></a>
      <form method="post" action="{{ route('admin.artikel.destroy', $a) }}" class="d-inline" onsubmit="return confirm('Hapus artikel ini?')">@csrf @method('DELETE')<button class="ad-ic d" aria-label="Hapus"><i class="bi bi-trash-fill"></i></button></form></td>
  </tr>
@empty
  <tr><td colspan="5" class="text-center text-muted py-4">Belum ada artikel.</td></tr>
@endforelse
</tbody></table></div>
<div class="d-flex justify-content-between align-items-center p-3 small">
  <span>Menampilkan {{ $articles->count() ? $articles->firstItem() : 0 }}-{{ $articles->count() ? $articles->lastItem() : 0 }} dari {{ $articles->total() }} data</span>
  @if($articles->hasPages()){{ $articles->links() }}@endif
</div></div>
@endsection
