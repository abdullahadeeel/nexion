<?php

use Phpify\Core\Application;
use App\Controllers\HomeController;

$router = Application::$app->router;

$router->get('/', [HomeController::class, 'index']);
$router->get('/user/{id}', [HomeController::class, 'show']);
$router->get('/admin', function() {
    return "Welcome to the Admin Area!";
})->middleware(\App\Middleware\AuthMiddleware::class);
