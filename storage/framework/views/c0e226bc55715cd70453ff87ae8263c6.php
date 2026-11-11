<?php
  $self = route('admin.konten.index', $jenis);
  $listCls = $useBody ? 'col-12 order-2' : 'col-xl-8';
  $formCls = $useBody ? 'col-12 order-1' : 'col-xl-4';
  $status = old('status', $edit->status ?? 'published');
?>

<?php $__env->startSection('title', $label); ?>

<?php $__env->startSection('content'); ?>
<div class="crumb">Konten &rsaquo; <b><?php echo e($label); ?></b></div>
<div class="row g-4">
  <div class="<?php echo e($listCls); ?>">
    <div class="tree-card">
      <div class="tree-head"><div>Judul</div><div class="hu"><?php echo e($jenis === 'halaman' ? 'Alamat halaman' : ($useBody ? 'Tanggal' : 'Tautan / Berkas')); ?></div><div></div></div>
      <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php $ref = $r->berkas ?: $r->tautan; ?>
        <div class="tree-row">
          <div class="t" style="--lv:0"><i class="bi bi-dot"></i><span><?php echo e($r->judul); ?></span><?php if($r->status === 'draft'): ?><em class="tag">Draft</em><?php endif; ?></div>
          <div class="u"><?php echo e($jenis === 'halaman' ? 'halaman/'.$r->slug : ($useBody ? $r->created_at->translatedFormat('d M Y') : ($ref ?: '-'))); ?></div>
          <div class="act">
            <a class="ic-btn" href="<?php echo e(route('admin.konten.index', [$jenis, 'edit' => $r->id])); ?>" title="Edit"><i class="bi bi-pencil"></i></a>
            <form method="post" action="<?php echo e(route('admin.konten.destroy', [$jenis, $r])); ?>" onsubmit="return confirm('Hapus data ini?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="ic-btn" title="Hapus"><i class="bi bi-trash"></i></button></form>
          </div>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="tree-row"><div class="t" style="--lv:0">Belum ada data <?php echo e(strtolower($label)); ?>.</div></div>
      <?php endif; ?>
    </div>
  </div>
  <div class="<?php echo e($formCls); ?>">
    <div class="card shadow-sm"><div class="card-body p-4 pn-form">
      <h5 class="fw-bold mb-3"><?php echo e($edit ? 'Edit' : 'Tambah'); ?> <?php echo e($label); ?></h5>
      <form method="post" enctype="multipart/form-data" action="<?php echo e($edit ? route('admin.konten.update', [$jenis, $edit]) : route('admin.konten.store', $jenis)); ?>">
        <?php echo csrf_field(); ?> <?php if($edit): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
        <label>Judul</label><input class="form-control mb-<?php echo e($jenis === 'halaman' ? '1' : '3'); ?>" name="judul" maxlength="200" value="<?php echo e(old('judul', $edit->judul ?? '')); ?>" required>
        <?php if($jenis === 'halaman'): ?><div class="small text-muted mb-3">Alamat halaman dibuat dari judul, misalnya &ldquo;Visi dan Misi&rdquo; menjadi <code>halaman/visi-dan-misi</code>. Alamat ini harus sama dengan URL di Menu Manager.</div><?php endif; ?>
        <?php if($useBody): ?><label>Isi</label><div class="mb-3"><?php echo $__env->make('admin.partials.editor', ['content' => old('content', $edit->isi ?? '')], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div><?php endif; ?>
        <?php if (! ($useBody)): ?><label>Tautan (opsional)</label><input class="form-control mb-3" name="tautan" placeholder="https://..." value="<?php echo e(old('tautan', $edit->tautan ?? '')); ?>"><?php endif; ?>
        <?php if($useFile): ?>
          <label><?php echo e($jenis === 'slider' ? 'Gambar' : 'Berkas'); ?> (maks. 10 MB)</label><input type="file" class="form-control mb-1" name="berkas" data-compress accept=".<?php echo e(implode(',.', $allowExt)); ?>">
          <?php if(!empty($edit->berkas)): ?><div class="small text-muted mb-2">Saat ini: <?php echo e(basename($edit->berkas)); ?></div><?php else: ?><div class="mb-2"></div><?php endif; ?>
        <?php endif; ?>
        <label>Status</label>
        <select class="form-select mb-3" name="status"><option value="published" <?php if($status === 'published'): echo 'selected'; endif; ?>>Terbit</option><option value="draft" <?php if($status === 'draft'): echo 'selected'; endif; ?>>Draft</option></select>
        <button class="btn btn-primary"><?php echo e($edit ? 'Simpan Perubahan' : 'Tambah'); ?></button>
        <?php if($edit): ?><a href="<?php echo e($self); ?>" class="btn btn-light border">Batal</a><?php endif; ?>
      </form>
    </div></div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\diskominfo\resources\views/admin/contents.blade.php ENDPATH**/ ?>