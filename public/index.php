<?php
// 1. Cargar Autoload de Composer y Dotenv
require_once __DIR__ . '/../vendor/autoload.php';

// 2. Iniciar sesión (usando el SessionService)
\App\Services\SessionService::start();

// 3. Importar FastRoute
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

$dispatcher = simpleDispatcher(function (RouteCollector $r) {
    require __DIR__ . '/../src/routes.php';
});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Quitar la query string si existe
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

// 4. Despachar la ruta y manejar errores con ErrorController
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case \FastRoute\Dispatcher::NOT_FOUND:
        // Redirigir a la página de error 404
        http_response_code(404);
        $errorCode = 404;
        $errorMessage = "Página no encontrada.";
        $controller = new \App\Controllers\ErrorController();
        $controller->index(['errorCode' => $errorCode, 'errorMessage' => $errorMessage]);
        break;

    case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        // Redirigir a la página de error 405
        http_response_code(405);
        $errorCode = 405;
        $errorMessage = "Método no permitido.";
        $controller = new \App\Controllers\ErrorController();
        $controller->index(['errorCode' => $errorCode, 'errorMessage' => $errorMessage]);
        break;

    case \FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        if (is_array($handler)) {
            list($class, $method) = $handler;
            $controller = new $class();
            call_user_func_array([$controller, $method], [$vars]);
        } else {
            echo "Handler not defined correctly.";
        }
        break;
}