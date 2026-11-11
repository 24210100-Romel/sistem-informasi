@php
  $self = route('admin.konten.index', $jenis);
  $listCls = $useBody ? 'col-12 order-2' : 'col-xl-8';
  $formCls = $useBody ? 'col-12 order-1' : 'col-xl-4';
  $status = old('status', $edit->status ?? 'published');
@endphp
@extends('layouts.admin')
@section('title', $label)

@section('content')
<div class="crumb">Konten &rsaquo; <b>{{ $label }}</b></div>
<div class="row g-4">
  <div class="{{ $listCls }}">
    <div class="tree-card">
      <div class="tree-head"><div>Judul</div><div class="hu">{{ $jenis === 'halaman' ? 'Alamat halaman' : ($useBody ? 'Tanggal' : 'Tautan / Berkas') }}</div><div></div></div>
      @forelse($items as $r)
        @php $ref = $r->berkas ?: $r->tautan; @endphp
        <div class="tree-row">
          <div class="t" style="--lv:0"><i class="bi bi-dot"></i><span>{{ $r->judul }}</span>@if($r->status === 'draft')<em class="tag">Draft</em>@endif</div>
          <div class="u">{{ $jenis === 'halaman' ? 'halaman/'.$r->slug : ($useBody ? $r->created_at->translatedFormat('d M Y') : ($ref ?: '-')) }}</div>
          <div class="act">
            <a class="ic-btn" href="{{ route('admin.konten.index', [$jenis, 'edit' => $r->id]) }}" title="Edit"><i class="bi bi-pencil"></i></a>
            <form method="post" action="{{ route('admin.konten.destroy', [$jenis, $r]) }}" onsubmit="return confirm('Hapus data ini?')">@csrf @method('DELETE')<button class="ic-btn" title="Hapus"><i class="bi bi-trash"></i></button></form>
          </div>
        </div>
      @empty
        <div class="tree-row"><div class="t" style="--lv:0">Belum ada data {{ strtolower($label) }}.</div></div>
      @endforelse
    </div>
  </div>
  <div class="{{ $formCls }}">
    <div class="card shadow-sm"><div class="card-body p-4 pn-form">
      <h5 class="fw-bold mb-3">{{ $edit ? 'Edit' : 'Tambah' }} {{ $label }}</h5>
      <form method="post" enctype="multipart/form-data" action="{{ $edit ? route('admin.konten.update', [$jenis, $edit]) : route('admin.konten.store', $jenis) }}">
        @csrf @if($edit) @method('PUT') @endif
        <label>Judul</label><input class="form-control mb-{{ $jenis === 'halaman' ? '1' : '3' }}" name="judul" maxlength="200" value="{{ old('judul', $edit->judul ?? '') }}" required>
        @if($jenis === 'halaman')<div class="small text-muted mb-3">Alamat halaman dibuat dari judul, misalnya &ldquo;Visi dan Misi&rdquo; menjadi <code>halaman/visi-dan-misi</code>. Alamat ini harus sama dengan URL di Menu Manager.</div>@endif
        @if($useBody)<label>Isi</label><div class="mb-3">@include('admin.partials.editor', ['content' => old('content', $edit->isi ?? '')])</div>@endif
        @unless($useBody)<label>Tautan (opsional)</label><input class="form-control mb-3" name="tautan" placeholder="https://..." value="{{ old('tautan', $edit->tautan ?? '') }}">@endunless
        @if($useFile)
          <label>{{ $jenis === 'slider' ? 'Gambar' : 'Berkas' }} (maks. 10 MB)</label><input type="file" class="form-control mb-1" name="berkas" data-compress accept=".{{ implode(',.', $allowExt) }}">
          @if(!empty($edit->berkas))<div class="small text-muted mb-2">Saat ini: {{ basename($edit->berkas) }}</div>@else<div class="mb-2"></div>@endif
        @endif
        <label>Status</label>
        <select class="form-select mb-3" name="status"><option value="published" @selected($status === 'published')>Terbit</option><option value="draft" @selected($status === 'draft')>Draft</option></select>
        <button class="btn btn-primary">{{ $edit ? 'Simpan Perubahan' : 'Tambah' }}</button>
        @if($edit)<a href="{{ $self }}" class="btn btn-light border">Batal</a>@endif
      </form>
    </div></div>
  </div>
</div>
@endsection
