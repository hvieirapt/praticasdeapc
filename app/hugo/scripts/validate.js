// Simple client-side logic for forms and modals


// Exibe aviso simples
function showAlert(msg) {
  alert(msg);
}

// Aplica ou remove destaque visual no campo
function highlightField(field, error) {
  if (error) {
    field.classList.add('border-red-500', 'bg-red-100');
  } else {
    field.classList.remove('border-red-500', 'bg-red-100');
  }
}

function validateLogin(form) {
  let valid = true;
  const u = form.username;
  const p = form.password;

  if (!u.value.trim()) {
    highlightField(u, true);
    valid = false;
  } else if (u.value.trim().length < 3) {
    highlightField(u, true);
    showAlert('Utilizador deve ter pelo menos 3 caracteres.');
    valid = false;
  } else {
    highlightField(u, false);
  }

  if (!p.value.trim()) {
    highlightField(p, true);
    valid = false;
  } else if (p.value.trim().length < 4) {
    highlightField(p, true);
    showAlert('Palavra-passe deve ter pelo menos 4 caracteres.');
    valid = false;
  } else {
    highlightField(p, false);
  }

  if (!valid) {
    showAlert('Preencha corretamente os campos destacados.');
  }
  return valid;
}

function validateExpedicao(form) {
  let valid = true;
  const c = form.cliente;
  const m = form.morada;
  const d = form.data_entrega;

  if (!c.value.trim()) {
    highlightField(c, true);
    valid = false;
  } else {
    highlightField(c, false);
  }

  if (!m.value.trim()) {
    highlightField(m, true);
    valid = false;
  } else {
    highlightField(m, false);
  }

  const dataEnt = d.value;
  const today = new Date().toISOString().split('T')[0];
  if (!dataEnt) {
    highlightField(d, true);
    valid = false;
  } else if (dataEnt < today) {
    highlightField(d, true);
    showAlert('Data de entrega não pode ser no passado.');
    valid = false;
  } else {
    highlightField(d, false);
  }

  if (form.estado) {
    const e = form.estado;
    if (!e.value.trim()) {
      highlightField(e, true);
      valid = false;
    } else {
      highlightField(e, false);
    }
  }

  if (!valid) {
    showAlert('Preencha corretamente os campos destacados.');
  }
  return valid;
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
