@extends('layouts.admin')
@section('title', 'Pengaturan Berita Utama')

@section('content')
<div class="card featured-card shadow-sm mb-4"><div class="card-body p-4"><div class="d-flex justify-content-between align-items-start gap-3">
  <div><span class="featured-badge"><i class="bi bi-star-fill"></i> BERITA UTAMA SAAT INI</span>
    <h3 class="fw-bold mt-3 mb-1">{{ $featured->title ?? 'Belum ada Berita Utama' }}</h3>
    <p class="text-muted mb-0">{{ $featured ? ($featured->category->nama ?? 'Tanpa kategori').' · '.$featured->created_at->translatedFormat('d M Y') : 'Pilih satu berita terbit untuk tampil paling menonjol di halaman depan.' }}</p></div>
  @if($featured)<img src="{{ $featured->image_url ?: asset('assets/logo-header.png') }}" class="cover-preview" alt="">@endif
</div></div></div>
<div class="card shadow-sm"><div class="card-body p-0">
  <div class="p-4 border-bottom"><h5 class="fw-bold mb-1">Pilih Berita Utama</h5><p class="text-muted small mb-0">Hanya satu berita yang akan menjadi headline di halaman depan.</p></div>
  <form method="post" action="{{ route('admin.berita-utama.update') }}">@csrf
    <div class="p-4">
      <select name="article_id" class="form-select form-select-lg"><option value="0">— Tidak ada Berita Utama —</option>
        @foreach($published as $a)<option value="{{ $a->id }}" @selected($featured && $featured->id === $a->id)>{{ $a->title }}{{ $a->is_featured ? ' ★' : '' }}</option>@endforeach
      </select>
      <div class="form-text mt-2">Artikel harus berstatus Terbit agar dapat dipilih.</div>
    </div>
    <div class="p-4 border-top"><button class="btn btn-primary"><i class="bi bi-check2-circle"></i> Simpan Pengaturan</button></div>
  </form>
</div></div>
@endsection
