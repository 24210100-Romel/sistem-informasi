<?php $__currentLoopData = $by[$pid] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <?php
    $has = ! empty($by[(int) $m->id]);
    $cls = trim(($has ? 'has-sub ' : '').(\App\Support\MenuLink::branchActive($by, $m) ? 'active' : ''));
  ?>
  <li <?php if($cls): ?> class="<?php echo e($cls); ?>" <?php endif; ?>><a href="<?php echo e(\App\Support\MenuLink::href($m->url)); ?>" <?php if($has): ?> aria-haspopup="true" <?php endif; ?>><?php echo e($m->title); ?></a>
    <?php if($has): ?>
      <ul class="nav-sub"><?php echo $__env->make('partials.nav-items', ['by' => $by, 'pid' => (int) $m->id], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></ul>
    <?php endif; ?>
  </li>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH D:\laragon\www\diskominfo\resources\views/partials/nav-items.blade.php ENDPATH**/ ?>