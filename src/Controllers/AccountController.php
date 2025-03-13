<?php
namespace App\Controllers;

use App\Services\SessionService;
use App\Services\ValidationService;
use App\Services\FlashService;
use App\Models\UserModel;
use App\Models\ReservationModel;

class AccountController
{
    public function index(): void
    {
        // Requiere que el usuario esté autenticado
        SessionService::requireAuthentication();
        $userId = SessionService::get('user_id');

        // Obtener datos del usuario
        $userModel = new UserModel();
        $userProfile = $userModel->getById($userId);

        // Obtener reservas del usuario
        $reservationModel = new ReservationModel();
        $reservations = $reservationModel->getReservationsByUser($userId);

        $title = "Mi Cuenta - Zero Emission";
        ob_start();
        require_once __DIR__ . '/../Views/account.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../Views/layout.php';
    }

    public function updateProfile(): void
    {
        SessionService::requireAuthentication();
        $userId = SessionService::getUserId();

        // Recoger y sanitizar datos
        $name = trim($_POST['name'] ?? '');
        $surname = trim($_POST['surname'] ?? '');
        $email = trim($_POST['email'] ?? '');

        // Validar datos usando ValidationService
        $errors = [];
        if (!ValidationService::validateRequired($name)) {
            $errors['name'] = "El nombre es obligatorio.";
        }
        if (!ValidationService::validateRequired($surname)) {
            $errors['surname'] = "El apellido es obligatorio.";
        }
        if (!ValidationService::validateEmail($email)) {
            $errors['email'] = "El email no tiene un formato válido.";
        }

        if (!empty($errors)) {
            FlashService::setFlash('error', implode("<br>", $errors));
            header('Location: /account');
            exit;
        }

        // Actualizar el usuario
        $userModel = new UserModel();
        try {
            $userModel->updateUser($userId, $name, $surname, $email);
            FlashService::setFlash('success', "Perfil actualizado correctamente");
        } catch (\Exception $e) {
            FlashService::setFlash('error', "Error al actualizar el perfil: " . $e->getMessage());
        }
        header('Location: /account');
        exit;
    }
}