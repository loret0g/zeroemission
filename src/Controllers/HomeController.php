<?php
namespace App\Controllers;

class HomeController
{
    public function index(): void
    {
        ob_start();
        require_once __DIR__ . '/../Views/home.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../Views/layout.php';
    }

    public function about(): void
    {
        $title = "Nosotros - Zero Emission";
        ob_start();
        require_once __DIR__ . '/../Views/about.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../Views/layout.php';
    }
}