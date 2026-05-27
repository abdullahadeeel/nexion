<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Phpify\Foundation\Application;

\Phpify\Exception\ErrorHandler::register();

// Load environment variables
\Phpify\Config\Dotenv::load(dirname(__DIR__));

$config = [
    'db' => [
        'host' => env('DB_HOST', '127.0.0.1'),
        'dbname' => env('DB_DATABASE', 'phpify'),
        'user' => env('DB_USERNAME', 'root'),
        'password' => env('DB_PASSWORD', '')
      ]
];

$app = new Application(dirname(__DIR__), $config);

// Load routes
require_once __DIR__ . '/../routes/web.php';

$app->run();
