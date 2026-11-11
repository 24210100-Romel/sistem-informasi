<?php
  $user = auth()->user();
  $isAdmin = $user?->isAdmin();
  $userName = $user->nama ?: $user->username;
  $jenisNow = request()->route('jenis');
  $konten = ['dokumen' => ['Dokumen', 'bi-file-earmark-text'], 'halaman' => ['Halaman', 'bi-file-richtext'], 'pengumuman' => ['Pengumuman', 'bi-megaphone'], 'slider' => ['Slider', 'bi-images'], 'sumber' => ['Sumber', 'bi-link-45deg']];
?>
<!doctype html><html lang="id"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<title><?php echo $__env->yieldContent('title', 'Dashboard'); ?> — DISKOMINFO Kota Manado</title>
<link rel="icon" href="<?php echo e(asset('favicon.ico')); ?>" sizes="any">
<link rel="apple-touch-icon" href="<?php echo e(asset('apple-touch-icon.png')); ?>">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="<?php echo e(asset('assets/css/admin.css')); ?>" rel="stylesheet">
<link href="<?php echo e(asset('assets/css/panel.css')); ?>" rel="stylesheet">
<link href="<?php echo e(asset('assets/css/admin-blue.css')); ?>?v=<?php echo e((int) @filemtime(public_path('assets/css/admin-blue.css'))); ?>" rel="stylesheet">
</head><body class="admin-body">
<div class="admin-shell">
<aside class="pn-side">
  <a class="pn-brand" href="<?php echo e(route('admin.dashboard')); ?>"><img src="<?php echo e(asset('assets/logo-header.png')); ?>" alt="Logo"><div><strong>DISKOMINFO</strong><span>KOTA MANADO</span></div></a>
  <nav>
    <?php if (isset($component)) { $__componentOriginal06d8dbc1e07f53d7457e5b8d88f9e301 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal06d8dbc1e07f53d7457e5b8d88f9e301 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.pn-link','data' => ['href' => route('admin.dashboard'),'icon' => 'bi-house-fill','active' => request()->routeIs('admin.dashboard')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('pn-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.dashboard')),'icon' => 'bi-house-fill','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.dashboard'))]); ?>Dashboard <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal06d8dbc1e07f53d7457e5b8d88f9e301)): ?>
<?php $attributes = $__attributesOriginal06d8dbc1e07f53d7457e5b8d88f9e301; ?>
<?php unset($__attributesOriginal06d8dbc1e07f53d7457e5b8d88f9e301); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal06d8dbc1e07f53d7457e5b8d88f9e301)): ?>
<?php $component = $__componentOriginal06d8dbc1e07f53d7457e5b8d88f9e301; ?>
<?php unset($__componentOriginal06d8dbc1e07f53d7457e5b8d88f9e301); ?>
<?php endif; ?>

    <div class="pn-label">Konten</div>
    <?php if (isset($component)) { $__componentOriginal06d8dbc1e07f53d7457e5b8d88f9e301 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal06d8dbc1e07f53d7457e5b8d88f9e301 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.pn-link','data' => ['href' => route('admin.artikel.index'),'icon' => 'bi-newspaper','active' => request()->routeIs('admin.artikel.*')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('pn-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.artikel.index')),'icon' => 'bi-newspaper','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.artikel.*'))]); ?>Artikel <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal06d8dbc1e07f53d7457e5b8d88f9e301)): ?>
<?php $attributes = $__attributesOriginal06d8dbc1e07f53d7457e5b8d88f9e301; ?>
<?php unset($__attributesOriginal06d8dbc1e07f53d7457e5b8d88f9e301); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal06d8dbc1e07f53d7457e5b8d88f9e301)): ?>
<?php $component = $__componentOriginal06d8dbc1e07f53d7457e5b8d88f9e301; ?>
<?php unset($__componentOriginal06d8dbc1e07f53d7457e5b8d88f9e301); ?>
<?php endif; ?>
    <?php $__currentLoopData = $konten; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => [$lbl, $ico]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <?php if (isset($component)) { $__componentOriginal06d8dbc1e07f53d7457e5b8d88f9e301 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal06d8dbc1e07f53d7457e5b8d88f9e301 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.pn-link','data' => ['href' => route('admin.konten.index', $k),'icon' => $ico,'active' => request()->routeIs('admin.konten.*') && $jenisNow === $k]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('pn-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.konten.index', $k)),'icon' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($ico),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.konten.*') && $jenisNow === $k)]); ?><?php echo e($lbl); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal06d8dbc1e07f53d7457e5b8d88f9e301)): ?>
