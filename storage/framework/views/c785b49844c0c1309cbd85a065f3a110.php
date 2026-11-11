<?php $__env->startSection('title', 'Berita - Pemerintah Kota Manado'); ?>

<?php $__env->startSection('content'); ?>
<main class="inner-page">
  <?php if (isset($component)) { $__componentOriginalb00c7f6baf7ea834a0251f4153d10b8b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb00c7f6baf7ea834a0251f4153d10b8b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.page-banner','data' => ['kicker' => 'Informasi Terkini','title' => 'Berita','crumbs' => [['Berita', null]]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('page-banner'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['kicker' => 'Informasi Terkini','title' => 'Berita','crumbs' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([['Berita', null]])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb00c7f6baf7ea834a0251f4153d10b8b)): ?>
<?php $attributes = $__attributesOriginalb00c7f6baf7ea834a0251f4153d10b8b; ?>
<?php unset($__attributesOriginalb00c7f6baf7ea834a0251f4153d10b8b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb00c7f6baf7ea834a0251f4153d10b8b)): ?>
<?php $component = $__componentOriginalb00c7f6baf7ea834a0251f4153d10b8b; ?>
<?php unset($__componentOriginalb00c7f6baf7ea834a0251f4153d10b8b); ?>
<?php endif; ?>
  <div class="container inner-container">
    <div class="panel">
      <div class="panel-title">Semua Berita</div>
      <div class="panel-body">
        <div class="news-grid">
          <?php $__empty_1 = true; $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php $u = route('berita.show', $a->slug); ?>
            <article class="news-card">
              <a class="news-image" href="<?php echo e($u); ?>" tabindex="-1" aria-hidden="true"><?php if($a->image_url): ?><img src="<?php echo e($a->image_url); ?>" alt="" loading="lazy"><?php else: ?><i class="bi bi-newspaper"></i><?php endif; ?></a>
              <div class="news-body">
                <span class="badge-soft"><?php echo e($a->category->nama ?? 'Informasi'); ?></span>
                <h2 class="news-h"><a href="<?php echo e($u); ?>"><?php echo e($a->title); ?></a></h2>
                <p class="news-p"><?php echo e($a->excerpt(120)); ?></p>
                <small class="news-date"><i class="bi bi-calendar3"></i> <?php echo e($a->created_at->format('d-m-Y')); ?></small>
              </div>
            </article>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="empty-state grid-full">Belum ada berita.</div>
          <?php endif; ?>
        </div>
        <?php if($articles->hasPages()): ?><div class="pager"><?php echo e($articles->links()); ?></div><?php endif; ?>
      </div>
    </div>
  </div>
</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\diskominfo\resources\views/public/articles.blade.php ENDPATH**/ ?>