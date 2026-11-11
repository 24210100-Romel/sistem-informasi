@extends('layouts.public')
@section('title', $judul.' - Pemerintah Kota Manado')

@section('content')
<main class="inner-page">
  <x-page-banner :title="$judul" :crumbs="[['Halaman', null], [$judul, null]]" />
  <div class="container inner-container">
    <div class="top-grid">
      <div class="panel">
        <div class="panel-title">{{ $row ? $judul : 'Halaman Tidak Ditemukan' }}</div>
        <div class="panel-body">
          @if($row)
            <div class="article-content">{!! $row->isi !!}</div>
          @else
            <p><strong>Halaman belum tersedia.</strong></p>
            <p>Konten untuk halaman ini belum dipublikasikan. Silakan kembali lagi nanti.</p>
            @if(count($known))
              <hr>
              <p class="small text-muted mb-2">Daftar alamat halaman yang tersedia (hanya terlihat setelah login):</p>
              <ul class="small mb-0">
                @foreach($known as $k)<li>{{ $k->judul }} — <code>halaman/{{ $k->slug }}</code></li>@endforeach
              </ul>
            @endif
          @endif
        </div>
      </div>
      @include('partials.sidebar')
    </div>
  </div>
</main>
@endsection
