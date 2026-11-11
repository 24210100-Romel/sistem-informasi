document.addEventListener('DOMContentLoaded',()=>{
  document.querySelectorAll('[data-rich-editor]').forEach(initEditor);
  document.querySelectorAll('[data-slug-source]').forEach(input=>{
    const target=document.querySelector(input.dataset.slugTarget); if(!target) return;
    input.addEventListener('input',()=>{ if(!target.dataset.manual) target.value=input.value.toLowerCase().trim().normalize('NFD').replace(/[\u0300-\u036f]/g,'').replace(/[^a-z0-9]+/g,'-').replace(/^-+|-+$/g,''); });
    target.addEventListener('input',()=>target.dataset.manual='1');
  });
});
function initEditor(root){
  const area=root.querySelector('.editor-area'), source=root.querySelector('.editor-source'), hidden=root.querySelector('textarea[name="content"]');
  if(!area||!hidden) return;
  area.innerHTML=hidden.value||'';
  root.querySelectorAll('[data-cmd]').forEach(btn=>btn.addEventListener('click',()=>{
    area.focus(); const cmd=btn.dataset.cmd; let value=btn.dataset.value||null;
    if(cmd==='createLink'){value=prompt('Masukkan URL tautan:','https://'); if(!value)return;}
    if(cmd==='insertImage'){value=prompt('Masukkan URL gambar:'); if(value)document.execCommand('insertImage',false,value); return;}
    document.execCommand(cmd,false,value); sync();
  }));
  const format=root.querySelector('[data-format]'); if(format) format.addEventListener('change',e=>{area.focus();document.execCommand('formatBlock',false,e.target.value);sync();});
  const toggle=root.querySelector('[data-source-toggle]'); if(toggle) toggle.addEventListener('click',()=>{if(source.style.display==='block'){area.innerHTML=source.value;area.style.display='block';source.style.display='none';toggle.classList.remove('active');}else{source.value=area.innerHTML;area.style.display='none';source.style.display='block';toggle.classList.add('active');}sync();});
  area.addEventListener('input',sync); if(source)source.addEventListener('input',()=>hidden.value=source.value);
  const form=root.closest('form'); if(form)form.addEventListener('submit',()=>{if(source&&source.style.display==='block')hidden.value=source.value;else sync();});
  function sync(){hidden.value=area.innerHTML;}
}
