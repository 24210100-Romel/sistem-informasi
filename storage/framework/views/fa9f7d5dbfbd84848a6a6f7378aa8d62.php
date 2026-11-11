<?php $__env->startSection('title', 'Pengaturan Berita Utama'); ?>

<?php $__env->startSection('content'); ?>
<div class="card featured-card shadow-sm mb-4"><div class="card-body p-4"><div class="d-flex justify-content-between align-items-start gap-3">
  <div><span class="featured-badge"><i class="bi bi-star-fill"></i> BERITA UTAMA SAAT INI</span>
    <h3 class="fw-bold mt-3 mb-1"><?php echo e($featured->title ?? 'Belum ada Berita Utama'); ?></h3>
    <p class="text-muted mb-0"><?php echo e($featured ? ($featured->category->nama ?? 'Tanpa kategori').' · '.$featured->created_at->translatedFormat('d M Y') : 'Pilih satu berita terbit untuk tampil paling menonjol di halaman depan.'); ?></p></div>
  <?php if($featured): ?><img src="<?php echo e($featured->image_url ?: asset('assets/logo-header.png')); ?>" class="cover-preview" alt=""><?php endif; ?>
</div></div></div>
<div class="card shadow-sm"><div class="card-body p-0">
  <div class="p-4 border-bottom"><h5 class="fw-bold mb-1">Pilih Berita Utama</h5><p class="text-muted small mb-0">Hanya satu berita yang akan menjadi headline di halaman depan.</p></div>
  <form method="post" action="<?php echo e(route('admin.berita-utama.update')); ?>"><?php echo csrf_field(); ?>
    <div class="p-4">
      <select name="article_id" class="form-select form-select-lg"><option value="0">— Tidak ada Berita Utama —</option>
        <?php $__currentLoopData = $published; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($a->id); ?>" <?php if($featured && $featured->id === $a->id): echo 'selected'; endif; ?>><?php echo e($a->title); ?><?php echo e($a->is_featured ? ' ★' : ''); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
      <div class="form-text mt-2">Artikel harus berstatus Terbit agar dapat dipilih.</div>
    </div>
    <div class="p-4 border-top"><button class="btn btn-primary"><i class="bi bi-check2-circle"></i> Simpan Pengaturan</button></div>
  </form>
</div></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\diskominfo\resources\views/admin/featured.blade.php ENDPATH**/ ?>