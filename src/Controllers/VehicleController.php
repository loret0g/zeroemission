<?php
namespace App\Controllers;

use App\Models\VehicleModel;
use App\Models\ParkingModel;
use App\Services\SessionService;
use App\Services\FlashService;

class VehicleController
{
    /**
     * Muestra la interfaz de reserva para un vehículo según su tipo.
     * @param array $vars Contiene ['type' => 'valor'] de la ruta /vehicle/{type}
     */
    public function showVehicle(array $vars): void
    {
        // Recupera el tipo del vehículo (en inglés: bike, scooter, motorbike, car)
        $type = $vars['type'] ?? '';

        // Obtener los datos del vehículo
        $vehicleModel = new VehicleModel();
        $vehicleData = $vehicleModel->getVehicleByType($type);

        if (!$vehicleData) {
            $errorController = new \App\Controllers\ErrorController();
            $errorController->index([
                'errorCode'    => 404,
                'errorMessage' => "Vehículo no encontrado.",
                'title'        => "Vehículo no encontrado"
            ]);
            return;
        }

        // Obtener la lista de parkings
        $parkingModel = new ParkingModel();
        $parkings = $parkingModel->getAllParkings();

        $title = htmlspecialchars($vehicleData['spanish_name']) . " - Zero Emission";
        ob_start();
        require_once __DIR__ . '/../Views/vehicle.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../Views/layout.php';
    }
}