<?php $attributes = $__attributesOriginal06d8dbc1e07f53d7457e5b8d88f9e301; ?>
<?php unset($__attributesOriginal06d8dbc1e07f53d7457e5b8d88f9e301); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal06d8dbc1e07f53d7457e5b8d88f9e301)): ?>
<?php $component = $__componentOriginal06d8dbc1e07f53d7457e5b8d88f9e301; ?>
<?php unset($__componentOriginal06d8dbc1e07f53d7457e5b8d88f9e301); ?>
<?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php if (isset($component)) { $__componentOriginal06d8dbc1e07f53d7457e5b8d88f9e301 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal06d8dbc1e07f53d7457e5b8d88f9e301 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.pn-link','data' => ['href' => route('admin.berita-utama'),'icon' => 'bi-star','active' => request()->routeIs('admin.berita-utama*')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('pn-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.berita-utama')),'icon' => 'bi-star','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.berita-utama*'))]); ?>Berita Utama <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal06d8dbc1e07f53d7457e5b8d88f9e301)): ?>
<?php $attributes = $__attributesOriginal06d8dbc1e07f53d7457e5b8d88f9e301; ?>
<?php unset($__attributesOriginal06d8dbc1e07f53d7457e5b8d88f9e301); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal06d8dbc1e07f53d7457e5b8d88f9e301)): ?>
<?php $component = $__componentOriginal06d8dbc1e07f53d7457e5b8d88f9e301; ?>
<?php unset($__componentOriginal06d8dbc1e07f53d7457e5b8d88f9e301); ?>
<?php endif; ?>

    <div class="pn-label">Referensi</div>
    <?php if (isset($component)) { $__componentOriginal06d8dbc1e07f53d7457e5b8d88f9e301 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal06d8dbc1e07f53d7457e5b8d88f9e301 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.pn-link','data' => ['href' => route('admin.kategori.index'),'icon' => 'bi-tags','active' => request()->routeIs('admin.kategori.*')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('pn-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.kategori.index')),'icon' => 'bi-tags','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.kategori.*'))]); ?>Kategori <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal06d8dbc1e07f53d7457e5b8d88f9e301)): ?>
<?php $attributes = $__attributesOriginal06d8dbc1e07f53d7457e5b8d88f9e301; ?>
<?php unset($__attributesOriginal06d8dbc1e07f53d7457e5b8d88f9e301); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal06d8dbc1e07f53d7457e5b8d88f9e301)): ?>
<?php $component = $__componentOriginal06d8dbc1e07f53d7457e5b8d88f9e301; ?>
<?php unset($__componentOriginal06d8dbc1e07f53d7457e5b8d88f9e301); ?>
<?php endif; ?>
    <div class="pn-group <?php echo e(request()->routeIs('admin.menu.*') ? 'open' : ''); ?>">
      <button type="button" class="pn-link <?php echo e(request()->routeIs('admin.menu.*') ? 'active' : ''); ?>" onclick="this.parentNode.classList.toggle('open')"><i class="bi bi-diagram-3"></i><span>Menu Manager</span><i class="bi bi-chevron-down chev"></i></button>
      <div class="pn-sub">
        <?php if (isset($component)) { $__componentOriginal06d8dbc1e07f53d7457e5b8d88f9e301 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal06d8dbc1e07f53d7457e5b8d88f9e301 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.pn-link','data' => ['href' => route('admin.menu.index', 'utama'),'icon' => 'bi-dot','active' => request()->routeIs('admin.menu.*') && $jenisNow === 'utama']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('pn-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.menu.index', 'utama')),'icon' => 'bi-dot','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.menu.*') && $jenisNow === 'utama')]); ?>Menu Utama <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal06d8dbc1e07f53d7457e5b8d88f9e301)): ?>
<?php $attributes = $__attributesOriginal06d8dbc1e07f53d7457e5b8d88f9e301; ?>
<?php unset($__attributesOriginal06d8dbc1e07f53d7457e5b8d88f9e301); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal06d8dbc1e07f53d7457e5b8d88f9e301)): ?>
<?php $component = $__componentOriginal06d8dbc1e07f53d7457e5b8d88f9e301; ?>
<?php unset($__componentOriginal06d8dbc1e07f53d7457e5b8d88f9e301); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginal06d8dbc1e07f53d7457e5b8d88f9e301 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal06d8dbc1e07f53d7457e5b8d88f9e301 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.pn-link','data' => ['href' => route('admin.menu.index', 'footer'),'icon' => 'bi-dot','active' => request()->routeIs('admin.menu.*') && $jenisNow === 'footer']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('pn-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.menu.index', 'footer')),'icon' => 'bi-dot','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.menu.*') && $jenisNow === 'footer')]); ?>Menu Footer <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal06d8dbc1e07f53d7457e5b8d88f9e301)): ?>
<?php $attributes = $__attributesOriginal06d8dbc1e07f53d7457e5b8d88f9e301; ?>
<?php unset($__attributesOriginal06d8dbc1e07f53d7457e5b8d88f9e301); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal06d8dbc1e07f53d7457e5b8d88f9e301)): ?>
<?php $component = $__componentOriginal06d8dbc1e07f53d7457e5b8d88f9e301; ?>
<?php unset($__componentOriginal06d8dbc1e07f53d7457e5b8d88f9e301); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginal06d8dbc1e07f53d7457e5b8d88f9e301 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal06d8dbc1e07f53d7457e5b8d88f9e301 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.pn-link','data' => ['href' => route('admin.menu.index', 'layanan'),'icon' => 'bi-dot','active' => request()->routeIs('admin.menu.*') && $jenisNow === 'layanan']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('pn-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.menu.index', 'layanan')),'icon' => 'bi-dot','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.menu.*') && $jenisNow === 'layanan')]); ?>Layanan Informasi <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal06d8dbc1e07f53d7457e5b8d88f9e301)): ?>
<?php $attributes = $__attributesOriginal06d8dbc1e07f53d7457e5b8d88f9e301; ?>
<?php unset($__attributesOriginal06d8dbc1e07f53d7457e5b8d88f9e301); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal06d8dbc1e07f53d7457e5b8d88f9e301)): ?>
<?php $component = $__componentOriginal06d8dbc1e07f53d7457e5b8d88f9e301; ?>
<?php unset($__componentOriginal06d8dbc1e07f53d7457e5b8d88f9e301); ?>
<?php endif; ?>
      </div>
    </div>
    <?php if($isAdmin): ?>
      <?php if (isset($component)) { $__componentOriginal06d8dbc1e07f53d7457e5b8d88f9e301 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal06d8dbc1e07f53d7457e5b8d88f9e301 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.pn-link','data' => ['href' => route('admin.pengaturan'),'icon' => 'bi-gear','active' => request()->routeIs('admin.pengaturan*')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('pn-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.pengaturan')),'icon' => 'bi-gear','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.pengaturan*'))]); ?>Pengaturan <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal06d8dbc1e07f53d7457e5b8d88f9e301)): ?>
<?php $attributes = $__attributesOriginal06d8dbc1e07f53d7457e5b8d88f9e301; ?>
<?php unset($__attributesOriginal06d8dbc1e07f53d7457e5b8d88f9e301); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal06d8dbc1e07f53d7457e5b8d88f9e301)): ?>
<?php $component = $__componentOriginal06d8dbc1e07f53d7457e5b8d88f9e301; ?>
<?php unset($__componentOriginal06d8dbc1e07f53d7457e5b8d88f9e301); ?>
<?php endif; ?>

      <div class="pn-label">Autentikasi</div>
      <?php if (isset($component)) { $__componentOriginal06d8dbc1e07f53d7457e5b8d88f9e301 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal06d8dbc1e07f53d7457e5b8d88f9e301 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.pn-link','data' => ['href' => route('admin.pengguna.index'),'icon' => 'bi-people','active' => request()->routeIs('admin.pengguna.*')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('pn-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.pengguna.index')),'icon' => 'bi-people','active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.pengguna.*'))]); ?>Pengguna <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal06d8dbc1e07f53d7457e5b8d88f9e301)): ?>
<?php $attributes = $__attributesOriginal06d8dbc1e07f53d7457e5b8d88f9e301; ?>
<?php unset($__attributesOriginal06d8dbc1e07f53d7457e5b8d88f9e301); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal06d8dbc1e07f53d7457e5b8d88f9e301)): ?>
<?php $component = $__componentOriginal06d8dbc1e07f53d7457e5b8d88f9e301; ?>
<?php unset($__componentOriginal06d8dbc1e07f53d7457e5b8d88f9e301); ?>
<?php endif; ?>
    <?php endif; ?>
  </nav>
  <div class="pn-foot">
    <?php if (isset($component)) { $__componentOriginal06d8dbc1e07f53d7457e5b8d88f9e301 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal06d8dbc1e07f53d7457e5b8d88f9e301 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.pn-link','data' => ['href' => route('home'),'icon' => 'bi-globe2','target' => '_blank']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('pn-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('home')),'icon' => 'bi-globe2','target' => '_blank']); ?>Website Utama <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal06d8dbc1e07f53d7457e5b8d88f9e301)): ?>
