<?php $__env->startSection('title', 'Pengaturan'); ?>

<?php $__env->startSection('content'); ?>
<div class="crumb">Referensi &rsaquo; <b>Pengaturan</b></div>
<div class="card shadow-sm" style="max-width:720px"><div class="card-body p-4 pn-form">
  <form method="post" action="<?php echo e(route('admin.pengaturan.update')); ?>"><?php echo csrf_field(); ?>
    <?php $__currentLoopData = $fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $lbl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <label><?php echo e($lbl); ?></label>
      <?php if($k === 'ai_aktif'): ?>
        <select class="form-select mb-1" name="ai_aktif">
          <option value="1" <?php if(old('ai_aktif', $current['ai_aktif'] ?? '1') !== '0'): echo 'selected'; endif; ?>>Aktif</option>
          <option value="0" <?php if(old('ai_aktif', $current['ai_aktif'] ?? '1') === '0'): echo 'selected'; endif; ?>>Nonaktif</option>
        </select>
        <div class="small text-muted mb-3">Mode saat ini: <b><?php echo e($aiMode); ?></b>. Kunci API diisi di berkas <code>.env</code>, bukan di sini.</div>
      <?php elseif(in_array($k, $textarea, true)): ?><textarea class="form-control mb-3" name="<?php echo e($k); ?>" rows="2"><?php echo e(old($k, $current[$k] ?? '')); ?></textarea>
      <?php else: ?><input class="form-control mb-3" name="<?php echo e($k); ?>" value="<?php echo e(old($k, $current[$k] ?? '')); ?>"><?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <button class="btn btn-primary">Simpan Pengaturan</button>
  </form>
</div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\diskominfo\resources\views/admin/settings.blade.php ENDPATH**/ ?>