<?php $__currentLoopData = ['success', 'danger']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <?php if(session($type)): ?>
    <div class="alert alert-<?php echo e($type); ?> alert-dismissible fade show" role="alert"><?php echo e(session($type)); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
  <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php if($errors->any()): ?>
  <div class="alert alert-danger" role="alert">
    <?php if($errors->count() === 1): ?><?php echo e($errors->first()); ?><?php else: ?><ul class="mb-0 ps-3"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($err); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul><?php endif; ?>
  </div>
<?php endif; ?>
<?php /**PATH D:\laragon\www\diskominfo\resources\views/admin/partials/flash.blade.php ENDPATH**/ ?>