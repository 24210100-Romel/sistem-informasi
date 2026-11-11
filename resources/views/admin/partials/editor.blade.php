<div class="editor-wrap" data-rich-editor>
  <div class="editor-toolbar" role="toolbar" aria-label="Editor berita">
    <button type="button" data-cmd="bold" title="Tebal"><i class="bi bi-type-bold"></i></button>
    <button type="button" data-cmd="italic" title="Miring"><i class="bi bi-type-italic"></i></button>
    <button type="button" data-cmd="underline" title="Garis bawah"><i class="bi bi-type-underline"></i></button>
    <button type="button" data-cmd="strikeThrough" title="Coret"><i class="bi bi-type-strikethrough"></i></button>
    <span class="divider"></span>
    <select data-format title="Format"><option value="p">Paragraf</option><option value="h2">Heading 2</option><option value="h3">Heading 3</option><option value="h4">Heading 4</option><option value="blockquote">Kutipan</option></select>
    <button type="button" data-cmd="insertUnorderedList" title="Daftar"><i class="bi bi-list-ul"></i></button>
    <button type="button" data-cmd="insertOrderedList" title="Nomor"><i class="bi bi-list-ol"></i></button>
    <span class="divider"></span>
    <button type="button" data-cmd="justifyLeft" title="Rata kiri"><i class="bi bi-text-left"></i></button>
    <button type="button" data-cmd="justifyCenter" title="Rata tengah"><i class="bi bi-text-center"></i></button>
    <button type="button" data-cmd="justifyRight" title="Rata kanan"><i class="bi bi-text-right"></i></button>
    <button type="button" data-cmd="justifyFull" title="Rata kiri-kanan"><i class="bi bi-justify"></i></button>
    <span class="divider"></span>
    <button type="button" data-cmd="createLink" title="Tautan"><i class="bi bi-link-45deg"></i></button>
    <button type="button" data-cmd="insertImage" title="Gambar dari URL"><i class="bi bi-image"></i></button>
    <button type="button" data-cmd="insertHorizontalRule" title="Garis"><i class="bi bi-dash-lg"></i></button>
    <button type="button" data-cmd="undo" title="Urungkan"><i class="bi bi-arrow-counterclockwise"></i></button>
    <button type="button" data-cmd="redo" title="Ulangi"><i class="bi bi-arrow-clockwise"></i></button>
    <button type="button" data-cmd="removeFormat" title="Bersihkan format"><i class="bi bi-eraser"></i></button>
    <button type="button" data-source-toggle title="Lihat HTML"><i class="bi bi-code-slash"></i></button>
  </div>
  <div class="editor-area" contenteditable="true" spellcheck="true"></div>
  <textarea class="editor-source" name="content" aria-label="HTML artikel">{{ $content ?? '' }}</textarea>
</div>
