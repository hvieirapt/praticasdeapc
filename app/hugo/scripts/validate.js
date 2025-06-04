// Form and modal handlers

function show(id) { document.getElementById(id).classList.remove('hidden'); }
function hide(id) { document.getElementById(id).classList.add('hidden'); }

function bindModal(openId, modalId, closeId) {
  const open = document.getElementById(openId);
  const modal = document.getElementById(modalId);
  const close = document.getElementById(closeId);
  if (open && modal && close) {
    open.addEventListener('click', () => show(modalId));
    close.addEventListener('click', () => hide(modalId));
  }
}

function validateForm(form) {
  let ok = true;
  form.querySelectorAll('input[required],select[required]').forEach(f => {
    if (!f.value.trim()) ok = false;
  });
  if (!ok) alert('Preencha todos os campos.');
  return ok;
}

document.addEventListener('DOMContentLoaded', () => {
  bindModal('openCreate', 'createModal', 'closeCreate');

  document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      document.getElementById('edit_id').value = btn.dataset.id;
      document.getElementById('edit_cliente').value = btn.dataset.cliente;
      document.getElementById('edit_morada').value = btn.dataset.morada;
      document.getElementById('edit_entrega').value = btn.dataset.entrega;
      document.getElementById('edit_estado').value = btn.dataset.estado;
      show('editModal');
    });
  });
  const closeEdit = document.getElementById('closeEdit');
  if (closeEdit) closeEdit.addEventListener('click', () => hide('editModal'));

  ['loginForm','indexLoginForm','registoForm','createForm','editForm'].forEach(id => {
    const form = document.getElementById(id);
    if (form) {
      form.addEventListener('submit', e => {
        if (!validateForm(form)) e.preventDefault();
      });
    }
  });
});
