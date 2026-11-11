@extends('layouts.public')
@section('title', 'Profil Instansi - Pemerintah Kota Manado')

@section('content')
<main class="inner-page">
  <x-page-banner kicker="DISKOMINFO Kota Manado" :title="$judul" :crumbs="[['Profil Instansi', null]]" />
  <div class="container inner-container">
    <div class="top-grid">
      <div class="panel">
        <div class="panel-title">Profil Instansi</div>
        <div class="panel-body">
          @if($row)
            <div class="article-content">{!! $row->isi !!}</div>
          @else
            <h2 class="article-title">Dinas Komunikasi dan Informatika Kota Manado</h2>
            <p>Halaman ini memuat profil, tugas, fungsi, dan informasi kelembagaan DISKOMINFO Kota Manado.</p>
          @endif
        </div>
      </div>
      @include('partials.sidebar')
    </div>
  </div>
</main>
@endsection
