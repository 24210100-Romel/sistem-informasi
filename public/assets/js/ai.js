(function () {
  var fab = document.getElementById('aiFab'), panel = document.getElementById('aiPanel');
  if (!fab || !panel) return;
  var body = document.getElementById('aiBody'), form = document.getElementById('aiForm'),
      input = document.getElementById('aiInput'), chips = document.getElementById('aiChips'),
      sendBtn = form.querySelector('button'), history = [], busy = false;
  var token = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';

  function open(v) {
    panel.hidden = !v; fab.setAttribute('aria-expanded', v ? 'true' : 'false');
    if (v) setTimeout(function () { input.focus(); }, 50); else fab.focus();
  }
  fab.addEventListener('click', function () { open(panel.hidden); });
  document.getElementById('aiClose').addEventListener('click', function () { open(false); });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !panel.hidden) open(false); });

  function add(text, cls) {
    var d = document.createElement('div'); d.className = 'ai-msg ' + cls; d.textContent = text;   // textContent: aman dari HTML
    body.appendChild(d); body.scrollTop = body.scrollHeight; return d;
  }
  function addSources(list) {
    if (!list || !list.length) return;
    var d = document.createElement('div'); d.className = 'ai-src'; d.append('Sumber: ');
    list.forEach(function (s) {
      if (!/^https?:\/\//i.test(s.url)) return;
      var a = document.createElement('a'); a.href = s.url; a.textContent = s.title; d.appendChild(a);
    });
    body.appendChild(d); body.scrollTop = body.scrollHeight;
  }
  function typing() {
    var d = document.createElement('div'); d.className = 'ai-msg bot ai-typing'; d.setAttribute('aria-label', 'Mengetik');
    d.innerHTML = '<i></i><i></i><i></i>'; body.appendChild(d); body.scrollTop = body.scrollHeight; return d;
  }

  function send(text) {
    text = (text || '').trim(); if (!text || busy) return;
    busy = true; sendBtn.disabled = true; if (chips) chips.remove();
    add(text, 'user'); input.value = '';
    var t = typing();
    fetch(panel.dataset.url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': token },
      body: JSON.stringify({ message: text, history: history.slice(-6) })
    }).then(function (r) {
      return r.json().catch(function () { return {}; }).then(function (j) { return { ok: r.ok, status: r.status, j: j }; });
    }).then(function (res) {
      t.remove();
      if (res.ok && res.j.answer) {
        add(res.j.answer, 'bot'); addSources(res.j.sources);
        history.push({ role: 'user', content: text }, { role: 'assistant', content: res.j.answer.slice(0, 1500) });
      } else if (res.status === 429) {
        add('Terlalu banyak pertanyaan dalam waktu singkat. Mohon tunggu sebentar lalu coba lagi.', 'bot err');
      } else {
        add((res.j && res.j.message) || 'Maaf, asisten sedang bermasalah. Silakan coba lagi atau hubungi halaman Kontak.', 'bot err');
      }
    }).catch(function () {
      t.remove(); add('Tidak dapat terhubung ke server. Periksa koneksi Anda lalu coba lagi.', 'bot err');
    }).finally(function () { busy = false; sendBtn.disabled = false; input.focus(); });
  }

  form.addEventListener('submit', function (e) { e.preventDefault(); send(input.value); });
  if (chips) chips.addEventListener('click', function (e) { if (e.target.tagName === 'BUTTON') send(e.target.textContent); });
})();
