<?php $__env->startSection('title', $article->title.' - Pemerintah Kota Manado'); ?>

<?php $__env->startSection('content'); ?>
<main class="inner-page">
  <?php if (isset($component)) { $__componentOriginalb00c7f6baf7ea834a0251f4153d10b8b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb00c7f6baf7ea834a0251f4153d10b8b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.page-banner','data' => ['kicker' => 'Berita','title' => \Illuminate\Support\Str::limit($article->title, 90),'crumbs' => [['Berita', route('berita.index')], [\Illuminate\Support\Str::limit($article->title, 50), null]]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('page-banner'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['kicker' => 'Berita','title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(\Illuminate\Support\Str::limit($article->title, 90)),'crumbs' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([['Berita', route('berita.index')], [\Illuminate\Support\Str::limit($article->title, 50), null]])]); ?>
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
    <div class="top-grid">
      <article class="panel">
        <div class="panel-title">Detail Berita</div>
        <div class="panel-body">
          <h2 class="article-title"><?php echo e($article->title); ?></h2>
          <div class="article-meta">
            <span><i class="bi bi-calendar3"></i> <?php echo e($article->created_at->translatedFormat('d M Y')); ?></span>
            <?php if($article->category): ?><span><i class="bi bi-tag"></i> <?php echo e($article->category->nama); ?></span><?php endif; ?>
            <?php if($article->author): ?><span><i class="bi bi-person"></i> <?php echo e($article->author->nama); ?></span><?php endif; ?>
          </div>
          <?php if($article->image_url): ?><img class="article-hero" src="<?php echo e($article->image_url); ?>" alt="<?php echo e($article->title); ?>"><?php endif; ?>
          <div class="article-content"><?php echo $article->content; ?></div>
          <a class="back-link" href="<?php echo e(route('berita.index')); ?>"><i class="bi bi-arrow-left"></i> Kembali ke daftar berita</a>
        </div>
      </article>
      <?php echo $__env->make('partials.sidebar', ['exceptId' => $article->id], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
  </div>
</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\diskominfo\resources\views/public/article.blade.php ENDPATH**/ ?>