<?php
namespace App\Controllers;

class ErrorController {
    /**
     * Muestra una página de error personalizada.
     *
     * @param array $vars (opcional) Array que puede incluir 'errorCode' y 'errorMessage'.
     */
    public function index(array $vars = []): void {
        $errorCode = $vars['errorCode'] ?? 500;
        $errorMessage = $vars['errorMessage'] ?? "Ha ocurrido un error inesperado.";
        $title = "Error " . $errorCode;
        
        // Capturamos el contenido de la vista de error
        ob_start();
        require_once __DIR__ . '/../Views/error.php';
        $content = ob_get_clean();
        
        // Incluir el layout base
        require_once __DIR__ . '/../Views/layout.php';
    }
}