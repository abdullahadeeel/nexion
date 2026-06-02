<?php

/**
 * nexion - A simple PHP Framework
 */

require_once __DIR__ . '/../vendor/autoload.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Register Error Handler
\Nexion\Exception\ErrorHandler::register();

// Load environment variables
\Nexion\Config\Dotenv::load(dirname(__DIR__));

/** @var \Nexion\Foundation\Application $app */
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Load routes
require_once __DIR__ . '/../routes/web.php';

// Handle the request
$request = new \Nexion\Http\Request();
$response = $app->handle($request);

$response->send();
