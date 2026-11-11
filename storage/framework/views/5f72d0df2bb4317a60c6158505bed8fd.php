<link href="<?php echo e(asset('assets/css/ai.css')); ?>?v=<?php echo e((int) @filemtime(public_path('assets/css/ai.css'))); ?>" rel="stylesheet">
<button type="button" class="ai-fab" id="aiFab" aria-label="Buka asisten virtual" aria-expanded="false" aria-controls="aiPanel">
  <img src="<?php echo e(asset('assets/ai-logo.png')); ?>" alt="" width="28" height="27"><span>AI</span>
</button>
<section class="ai-panel" id="aiPanel" role="dialog" aria-label="Asisten virtual" hidden data-url="<?php echo e(route('chat')); ?>">
  <header class="ai-head">
    <div class="ai-title"><img src="<?php echo e(asset('assets/ai-logo.png')); ?>" alt="" width="30" height="29">
      <div><strong>Asisten Virtual</strong><small>Asisten AI &middot; bukan petugas</small></div>
    </div>
    <button type="button" class="ai-close" id="aiClose" aria-label="Tutup"><i class="bi bi-x-lg"></i></button>
  </header>
  <div class="ai-body" id="aiBody" aria-live="polite">
    <div class="ai-msg bot">Halo! Saya asisten virtual <?php echo e($siteName); ?>. Silakan tanya seputar berita, layanan informasi, profil, atau kontak kami.</div>
    <div class="ai-chips" id="aiChips">
      <button type="button">Alamat dan kontak</button>
      <button type="button">Layanan informasi apa saja?</button>
      <button type="button">Berita terbaru</button>
    </div>
  </div>
  <form class="ai-form" id="aiForm" autocomplete="off">
    <input type="text" id="aiInput" maxlength="500" placeholder="Tulis pertanyaan Anda…" aria-label="Pertanyaan" required>
    <button type="submit" aria-label="Kirim"><i class="bi bi-send-fill"></i></button>
  </form>
  <p class="ai-note">Jangan kirim data pribadi (NIK, nomor telepon). Jawaban dapat keliru; untuk informasi resmi gunakan halaman <a href="<?php echo e(route('kontak')); ?>">Kontak</a>.</p>
</section>
<script src="<?php echo e(asset('assets/js/ai.js')); ?>?v=<?php echo e((int) @filemtime(public_path('assets/js/ai.js'))); ?>" defer></script>
<?php /**PATH D:\laragon\www\diskominfo\resources\views/partials/ai-widget.blade.php ENDPATH**/ ?>