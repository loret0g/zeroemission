<section class="account container">
  <!-- Sección de Perfil -->
  <div class="account__header">
    <div class="account__profile-info">
      <h1 class="account__name">
        <?= htmlspecialchars($userProfile['name'] . " " . $userProfile['surname']) ?>
      </h1>
    </div>
    <div class="account__actions">
      <button class="btn btn--edit" id="btn-edit-profile">Editar Perfil</button>
      <a href="/logout" class="btn btn--logout" id="logout-btn">Cerrar Sesión</a>
    </div>
  </div>

  <!-- Sección de Reservas -->
  <div class="account__reservations">
    <h2 class="account__section-title">Mis Reservas</h2>
    <?php if (!empty($reservations)): ?>
      <div class="table-container">
        <table class="account__table">
          <thead>
            <tr>
              <th>Vehículo</th>
              <th>Parking</th>
              <th>Nº de Plaza</th>
              <th>Fecha Inicio</th>
              <th>Fecha Fin</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($reservations as $reservation): ?>
              <tr>
                <td><?= htmlspecialchars($reservation['vehicle']) ?></td>
                <td><?= htmlspecialchars($reservation['parking']) ?></td>
                <td><?= htmlspecialchars($reservation['spot']) ?></td>
                <td><?= htmlspecialchars(date('d-m-Y', strtotime($reservation['start_date']))) ?></td>
                <td><?= htmlspecialchars(date('d-m-Y', strtotime($reservation['end_date']))) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <p class="no-reservations">No tienes reservas.</p>
      <div class="center-btn">
        <a href="/#services" class="btn btn--view-vehicles">Ver Vehículos</a>
      </div>
    <?php endif; ?>
  </div>
</section>

<!-- Modal para editar perfil -->
<div class="modal" id="modal-edit-profile">
  <div class="modal__content">
    <span class="modal__close" id="modal-close">&times;</span>
    <h3 class="modal__title">Editar Perfil</h3>
    <form class="profile-form" id="profile-form" method="POST" action="/account/update" novalidate>
      <div class="form-field">
        <label for="edit-name">Nombre</label>
        <input type="text" id="edit-name" name="name" value="<?= htmlspecialchars($userProfile['name']) ?>"
          autocomplete="given-name" required>
        <span class="auth__error" id="error-edit-name"></span>
      </div>
      <div class="form-field">
        <label for="edit-surname">Apellido</label>
        <input type="text" id="edit-surname" name="surname" value="<?= htmlspecialchars($userProfile['surname']) ?>"
          required>
        <span class="auth__error" id="error-edit-surname"></span>
      </div>
      <div class="form-field">
        <label for="edit-email">Email</label>
        <input type="email" id="edit-email" name="email" value="<?= htmlspecialchars($userProfile['email']) ?>"
          autocomplete="email" required>
        <span class="auth__error" id="error-edit-email"></span>
      </div>
      <div class="form-actions">
        <button type="submit" class="btn btn--primary">Guardar Cambios</button>
        <button type="button" class="btn btn--logout" id="btn-cancel-edit">Cancelar</button>
      </div>
    </form>
  </div>
</div>