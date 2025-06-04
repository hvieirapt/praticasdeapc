// Manipulação de formulários e modais

function mostrar(id) { document.getElementById(id).classList.remove('hidden'); }
function esconder(id) { document.getElementById(id).classList.add('hidden'); }

function ligarModal(abrirId, modalId, fecharId) {
  const abrir = document.getElementById(abrirId);
  const modal = document.getElementById(modalId);
  const fechar = document.getElementById(fecharId);
  if (abrir && modal && fechar) {
    abrir.addEventListener('click', () => mostrar(modalId));
    fechar.addEventListener('click', () => esconder(modalId));
  }
}

function validarFormulario(form) {
  let ok = true;
  form.querySelectorAll('input[required],select[required]').forEach(f => {
    if (!f.value.trim()) ok = false;
  });
  if (!ok) alert('Preencha todos os campos.');
  return ok;
}

document.addEventListener('DOMContentLoaded', () => {
  ligarModal('openCreate', 'createModal', 'closeCreate');

  document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      document.getElementById('edit_id').value = btn.dataset.id;
      document.getElementById('edit_cliente').value = btn.dataset.cliente;
      document.getElementById('edit_morada').value = btn.dataset.morada;
      document.getElementById('edit_entrega').value = btn.dataset.entrega;
      document.getElementById('edit_estado').value = btn.dataset.estado;
      mostrar('editModal');
    });
  });
  const fecharEdit = document.getElementById('closeEdit');
  if (fecharEdit) fecharEdit.addEventListener('click', () => esconder('editModal'));

  ['loginForm','indexLoginForm','registoForm','createForm','editForm'].forEach(id => {
    const form = document.getElementById(id);
    if (form) {
      form.addEventListener('submit', e => {
        if (!validarFormulario(form)) e.preventDefault();
      });
    }
  });
});
