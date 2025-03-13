document.addEventListener('DOMContentLoaded', () => {
  setupAuthValidation();
  setupAuthModal();
});

function setupAuthValidation() {
  const registerForm = document.querySelector('.auth__form--register');
  const loginForm = document.querySelector('.auth__form--login');

  if (registerForm) {
    registerForm.addEventListener('submit', (event) => {
      event.preventDefault();
      clearFormErrors(registerForm);
      const errors = validateRegisterForm(registerForm);
      if (Object.keys(errors).length === 0) {
        registerForm.submit();
      } else {
        displayFormErrors(errors, registerForm);
      }
    });
  }

  if (loginForm) {
    loginForm.addEventListener('submit', (event) => {
      event.preventDefault();
      clearFormErrors(loginForm);
      const errors = validateLoginForm(loginForm);
      if (Object.keys(errors).length === 0) {
        loginForm.submit();
      } else {
        displayFormErrors(errors, loginForm);
      }
    });
  }
}

function validateRegisterForm(form) {
  const errors = {};
  const name = form.querySelector('input[name="name"]').value.trim();
  const surname = form.querySelector('input[name="surname"]').value.trim();
  const email = form.querySelector('input[name="email"]').value.trim();
  const password = form.querySelector('input[name="password"]').value;
  const passwordRepeat = form.querySelector('input[name="password_repeat"]').value;

  if (name === '') {
    errors.name = 'El nombre es obligatorio';
  }
  if (surname === '') {
    errors.surname = 'El apellido es obligatorio';
  }
  if (!validateEmail(email)) {
    errors.email = 'El email no es válido';
  }
  if (password.length < 8) {
    errors.password = 'La contraseña debe tener al menos 8 caracteres';
  }
  if (password !== passwordRepeat) {
    errors.password_repeat = 'Las contraseñas no coinciden';
  }
  return errors;
}

function validateLoginForm(form) {
  const errors = {};
  const email = form.querySelector('input[name="email"]').value.trim();
  const password = form.querySelector('input[name="password"]').value;

  if (email === '') {
    errors.email = 'Introduce un email';
  } else if (!validateEmail(email)) {
    errors.email = 'El email no es válido';
  }
  if (password === '') {
    errors.password = 'La contraseña no puede estar vacía';
  } else if (password.length < 8) {
    errors.password = 'La contraseña debe tener al menos 8 caracteres';
  }
  return errors;
}

function validateEmail(email) {
  const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return regex.test(email);
}

function displayFormErrors(errors, form) {
  for (const field in errors) {
    const errorElement = document.createElement('p');
    errorElement.classList.add('auth__error');
    errorElement.textContent = errors[field];
    // Insertar el mensaje justo después del input correspondiente
    const input = form.querySelector(`input[name="${field}"]`);
    if (input) {
      input.parentNode.appendChild(errorElement);
    }
  }
}

function clearFormErrors(form) {
  const errorElements = form.querySelectorAll('.auth__error');
  errorElements.forEach(el => el.remove());
}

function setupAuthModal() {
  const modal = document.getElementById('modalExitoRegistro');
  if (!modal) return;
  const closeButton = modal.querySelector('.close-button');

  // Mostrar el modal si en la URL se pasa ?success=1
  if (new URLSearchParams(window.location.search).get('success') === '1') {
    modal.style.display = 'block';
    modal.style.visibility = 'visible';
  }

  closeButton.addEventListener('click', () => {
    modal.style.display = 'none';
    modal.style.visibility = 'hidden';
  });

  window.addEventListener('click', (event) => {
    if (event.target === modal) {
      modal.style.display = 'none';
      modal.style.visibility = 'hidden';
    }
  });
}