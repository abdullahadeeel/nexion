<?php

use Nexion\Foundation\Application;
use App\Controllers\TodoController;

$router = Application::$app->router;

$router->get('/', [TodoController::class, 'index']);
$router->post('/todos', [TodoController::class, 'store']);
$router->post('/todos/{id}/toggle', [TodoController::class, 'toggle']);
$router->get('/todos/{id}/edit', [TodoController::class, 'edit']);
$router->post('/todos/{id}/update', [TodoController::class, 'update']);
$router->post('/todos/{id}/delete', [TodoController::class, 'destroy']);
