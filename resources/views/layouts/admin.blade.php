@php
  $user = auth()->user();
  $isAdmin = $user?->isAdmin();
  $userName = $user->nama ?: $user->username;
  $jenisNow = request()->route('jenis');
  $konten = ['dokumen' => ['Dokumen', 'bi-file-earmark-text'], 'halaman' => ['Halaman', 'bi-file-richtext'], 'pengumuman' => ['Pengumuman', 'bi-megaphone'], 'slider' => ['Slider', 'bi-images'], 'sumber' => ['Sumber', 'bi-link-45deg']];
@endphp
<!doctype html><html lang="id"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Dashboard') — DISKOMINFO Kota Manado</title>
<link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
<link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="{{ asset('assets/css/admin.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/panel.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/admin-blue.css') }}?v={{ (int) @filemtime(public_path('assets/css/admin-blue.css')) }}" rel="stylesheet">
</head><body class="admin-body">
<div class="admin-shell">
<aside class="pn-side">
  <a class="pn-brand" href="{{ route('admin.dashboard') }}"><img src="{{ asset('assets/logo-header.png') }}" alt="Logo"><div><strong>DISKOMINFO</strong><span>KOTA MANADO</span></div></a>
  <nav>
    <x-pn-link :href="route('admin.dashboard')" icon="bi-house-fill" :active="request()->routeIs('admin.dashboard')">Dashboard</x-pn-link>

    <div class="pn-label">Konten</div>
    <x-pn-link :href="route('admin.artikel.index')" icon="bi-newspaper" :active="request()->routeIs('admin.artikel.*')">Artikel</x-pn-link>
    @foreach($konten as $k => [$lbl, $ico])
      <x-pn-link :href="route('admin.konten.index', $k)" :icon="$ico" :active="request()->routeIs('admin.konten.*') && $jenisNow === $k">{{ $lbl }}</x-pn-link>
    @endforeach
    <x-pn-link :href="route('admin.berita-utama')" icon="bi-star" :active="request()->routeIs('admin.berita-utama*')">Berita Utama</x-pn-link>

    <div class="pn-label">Referensi</div>
    <x-pn-link :href="route('admin.kategori.index')" icon="bi-tags" :active="request()->routeIs('admin.kategori.*')">Kategori</x-pn-link>
    <div class="pn-group {{ request()->routeIs('admin.menu.*') ? 'open' : '' }}">
      <button type="button" class="pn-link {{ request()->routeIs('admin.menu.*') ? 'active' : '' }}" onclick="this.parentNode.classList.toggle('open')"><i class="bi bi-diagram-3"></i><span>Menu Manager</span><i class="bi bi-chevron-down chev"></i></button>
      <div class="pn-sub">
        <x-pn-link :href="route('admin.menu.index', 'utama')" icon="bi-dot" :active="request()->routeIs('admin.menu.*') && $jenisNow === 'utama'">Menu Utama</x-pn-link>
        <x-pn-link :href="route('admin.menu.index', 'footer')" icon="bi-dot" :active="request()->routeIs('admin.menu.*') && $jenisNow === 'footer'">Menu Footer</x-pn-link>
        <x-pn-link :href="route('admin.menu.index', 'layanan')" icon="bi-dot" :active="request()->routeIs('admin.menu.*') && $jenisNow === 'layanan'">Layanan Informasi</x-pn-link>
      </div>
    </div>
    @if($isAdmin)
      <x-pn-link :href="route('admin.pengaturan')" icon="bi-gear" :active="request()->routeIs('admin.pengaturan*')">Pengaturan</x-pn-link>

      <div class="pn-label">Autentikasi</div>
      <x-pn-link :href="route('admin.pengguna.index')" icon="bi-people" :active="request()->routeIs('admin.pengguna.*')">Pengguna</x-pn-link>
    @endif
  </nav>
  <div class="pn-foot">
    <x-pn-link :href="route('home')" icon="bi-globe2" target="_blank">Website Utama</x-pn-link>
    <form method="post" action="{{ route('logout') }}">@csrf
      <button class="pn-link" type="submit"><i class="bi bi-box-arrow-right"></i><span>Logout</span></button>
    </form>
  </div>
</aside>
<main class="pn-main">
<header class="pn-top">
  <div class="pn-top-left">
    <button class="pn-burger" type="button" aria-label="Menu" onclick="document.body.classList.toggle(innerWidth>900?'side-off':'side-on')"><i class="bi bi-list"></i></button>
    <h1>@yield('title', 'Beranda')</h1>
  </div>
  <div class="profile-chip"><span class="avatar"><i class="bi bi-person-fill"></i></span><div><strong>{{ $userName }}</strong><small>{{ $user->role }}</small></div></div>
</header>
<div class="page-content">
@include('admin.partials.flash')
@yield('content')
</div></main></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets/js/editor.js') }}"></script>
<script src="{{ asset('assets/js/upload-compress.js') }}"></script>
@stack('scripts')
</body></html>
