<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login Admin - DISKOMINFO Manado</title>
<link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="{{ asset('assets/css/portal.css') }}?v={{ (int) @filemtime(public_path('assets/css/portal.css')) }}" rel="stylesheet">
</head>
<body class="login-page portal">
@php
  $loginBg = null;
  foreach (['jpg','jpeg','png','webp'] as $ext) {
    if (file_exists(public_path("assets/login-bg.$ext"))) { $loginBg = asset("assets/login-bg.$ext").'?v='.filemtime(public_path("assets/login-bg.$ext")); break; }
  }
@endphp
<main class="lg-hero" @if($loginBg) style="--hero:url('{{ $loginBg }}')" @endif>
  <div class="lg-brand">
    <img src="{{ asset('assets/logo-header.png') }}" alt="Logo">
    <h2>DISKOMINFO<br><span>KOTA MANADO</span></h2>
    <h3>Akses Informasi,<br>Bangun Kota Manado<br>Lebih Maju</h3>
  </div>
  <div class="lg-card">
    <h4>Login Admin</h4><p>Masuk ke dashboard sistem</p>
    @if($errors->any())<div class="alert alert-danger py-2 small">{{ $errors->first() }}</div>@endif
    <form method="post" action="{{ route('login') }}">@csrf
      <div class="lg-in"><i class="bi bi-person"></i><input name="username" placeholder="Username" value="{{ old('username') }}" required autofocus autocomplete="username"></div>
      <div class="lg-in"><i class="bi bi-lock"></i><input id="pw" type="password" name="password" placeholder="Password" required autocomplete="current-password"><i class="bi bi-eye" style="cursor:pointer" onclick="var p=document.getElementById('pw');p.type=p.type==='password'?'text':'password'"></i></div>
      <div class="lg-row"><label><input type="checkbox" name="remember" value="1" checked> Ingat Saya</label><a href="#">Lupa Password?</a></div>
      <button class="lg-btn" type="submit">Masuk</button>
    </form>
    <small>© {{ date('Y') }} DISKOMINFO Kota Manado</small>
  </div>
</main>
</body>
</html>
