<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['title', 'crumbs' => [], 'kicker' => null]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['title', 'crumbs' => [], 'kicker' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<section class="page-hero" aria-label="<?php echo e($title); ?>">
  <?php if(!empty($bannerBg)): ?><img class="page-hero-bg" src="<?php echo e($bannerBg); ?>" alt="" aria-hidden="true" fetchpriority="high"><?php endif; ?>
  <div class="hero-shade"></div>
  <div class="page-hero-inner">
    <?php if($kicker): ?><p class="hero-kecil"><?php echo e($kicker); ?></p><?php endif; ?>
    <h1 class="page-hero-title"><?php echo e($title); ?></h1>
    <nav aria-label="Breadcrumb"><ol class="crumbs"><li><a href="<?php echo e(route('home')); ?>">Beranda</a></li>
      <?php $__currentLoopData = $crumbs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => [$label, $url]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $last = $i === array_key_last($crumbs); ?>
        <li <?php if($last): ?> aria-current="page" <?php endif; ?>><?php if($url && ! $last): ?><a href="<?php echo e($url); ?>"><?php echo e($label); ?></a><?php else: ?><?php echo e($label); ?><?php endif; ?></li>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ol></nav>
  </div>
</section>
<?php /**PATH D:\laragon\www\diskominfo\resources\views/components/page-banner.blade.php ENDPATH**/ ?>