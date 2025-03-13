document.addEventListener('DOMContentLoaded', () => {
  const btnEditProfile = document.getElementById('btn-edit-profile');
  if(!btnEditProfile) {
    return;
  }
  const modal = document.getElementById('modal-edit-profile');
  const btnClose = document.getElementById('modal-close');
  const btnCancel = document.getElementById('btn-cancel-edit');
  const profileForm = document.getElementById('profile-form');

  // Abrir modal al pulsar "Editar Perfil"
  btnEditProfile.addEventListener('click', () => {
    modal.style.display = 'flex';
  });

  // Función para cerrar el modal y resetear el formulario
  function closeModal() {
    // Resetear el formulario a los valores iniciales
    profileForm.reset();
    // Limpiar mensajes de error
    document.querySelectorAll('.auth__error').forEach(el => el.textContent = '');
    modal.style.display = 'none';
  }

  // Cerrar modal al pulsar "Cerrar" o "Cancelar"
  btnClose.addEventListener('click', closeModal);
  btnCancel.addEventListener('click', closeModal);

  // Cerrar modal si se hace clic fuera de él
  window.addEventListener('click', (event) => {
    if (event.target === modal) {
      closeModal();
    }
  });

  // Validación del formulario de edición
  profileForm.addEventListener('submit', function(e) {
    // Limpiar mensajes de error previos
    document.getElementById('error-edit-name').textContent = '';
    document.getElementById('error-edit-surname').textContent = '';
    document.getElementById('error-edit-email').textContent = '';

    let valid = true;

    const nameInput = document.getElementById('edit-name');
    const surnameInput = document.getElementById('edit-surname');
    const emailInput = document.getElementById('edit-email');

    const nameValue = nameInput.value.trim();
    const surnameValue = surnameInput.value.trim();
    const emailValue = emailInput.value.trim();

    if (nameValue === '') {
      document.getElementById('error-edit-name').textContent = 'El nombre es obligatorio.';
      valid = false;
    }
    if (surnameValue === '') {
      document.getElementById('error-edit-surname').textContent = 'El apellido es obligatorio.';
      valid = false;
    }
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(emailValue)) {
      document.getElementById('error-edit-email').textContent = 'El formato del email no es válido.';
      valid = false;
    }

    if (!valid) {
      e.preventDefault(); // Evitar el envío si hay errores
    }
  });
});