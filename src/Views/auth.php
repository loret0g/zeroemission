<section class="auth container grid">
  <!-- Formulario de registro -->
  <form class="auth__form auth__form--register" action="/user/register" method="POST" novalidate>
    <fieldset>
      <legend class="auth__legend">Registro</legend>

      <div class="auth__fields">
        <!-- Nombre -->
        <div class="auth__field">
          <label for="name">Nombre</label>
          <input id="name" name="name" type="text" class="auth__input" required>
          <?php if (!empty($errors['name'])): ?>
            <p class="auth__error"><?= htmlspecialchars($errors['name']) ?></p>
          <?php endif; ?>
        </div>

        <!-- Apellido -->
        <div class="auth__field">
          <label for="surname">Apellido</label>
          <input id="surname" name="surname" type="text" class="auth__input" required>
          <?php if (!empty($errors['surname'])): ?>
            <p class="auth__error"><?= htmlspecialchars($errors['surname']) ?></p>
          <?php endif; ?>
        </div>

        <!-- Email -->
        <div class="auth__field auth__field--full">
          <label for="email">Email</label>
          <input id="email" name="email" type="email" class="auth__input" required>
          <?php if (!empty($errors['email'])): ?>
            <p class="auth__error"><?= htmlspecialchars($errors['email']) ?></p>
          <?php endif; ?>
        </div>

        <!-- Contraseña -->
        <div class="auth__field">
          <label for="password">Contraseña</label>
          <input id="password" name="password" type="password" class="auth__input" required>
          <?php if (!empty($errors['password'])): ?>
            <p class="auth__error"><?= htmlspecialchars($errors['password']) ?></p>
          <?php endif; ?>
        </div>

        <!-- Repetir Contraseña -->
        <div class="auth__field">
          <label for="password_repeat">Repite Contraseña</label>
          <input id="password_repeat" name="password_repeat" type="password" class="auth__input" required>
          <?php if (!empty($errors['password_repeat'])): ?>
            <p class="auth__error"><?= htmlspecialchars($errors['password_repeat']) ?></p>
          <?php endif; ?>
        </div>
      </div><!-- .auth__fields -->

      <div class="auth__actions">
        <button type="submit" class="btn">Regístrate</button>
      </div>
    </fieldset>
  </form>

  <!-- Formulario login -->
  <form class="auth__form auth__form--login" action="/user/login" method="POST" novalidate>
    <fieldset>
      <legend class="auth__legend">Iniciar Sesión</legend>

      <div class="auth__field">
        <label for="email_login">Email</label>
        <input id="email_login" name="email" type="email" class="auth__input" required>
        <?php if (!empty($errors['login_email'])): ?>
          <p class="auth__error"><?= htmlspecialchars($errors['login_email']) ?></p>
        <?php endif; ?>
      </div>

      <div class="auth__field">
        <label for="pass_login">Contraseña</label>
        <input id="pass_login" name="password" type="password" class="auth__input" required>
        <?php if (!empty($errors['login_pass'])): ?>
          <p class="auth__error"><?= htmlspecialchars($errors['login_pass']) ?></p>
        <?php endif; ?>
      </div>

      <!-- Posible error genérico de login -->
      <?php if (!empty($errors['login'])): ?>
        <p class="auth__error"><?= htmlspecialchars($errors['login']) ?></p>
      <?php endif; ?>

      <div class="auth__actions">
        <button type="submit" class="btn">Iniciar Sesión</button>
      </div>
    </fieldset>
  </form>
</section>