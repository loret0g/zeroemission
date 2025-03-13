<!-- src/Views/vehicle.php -->
<section class="container vehicle-reservation">
  <div class="vehicle-reservation__info">
    <img src="<?= htmlspecialchars($vehicleData['image']) ?>"
         alt="<?= htmlspecialchars($vehicleData['spanish_name']) ?>"
         class="vehicle-reservation__image">
    <h3 class="vehicle-reservation__name"><?= htmlspecialchars($vehicleData['spanish_name']) ?></h3>
    <p class="vehicle-reservation__description"><?= htmlspecialchars($vehicleData['description']) ?></p>
  </div>
  <div class="vehicle-reservation__selector">
    <h2 class="vehicle-reservation__title">Elige un parking y un mes para tu reserva</h2>
    
    <!-- Cuadrícula de parkings -->
    <div class="parking-grid">
      <?php foreach ($parkings as $parking): ?>
        <div class="parking-cell" data-parking-id="<?= htmlspecialchars($parking['id']) ?>">
          <?= htmlspecialchars($parking['name']) ?>
        </div>
      <?php endforeach; ?>
    </div>
    
    <!-- Controles de año -->
    <div class="year-controls">
      <button id="prevYearBtn">&laquo;</button>
      <span id="yearDisplay"></span>
      <button id="nextYearBtn">&raquo;</button>
    </div>
    
    <!-- Cuadrícula de meses (se rellena con JS) -->
    <div class="month-grid">
      <!-- Aquí se inyectan las celdas de cada mes -->
    </div>
    
    <!-- Spinner para "Comprobando disponibilidad" (oculto por defecto) -->
    <div id="availabilitySpinner" class="spinner" style="display:none;">Comprobando disponibilidad…</div>
    
    <!-- Formulario de reserva (si está autenticado) -->
    <?php if (isset($_SESSION['user_id'])): ?>
      <form action="/reservations/store" method="POST" id="reserveForm" class="auth__actions">
        <input type="hidden" name="type" value="<?= htmlspecialchars($vehicleData['type']) ?>">
        <!-- Guardar la elección de año, mes y parking -->
        <input type="hidden" name="selected_year" id="selectedYearInput">
        <input type="hidden" name="selected_month" id="selectedMonthInput">
        <input type="hidden" name="selected_parking" id="selectedParkingInput">
        <button type="submit" class="btn">Reservar</button>
      </form>
    <?php else: ?>
      <div class="auth__actions">
        <a href="/auth" class="btn">Inicia sesión para reservar</a>
      </div>
    <?php endif; ?>
  </div>
</section>