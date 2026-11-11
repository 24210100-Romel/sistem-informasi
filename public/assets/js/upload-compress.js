/* Unggah berkas tanpa bergantung pada folder tmp PHP (input[data-compress]).
   1) Foto dikecilkan di browser (maks. 1600 px, target <= ~700 KB).
   2) Berkas (<= 5 MB) dikirim sebagai teks base64 di badan form, bukan multipart,
      sehingga tetap berhasil walau upload_tmp_dir server bermasalah.
   3) Bila gagal/terlalu besar, otomatis kembali ke unggahan biasa. */
(function () {
  var MAX_SIDE = 1600, TARGET = 700 * 1024, SKIP_BELOW = 600 * 1024, MAX_B64 = 5 * 1048576;
  var QUALITIES = [0.85, 0.75, 0.65, 0.55];

  function readDataURL(file) {
    return new Promise(function (res, rej) {
      var r = new FileReader();
      r.onload = function () { res(r.result); };
      r.onerror = function () { rej(r.error); };
      r.readAsDataURL(file);
    });
  }
  function toBlob(canvas, q) {
    return new Promise(function (res) { canvas.toBlob(res, 'image/jpeg', q); });
  }

  async function shrink(file) {
    var bmp = await createImageBitmap(file, { imageOrientation: 'from-image' });
    var scale = Math.min(1, MAX_SIDE / Math.max(bmp.width, bmp.height));
    if (scale === 1 && file.size <= SKIP_BELOW) { if (bmp.close) bmp.close(); return file; }

    var canvas = document.createElement('canvas');
    canvas.width = Math.round(bmp.width * scale);
    canvas.height = Math.round(bmp.height * scale);
    var ctx = canvas.getContext('2d');
    ctx.fillStyle = '#fff';
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    ctx.drawImage(bmp, 0, 0, canvas.width, canvas.height);
    if (bmp.close) bmp.close();

    var blob = null;
    for (var i = 0; i < QUALITIES.length; i++) {
      blob = await toBlob(canvas, QUALITIES[i]);
      if (blob && blob.size <= TARGET) break;
    }
    if (!blob || blob.size >= file.size) return file;
    return new File([blob], file.name.replace(/\.[^.]+$/, '') + '.jpg', { type: 'image/jpeg', lastModified: Date.now() });
  }

  function hidden(name) {
    var h = document.createElement('input');
    h.type = 'hidden'; h.name = name;
    return h;
  }
  function mb(n) { return (n / 1048576).toFixed(1) + ' MB'; }

  function setup(input) {
    var field = input.getAttribute('name');
    if (!field) return;
    var hData = hidden(field + '_b64'), hName = hidden(field + '_b64_name');
    var note = document.createElement('div');
    note.className = 'form-text';
    input.insertAdjacentElement('afterend', note);
    input.insertAdjacentElement('afterend', hName);
    input.insertAdjacentElement('afterend', hData);
    var pending = null;

    function multipart() { // jalur cadangan: unggahan biasa
      input.setAttribute('name', field);
      hData.value = ''; hName.value = '';
    }

    async function process() {
      note.textContent = '';
      multipart();
      var file = input.files && input.files[0];
      if (!file) return;

      var out = file;
      if (/^image\/(jpeg|png|webp)$/.test(file.type) && window.createImageBitmap) {
        try { out = await shrink(file); } catch (e) { out = file; }
      }

      if (out.size > MAX_B64) { // terlalu besar untuk base64: kirim biasa
        if (out !== file) { var dt = new DataTransfer(); dt.items.add(out); input.files = dt.files; }
        return;
      }
      try {
        hData.value = await readDataURL(out);
        hName.value = out.name;
        input.removeAttribute('name'); // berkas asli tidak ikut dikirim multipart
        note.textContent = out !== file
          ? 'Foto dikecilkan: ' + mb(file.size) + ' \u2192 ' + mb(out.size) + '. Siap diunggah.'
          : 'Berkas siap diunggah (' + mb(out.size) + ').';
      } catch (e) {
        multipart();
      }
    }

    input.addEventListener('change', function () {
      pending = process().then(function () { pending = null; }, function () { pending = null; multipart(); });
    });

    if (input.form) {
      input.form.addEventListener('submit', function (e) {
        if (!pending) return;
        e.preventDefault(); // tunggu proses selesai, lalu kirim ulang
        pending.then(function () { input.form.requestSubmit(); });
      });
    }
  }

  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('input[type="file"][data-compress]').forEach(setup);
  });
})();
