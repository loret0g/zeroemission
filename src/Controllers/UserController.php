<?php
namespace App\Controllers;

use App\Models\UserModel;
use App\Services\ValidationService;
use App\Services\SessionService;
use App\Services\FlashService;

class UserController
{
    /**
     * Muestra el formulario de autenticación (registro y login) usando el layout.
     */
    public function showForm(): void
    {
        SessionService::start();
        $errors = SessionService::get('errors', []);
        SessionService::delete('errors');
        $success = SessionService::get('success', null);
        SessionService::delete('success');

        ob_start();
        require_once __DIR__ . '/../Views/auth.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../Views/layout.php';
    }

    /**
     * Procesa el registro de un nuevo usuario.
     */
    public function register(): void
    {
        SessionService::start();

        $name = trim($_POST['name'] ?? '');
        $surname = trim($_POST['surname'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordRepeat = $_POST['password_repeat'] ?? '';

        $errors = [];
        if (!ValidationService::validateRequired($name)) {
            $errors['name'] = "El nombre es obligatorio.";
        }
        if (!ValidationService::validateRequired($surname)) {
            $errors['surname'] = "El apellido es obligatorio.";
        }
        if (!ValidationService::validateEmail($email)) {
            $errors['email'] = "El formato del email no es válido.";
        }
        if (!ValidationService::validatePassword($password)) {
            $errors['password'] = "La contraseña debe tener al menos 8 caracteres.";
        }
        if (!ValidationService::validateMatch($password, $passwordRepeat)) {
            $errors['password_repeat'] = "Las contraseñas no coinciden.";
        }

        $userModel = new UserModel();
        if ($userModel->emailExists($email)) {
            $errors['email'] = "El email ya está registrado.";
        }

        if (!empty($errors)) {
            FlashService::setFlash('error', implode("<br>", $errors));
            header('Location: /auth');
            exit;
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        try {
            $userModel->createUser($name, $surname, $email, $passwordHash);
        } catch (\Exception $e) {
            FlashService::setFlash('error', "Error al crear el usuario. Inténtalo de nuevo");
            header('Location: /auth');
            exit;
        }

        FlashService::setFlash('success', "Usuario creado con éxito");
        header('Location: /auth');
        exit;
    }

    /**
     * Procesa el inicio de sesión.
     */
    public function login(): void
    {
        SessionService::start();

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $errors = [];
        if (!ValidationService::validateRequired($email) || !ValidationService::validateRequired($password)) {
            $errors['login'] = "Por favor, completa todos los campos";
        }

        if (!empty($errors)) {
            FlashService::setFlash('error', implode("<br>", $errors));
            header('Location: /auth');
            exit;
        }

        $userModel = new UserModel();
        $user = $userModel->getByEmail($email);
        if (!$user) {
            FlashService::setFlash('error', "El usuario no existe");
            header('Location: /auth');
            exit;
        }

        if (!password_verify($password, $user['password'])) {
            FlashService::setFlash('error', "Contraseña incorrecta");
            header('Location: /auth');
            exit;
        }

        SessionService::set('user_id', $user['id']);
        SessionService::set('user_name', $user['name']);
        header('Location: /account');
        exit;
    }

    /**
     * Cierra la sesión.
     */
    public function logout(): void
    {
        SessionService::start();
        SessionService::destroy();
        header('Location: /');
        exit;
    }
}