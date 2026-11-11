@extends('layouts.public')
@section('title', 'Tentang Kami - Pemerintah Kota Manado')

@section('content')
<main class="inner-page">
  <x-page-banner kicker="DISKOMINFO Kota Manado" :title="$judul" :crumbs="[['Tentang Kami', null]]" />
  <div class="container inner-container">
    <div class="top-grid">
      <div class="panel">
        <div class="panel-title">Tentang Kami</div>
        <div class="panel-body">
          @if($row)
            <div class="article-content">{!! $row->isi !!}</div>
          @else
            <h2 class="article-title">DISKOMINFO Kota Manado</h2>
            <p>Portal ini menjadi media penyampaian berita, informasi publik, dan informasi layanan komunikasi dan informatika kepada masyarakat Kota Manado.</p>
            <div class="about-grid">
              <div><i class="bi bi-megaphone"></i><strong>Komunikasi Publik</strong></div>
              <div><i class="bi bi-globe2"></i><strong>Layanan Digital</strong></div>
              <div><i class="bi bi-info-circle"></i><strong>Informasi Publik</strong></div>
              <div><i class="bi bi-database"></i><strong>Pengelolaan Informasi</strong></div>
            </div>
          @endif
        </div>
      </div>
      @include('partials.sidebar')
    </div>
  </div>
</main>
@endsection
