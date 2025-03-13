<?php

// Páginas públicas y estáticas
$r->addRoute('GET', '/', ['App\Controllers\HomeController', 'index']);
$r->addRoute('GET', '/about', ['App\Controllers\HomeController', 'about']);

// Rutas de vehículos
$r->addRoute('GET', '/vehicle/{type}', ['App\Controllers\VehicleController', 'showVehicle']);

// Rutas de autenticación y cuenta de usuario
$r->addRoute('GET', '/auth', ['App\Controllers\UserController', 'showForm']);
$r->addRoute('GET', '/account', ['App\Controllers\AccountController', 'index']);
$r->addRoute('GET', '/logout', ['App\Controllers\UserController', 'logout']);

// Rutas POST para autenticación
$r->addRoute('POST', '/user/register', ['App\Controllers\UserController', 'register']);
$r->addRoute('POST', '/user/login', ['App\Controllers\UserController', 'login']);
$r->addRoute('POST', '/account/update', ['App\Controllers\AccountController', 'updateProfile']);

// Rutas de reservas
$r->addRoute('POST', '/reservations/checkAvailability', ['App\Controllers\ReservationController', 'checkAvailability']);
$r->addRoute('POST', '/reservations/store', ['App\Controllers\ReservationController', 'store']);