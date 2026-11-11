@extends('layouts.public')
@section('title', 'Kontak - Pemerintah Kota Manado')

@section('content')
<main class="inner-page">
  <x-page-banner kicker="Hubungi Kami" title="Kontak" :crumbs="[['Kontak', null]]" />
  <div class="container inner-container">
    <div class="contact-grid">
      <div class="panel">
        <div class="panel-title">Alamat &amp; Kontak</div>
        <div class="panel-body contact-list">
          <div class="contact-item"><div class="icon-box"><i class="bi bi-geo-alt"></i></div><div><strong>Alamat</strong><span>{!! nl2br(e($alamat)) !!}</span></div></div>
          <div class="contact-item"><div class="icon-box"><i class="bi bi-envelope"></i></div><div><strong>Email</strong><span>{{ $email }}</span></div></div>
          @if($telepon)<div class="contact-item"><div class="icon-box"><i class="bi bi-telephone"></i></div><div><strong>Telepon</strong><span>{{ $telepon }}</span></div></div>@endif
          @if($fax)<div class="contact-item"><div class="icon-box"><i class="bi bi-printer"></i></div><div><strong>Fax</strong><span>{{ $fax }}</span></div></div>@endif
        </div>
      </div>

      <div class="panel">
        <div class="panel-title">Kirim Pesan</div>
        <div class="panel-body">
          <form class="contact-form" onsubmit="return false">
            <div class="row g-3">
              <div class="col-md-6"><label class="form-label" for="kNama">Nama</label><input id="kNama" class="form-control" placeholder="Nama lengkap"></div>
              <div class="col-md-6"><label class="form-label" for="kEmail">Email</label><input id="kEmail" type="email" class="form-control" placeholder="email@contoh.com"></div>
              <div class="col-12"><label class="form-label" for="kPesan">Pesan</label><textarea id="kPesan" class="form-control" rows="5" placeholder="Tulis pesan..."></textarea></div>
              <div class="col-12"><button type="button" class="hero-btn btn-sm-pill">Kirim Pesan</button></div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</main>
@endsection
