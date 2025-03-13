<?php
namespace App\Controllers;

use App\Models\ReservationModel;
use App\Services\SessionService;
use App\Services\FlashService;
use App\Services\ValidationService;

class ReservationController
{
    /**
     * Método AJAX para comprobar la disponibilidad mensual para un vehículo y parking.
     * Retorna un JSON con un array 'available' mapeando meses (1 a 12) a boolean.
     */
    public function checkAvailability(): void
    {
        // Recoger y limpiar los parámetros
        $type = trim($_POST['type'] ?? '');
        $parkingId = trim($_POST['parking_id'] ?? '');
        $year = trim($_POST['year'] ?? '');

        // Validación usando ValidationService
        $errors = [];
        if (!ValidationService::validateRequired($type)) {
            $errors[] = "El tipo de vehículo es obligatorio.";
        }
        if (!ValidationService::validateRequired($parkingId)) {
            $errors[] = "El parking es obligatorio.";
        }
        if (!ValidationService::validateRequired($year)) {
            $errors[] = "El año es obligatorio.";
        }

        if (!empty($errors)) {
            echo json_encode([
                'success' => false,
                'message' => implode(" ", $errors)
            ]);
            return;
        }

        // Convertir parkingId y year a enteros si es necesario
        $parkingId = (int) $parkingId;
        $year = (int) $year;

        // Obtener la disponibilidad mensual
        $reservationModel = new ReservationModel();
        $availability = $reservationModel->checkMonthlyAvailability($type, $parkingId, $year);

        echo json_encode([
            'success' => true,
            'available' => $availability
        ]);
    }


    /**
     * Procesa la creación de una reserva.
     * Se esperan por POST: type, selected_year, selected_month y selected_parking.
     */
    public function store(array $vars): void
    {
        SessionService::requireAuthentication();
        $userId = SessionService::getUserId();

        $type = trim($_POST['type'] ?? '');
        $selectedYear = trim($_POST['selected_year'] ?? '');
        $selectedMonth = trim($_POST['selected_month'] ?? '');
        $selectedParking = trim($_POST['selected_parking'] ?? '');

        if (empty($type) || empty($selectedYear) || empty($selectedMonth) || empty($selectedParking)) {
            FlashService::setFlash('error', 'Faltan datos para realizar la reserva');
            header("Location: /vehicle/" . urlencode($type));
            exit;
        }

        $startDate = sprintf("%s-%s-01", $selectedYear, $selectedMonth);
        $endDate = date("Y-m-t", strtotime($startDate));

        $reservationModel = new ReservationModel();

        try {
            $reservationModel->createReservation($userId, $type, (int) $selectedParking, $startDate, $endDate);
            FlashService::setFlash('success', 'Reserva creada con éxito');
            header("Location: /account");
            exit;
        } catch (\Exception $e) {
            FlashService::setFlash('error', 'Error al crear la reserva: ' . $e->getMessage());
            header("Location: /vehicle/" . urlencode($type));
            exit;
        }
    }
}