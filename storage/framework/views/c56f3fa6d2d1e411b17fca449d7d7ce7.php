<?php $__env->startSection('title', 'Manajemen Kategori'); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-4"><div class="col-lg-5"><div class="card shadow-sm"><div class="card-body p-4">
  <h5 class="fw-bold mb-1"><?php echo e($edit ? 'Edit Kategori' : 'Tambah Kategori'); ?></h5><p class="text-muted small">Kelompokkan berita agar mudah dikelola dan ditampilkan.</p>
  <form method="post" action="<?php echo e($edit ? route('admin.kategori.update', $edit) : route('admin.kategori.store')); ?>">
    <?php echo csrf_field(); ?> <?php if($edit): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
    <label class="form-label">Nama kategori</label>
    <input class="form-control mb-3" name="nama" value="<?php echo e(old('nama', $edit->nama ?? '')); ?>" maxlength="100" required>
    <button class="btn btn-primary"><?php echo e($edit ? 'Simpan Perubahan' : 'Tambah Kategori'); ?></button>
    <?php if($edit): ?><a href="<?php echo e(route('admin.kategori.index')); ?>" class="btn btn-light border">Batal</a><?php endif; ?>
  </form>
</div></div></div>
<div class="col-lg-7"><div class="card shadow-sm"><div class="card-body p-0">
  <div class="p-4 border-bottom"><h5 class="fw-bold mb-0">Daftar Kategori</h5></div>
  <div class="table-responsive"><table class="table align-middle mb-0"><thead><tr><th class="ps-4">Kategori</th><th>Jumlah Berita</th><th class="text-end pe-4">Aksi</th></tr></thead><tbody>
  <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <tr><td class="ps-4"><strong><?php echo e($c->nama); ?></strong></td><td><?php echo e($c->articles_count); ?></td><td class="text-end pe-4">
      <a class="btn btn-sm btn-outline-primary" href="<?php echo e(route('admin.kategori.index', ['edit' => $c->id])); ?>">Edit</a>
      <form method="post" action="<?php echo e(route('admin.kategori.destroy', $c)); ?>" class="d-inline" onsubmit="return confirm('Hapus kategori ini? Berita tidak akan ikut terhapus.')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="btn btn-sm btn-outline-danger">Hapus</button></form>
    </td></tr>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <tr><td colspan="3" class="text-center text-muted py-4">Belum ada kategori.</td></tr>
  <?php endif; ?>
  </tbody></table></div>
</div></div></div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\diskominfo\resources\views/admin/categories.blade.php ENDPATH**/ ?>