// Simple client-side logic for forms and modals

function showAlert(msg) {
  alert(msg);
}

function validateLogin(form) {
  if (!form.username.value.trim() || !form.password.value.trim()) {
    showAlert('Preencha utilizador e palavra-passe.');
    return false;
  }
  return true;
}

function validateExpedicao(form) {
  const cliente = form.cliente.value.trim();
  const morada = form.morada.value.trim();
  const dataEnt = form.data_entrega.value;
  if (!cliente || !morada || !dataEnt) {
    showAlert('Preencha todos os campos.');
    return false;
  }
  if (form.estado && !form.estado.value.trim()) {
    showAlert('Indique o estado.');
    return false;
  }
  return true;
}

function initModals() {
  const createBtn = document.getElementById('openCreate');
  const createModal = document.getElementById('createModal');
  const closeCreate = document.getElementById('closeCreate');
  if (createBtn && createModal && closeCreate) {
    createBtn.addEventListener('click', () => createModal.classList.remove('hidden'));
    closeCreate.addEventListener('click', () => createModal.classList.add('hidden'));
  }

  const editModal = document.getElementById('editModal');
  const closeEdit = document.getElementById('closeEdit');
  if (editModal && closeEdit) {
    document.querySelectorAll('.edit-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        document.getElementById('edit_id').value = btn.dataset.id;
        document.getElementById('edit_cliente').value = btn.dataset.cliente;
        document.getElementById('edit_morada').value = btn.dataset.morada;
        document.getElementById('edit_entrega').value = btn.dataset.entrega;
        document.getElementById('edit_estado').value = btn.dataset.estado;
        editModal.classList.remove('hidden');
      });
    });
    closeEdit.addEventListener('click', () => editModal.classList.add('hidden'));
  }
}

document.addEventListener('DOMContentLoaded', () => {
  initModals();

  const loginForm = document.getElementById('loginForm');
  if (loginForm) {
    loginForm.addEventListener('submit', e => {
      if (!validateLogin(loginForm)) e.preventDefault();
    });
  }

  const indexLoginForm = document.getElementById('indexLoginForm');
  if (indexLoginForm) {
    indexLoginForm.addEventListener('submit', e => {
      if (!validateLogin(indexLoginForm)) e.preventDefault();
    });
  }

  const registoForm = document.getElementById('registoForm');
  if (registoForm) {
    registoForm.addEventListener('submit', e => {
      if (!validateLogin(registoForm)) e.preventDefault();
    });
  }

  const createForm = document.getElementById('createForm');
  if (createForm) {
    createForm.addEventListener('submit', e => {
      if (!validateExpedicao(createForm)) e.preventDefault();
    });
  }

  const editForm = document.getElementById('editForm');
  if (editForm) {
    editForm.addEventListener('submit', e => {
      if (!validateExpedicao(editForm)) e.preventDefault();
    });
  }
});
