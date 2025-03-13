<?php
namespace App\Services;

class FlashService
{
    public static function getFlash(): ?array {
        $flash = [
            'error'   => $_SESSION['error'] ?? null,
            'success' => $_SESSION['success'] ?? null
        ];
        self::clearFlash();
        return $flash;
    }

    public static function clearFlash(): void {
        unset($_SESSION['error'], $_SESSION['success']);
    }

    public static function setFlash(string $type, string $message): void {
        $_SESSION[$type] = $message;
    }
}