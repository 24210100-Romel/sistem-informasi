@php
  $editing = $article->exists;
  $hasOld = session()->hasOldInput();
  $status = old('status', $article->status ?: 'published');
  $featured = $hasOld ? (bool) old('is_featured') : (bool) $article->is_featured;
  $catId = (int) old('category_id', $article->category_id);
@endphp
@extends('layouts.admin')
@section('title', $editing ? 'Edit Berita' : 'Tulis Berita')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3"><div><h3 class="fw-bold mb-1">{{ $editing ? 'Edit berita' : 'Tulis berita baru' }}</h3><p class="text-muted mb-0">{{ $editing ? 'Perbarui isi, gambar, kategori, atau status publikasi.' : 'Editor rich-text untuk membuat berita seperti Word.' }}</p></div><a href="{{ route('admin.artikel.index') }}" class="btn btn-light border"><i class="bi bi-arrow-left"></i> Kembali</a></div>
<form method="post" enctype="multipart/form-data" action="{{ $editing ? route('admin.artikel.update', $article) : route('admin.artikel.store') }}">
@csrf @if($editing) @method('PUT') @endif
<div class="row g-4"><div class="col-lg-8"><div class="card shadow-sm"><div class="card-body p-4">
<label class="form-label fw-semibold">Judul Berita</label><input name="title" data-slug-source data-slug-target="#slug" class="form-control form-control-lg mb-3" value="{{ old('title', $article->title) }}" required maxlength="200">
<label class="form-label fw-semibold">Isi Berita</label>
@include('admin.partials.editor', ['content' => old('content', $article->content)])
</div></div></div>
<div class="col-lg-4">
  <div class="card shadow-sm mb-4"><div class="card-body p-4"><h6 class="fw-bold mb-3">Publikasi</h6>
    <label class="form-label">Status</label>
    <select name="status" class="form-select mb-3"><option value="published" @selected($status === 'published')>Terbit</option><option value="draft" @selected($status === 'draft')>Draft</option></select>
    <div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="is_featured" id="featured" value="1" @checked($featured)><label class="form-check-label" for="featured">Jadikan Berita Utama</label></div>
  </div></div>
  <div class="card shadow-sm mb-4"><div class="card-body p-4"><h6 class="fw-bold mb-3">Informasi Berita</h6>
    <label class="form-label">Kategori</label>
    <select name="category_id" class="form-select mb-3"><option value="0">Tanpa kategori</option>@foreach($categories as $c)<option value="{{ $c->id }}" @selected($catId === (int) $c->id)>{{ $c->nama }}</option>@endforeach</select>
    <label class="form-label">Slug</label><input id="slug" name="slug" class="form-control" value="{{ old('slug', $article->slug) }}" maxlength="220">
    @unless($editing)<div class="form-text">Slug otomatis mengikuti judul, tetapi bisa diedit.</div>@endunless
  </div></div>
  <div class="card shadow-sm"><div class="card-body p-4"><h6 class="fw-bold mb-3">Gambar Sampul</h6>
    @if($editing && $article->image_url)<img src="{{ $article->image_url }}" class="cover-preview d-block mb-3" alt="">@endif
    <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp" class="form-control" data-compress>
    <div class="form-text">{{ $editing ? 'Upload baru akan mengganti gambar lama. ' : '' }}JPG/PNG/WEBP, maksimal 5 MB. Foto besar dikecilkan otomatis sebelum diunggah.</div>
  </div></div>
</div></div>
<div class="mt-4"><button class="btn btn-primary btn-lg"><i class="bi bi-check2-circle"></i> {{ $editing ? 'Simpan Perubahan' : 'Simpan Berita' }}</button> <a href="{{ route('admin.artikel.index') }}" class="btn btn-light border btn-lg">Batal</a></div>
</form>
@endsection