<?php $attributes = $__attributesOriginal06d8dbc1e07f53d7457e5b8d88f9e301; ?>
<?php unset($__attributesOriginal06d8dbc1e07f53d7457e5b8d88f9e301); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal06d8dbc1e07f53d7457e5b8d88f9e301)): ?>
<?php $component = $__componentOriginal06d8dbc1e07f53d7457e5b8d88f9e301; ?>
<?php unset($__componentOriginal06d8dbc1e07f53d7457e5b8d88f9e301); ?>
<?php endif; ?>
    <form method="post" action="<?php echo e(route('logout')); ?>"><?php echo csrf_field(); ?>
      <button class="pn-link" type="submit"><i class="bi bi-box-arrow-right"></i><span>Logout</span></button>
    </form>
  </div>
</aside>
<main class="pn-main">
<header class="pn-top">
  <div class="pn-top-left">
    <button class="pn-burger" type="button" aria-label="Menu" onclick="document.body.classList.toggle(innerWidth>900?'side-off':'side-on')"><i class="bi bi-list"></i></button>
    <h1><?php echo $__env->yieldContent('title', 'Beranda'); ?></h1>
  </div>
  <div class="profile-chip"><span class="avatar"><i class="bi bi-person-fill"></i></span><div><strong><?php echo e($userName); ?></strong><small><?php echo e($user->role); ?></small></div></div>
</header>
<div class="page-content">
<?php echo $__env->make('admin.partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->yieldContent('content'); ?>
</div></main></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo e(asset('assets/js/editor.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/upload-compress.js')); ?>"></script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body></html>
<?php /**PATH D:\laragon\www\diskominfo\resources\views/layouts/admin.blade.php ENDPATH**/ ?>