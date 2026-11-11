<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'DISKOMINFO Kota Manado')</title>
<link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
<link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="{{ asset('assets/css/site.css') }}?v={{ (int) @filemtime(public_path('assets/css/site.css')) }}" rel="stylesheet">
<link href="{{ asset('assets/css/portal.css') }}?v={{ (int) @filemtime(public_path('assets/css/portal.css')) }}" rel="stylesheet">
</head>
<body class="portal {{ trim($__env->yieldContent('body_class')) }}">
<header class="pt-head"><div class="pt-wrap">
  <a class="pt-brand" href="{{ route('home') }}" aria-label="{{ $siteName }}"><img src="{{ asset('assets/logo-header.png') }}" alt="Logo"><span>Dinas Komunikasi<br>dan Informatika<br>Kota Manado</span></a>
  <nav class="pt-nav" aria-label="Menu utama">
    <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="navList"><i class="bi bi-list"></i> MENU</button>
    <ul class="nav-list" id="navList">
      @if($navBy)
        @include('partials.nav-items', ['by' => $navBy, 'pid' => 0])
      @else
        <li class="{{ request()->routeIs('home') ? 'active' : '' }}"><a href="{{ route('home') }}">Beranda</a></li>
        <li class="{{ request()->routeIs('profil') ? 'active' : '' }}"><a href="{{ route('profil') }}">Profil</a></li>
        <li class="{{ request()->routeIs('tentang') ? 'active' : '' }}"><a href="{{ route('tentang') }}">Tentang Kami</a></li>
        <li class="{{ request()->routeIs('berita.*') ? 'active' : '' }}"><a href="{{ route('berita.index') }}">Berita</a></li>
        <li class="{{ request()->routeIs('kontak') ? 'active' : '' }}"><a href="{{ route('kontak') }}">Kontak</a></li>
      @endif
    </ul>
  </nav>
</div></header>
<script>
(function(){
  var n=document.querySelector('.pt-nav'); if(!n) return;
  var b=n.querySelector('.nav-toggle');
  b.addEventListener('click',function(){ n.classList.toggle('open'); b.setAttribute('aria-expanded',n.classList.contains('open')); });
  n.querySelectorAll('.has-sub > a').forEach(function(a){ a.addEventListener('click',function(e){
    var touch=innerWidth<=900||!matchMedia('(hover:hover)').matches;
    if(touch||a.getAttribute('href')==='#'){ e.preventDefault(); a.parentNode.classList.toggle('open'); }
  }); });
})();
</script>
@yield('content')
@include('partials.footer')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@if($aiEnabled ?? false)
@include('partials.ai-widget')
@endif
@stack('scripts')
</body>
</html>
