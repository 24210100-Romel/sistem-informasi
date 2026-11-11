<aside class="side-stack" aria-label="Informasi tambahan">
  <div class="panel">
    <div class="panel-title">Layanan Informasi</div>
    <div class="service-list">
      <?php $__currentLoopData = $sideServices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $ic = preg_match('/^bi-[a-z0-9-]+$/', (string) $it->ikon) ? $it->ikon : 'bi-file-earmark-text'; ?>
        <a href="<?php echo e(\App\Support\MenuLink::href($it->url)); ?>"><i class="bi <?php echo e($ic); ?>"></i> <?php echo e($it->title); ?></a>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      <?php if($sideServices->isEmpty()): ?><div class="empty-state">Belum ada layanan.</div><?php endif; ?>
    </div>
  </div>

  <div class="panel">
    <div class="panel-title">Berita Terbaru</div>
    <?php $__currentLoopData = $sideRecent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <?php $au = route('berita.show', $a->slug); ?>
      <article class="mini-news">
        <a class="mini-thumb" href="<?php echo e($au); ?>" tabindex="-1" aria-hidden="true"><?php if($a->image_url): ?><img src="<?php echo e($a->image_url); ?>" alt="" loading="lazy"><?php else: ?><i class="bi bi-image"></i><?php endif; ?></a>
        <div>
          <h3><a href="<?php echo e($au); ?>"><?php echo e($a->title); ?></a></h3>
          <small><i class="bi bi-calendar3"></i> <?php echo e($a->created_at->format('d-m-Y')); ?></small>
        </div>
      </article>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php if($sideRecent->isEmpty()): ?><div class="empty-state">Belum ada berita.</div><?php endif; ?>
    <a class="panel-more" href="<?php echo e(route('berita.index')); ?>">Lihat semua berita <i class="bi bi-chevron-right"></i></a>
  </div>
</aside>
<?php /**PATH D:\laragon\www\diskominfo\resources\views/partials/sidebar.blade.php ENDPATH**/ ?>