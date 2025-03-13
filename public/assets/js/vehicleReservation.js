document.addEventListener('DOMContentLoaded', () => {
  // --- Selección de Parking ---
  const parkingCells = document.querySelectorAll('.parking-cell');
  const selectedParkingInput = document.getElementById('selectedParkingInput');

  // Preseleccionamos el primer parking (si existe)
  if (parkingCells.length > 0) {
    parkingCells[0].classList.add('selected');
    selectedParkingInput.value = parkingCells[0].getAttribute('data-parking-id');
  }

  parkingCells.forEach(cell => {
    cell.addEventListener('click', () => {
      parkingCells.forEach(c => c.classList.remove('selected'));
      cell.classList.add('selected');
      selectedParkingInput.value = cell.getAttribute('data-parking-id');
      checkAvailability(); // Re-check availability al cambiar de parking
    });
  });

  // --- Selección de Año y Mes ---
  const yearDisplay = document.getElementById('yearDisplay');
  const monthGrid = document.querySelector('.month-grid');
  if (!yearDisplay || !monthGrid) {
    return;
  }
  const prevYearBtn = document.getElementById('prevYearBtn');
  const nextYearBtn = document.getElementById('nextYearBtn');
  const selectedYearInput = document.getElementById('selectedYearInput');
  const selectedMonthInput = document.getElementById('selectedMonthInput');

  const now = new Date();
  const currentYear = now.getFullYear();
  const currentMonth = now.getMonth(); // 0-indexed (0 = Enero)
  let selectedYear = currentYear;

  renderYear(selectedYear);
  updatePrevYearButton();
  updateNextYearButton();
  checkAvailability(); // Comprobar disponibilidad inicial

  prevYearBtn.addEventListener('click', () => {
    if (selectedYear > currentYear) {
      selectedYear--;
      renderYear(selectedYear);
      updatePrevYearButton();
      updateNextYearButton();
      checkAvailability();
    }
  });

  nextYearBtn.addEventListener('click', () => {
    if (selectedYear < currentYear + 1) {
      selectedYear++;
      renderYear(selectedYear);
      updatePrevYearButton();
      updateNextYearButton();
      checkAvailability();
    }
  });

  function updatePrevYearButton() {
    prevYearBtn.disabled = selectedYear <= currentYear;
  }
  
  function updateNextYearButton() {
    nextYearBtn.disabled = selectedYear >= currentYear + 1;
  }

  function renderYear(year) {
    yearDisplay.textContent = year;
    monthGrid.innerHTML = '';

    const monthNames = [
      'Enero', 'Febrero', 'Marzo', 'Abril',
      'Mayo', 'Junio', 'Julio', 'Agosto',
      'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
    ];

    for (let m = 0; m < 12; m++) {
      const cell = document.createElement('div');
      cell.classList.add('month-cell');
      cell.textContent = monthNames[m];

      // Si el año es el actual y el mes es anterior al mes actual, deshabilitar
      if (year === currentYear && m < currentMonth) {
        cell.classList.add('disabled');
        cell.style.pointerEvents = 'none';
      } else {
        cell.addEventListener('click', () => {
          document.querySelectorAll('.month-cell.selected').forEach(c => c.classList.remove('selected'));
          cell.classList.add('selected');
          selectedYearInput.value = year;
          selectedMonthInput.value = String(m + 1).padStart(2, '0');
        });
      }
      monthGrid.appendChild(cell);
    }
  }

  // --- Comprobación de disponibilidad vía AJAX ---
  function checkAvailability() {
    // Mostrar spinner
    const spinner = document.getElementById('availabilitySpinner');
    spinner.style.display = 'block';

    // Preparar datos para la petición
    const formData = new FormData();
    const vehicleType = document.querySelector('input[name="type"]').value;
    const parkingId = selectedParkingInput.value;
    formData.append('type', vehicleType);
    formData.append('parking_id', parkingId);
    formData.append('year', selectedYear);

    // Realizar la petición AJAX
    fetch('/reservations/checkAvailability', {
      method: 'POST',
      body: formData
    })
      .then(response => response.json())
      .then(data => {
        spinner.style.display = 'none';
        if (data.success) {
          const availability = data.available; // Objeto con claves 1 a 12
          const monthCells = document.querySelectorAll('.month-cell');
          monthCells.forEach((cell, index) => {
            const monthNum = index + 1;
            // Si es el año actual y el mes es anterior al actual, forzamos el disabled
            if (selectedYear === currentYear && index < currentMonth) {
              cell.classList.add('disabled');
              cell.style.pointerEvents = 'none';
            } else {
              // Si la disponibilidad es falsa, deshabilitamos la celda
              if (!availability[monthNum]) {
                cell.classList.add('disabled');
                cell.style.pointerEvents = 'none';
              } else {
                cell.classList.remove('disabled');
                cell.style.pointerEvents = 'auto';
              }
            }
          });
        } else {
          alert(data.message || "Error al comprobar disponibilidad.");
        }
      })
      .catch(error => {
        spinner.style.display = 'none';
        console.error('Error:', error);
        alert("Ocurrió un error al comprobar disponibilidad.");
      });
  }
});