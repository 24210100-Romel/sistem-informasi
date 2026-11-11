<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<title><?php echo $__env->yieldContent('title', 'DISKOMINFO Kota Manado'); ?></title>
<link rel="icon" href="<?php echo e(asset('favicon.ico')); ?>" sizes="any">
<link rel="apple-touch-icon" href="<?php echo e(asset('apple-touch-icon.png')); ?>">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="<?php echo e(asset('assets/css/site.css')); ?>?v=<?php echo e((int) @filemtime(public_path('assets/css/site.css'))); ?>" rel="stylesheet">
<link href="<?php echo e(asset('assets/css/portal.css')); ?>?v=<?php echo e((int) @filemtime(public_path('assets/css/portal.css'))); ?>" rel="stylesheet">
</head>
<body class="portal <?php echo e(trim($__env->yieldContent('body_class'))); ?>">
<header class="pt-head"><div class="pt-wrap">
  <a class="pt-brand" href="<?php echo e(route('home')); ?>" aria-label="<?php echo e($siteName); ?>"><img src="<?php echo e(asset('assets/logo-header.png')); ?>" alt="Logo"><span>Dinas Komunikasi<br>dan Informatika<br>Kota Manado</span></a>
  <nav class="pt-nav" aria-label="Menu utama">
    <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="navList"><i class="bi bi-list"></i> MENU</button>
    <ul class="nav-list" id="navList">
      <?php if($navBy): ?>
        <?php echo $__env->make('partials.nav-items', ['by' => $navBy, 'pid' => 0], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
      <?php else: ?>
        <li class="<?php echo e(request()->routeIs('home') ? 'active' : ''); ?>"><a href="<?php echo e(route('home')); ?>">Beranda</a></li>
        <li class="<?php echo e(request()->routeIs('profil') ? 'active' : ''); ?>"><a href="<?php echo e(route('profil')); ?>">Profil</a></li>
        <li class="<?php echo e(request()->routeIs('tentang') ? 'active' : ''); ?>"><a href="<?php echo e(route('tentang')); ?>">Tentang Kami</a></li>
        <li class="<?php echo e(request()->routeIs('berita.*') ? 'active' : ''); ?>"><a href="<?php echo e(route('berita.index')); ?>">Berita</a></li>
        <li class="<?php echo e(request()->routeIs('kontak') ? 'active' : ''); ?>"><a href="<?php echo e(route('kontak')); ?>">Kontak</a></li>
      <?php endif; ?>
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
<?php echo $__env->yieldContent('content'); ?>
<?php echo $__env->make('partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php if($aiEnabled ?? false): ?>
<?php echo $__env->make('partials.ai-widget', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH D:\laragon\www\diskominfo\resources\views/layouts/public.blade.php ENDPATH**/ ?>