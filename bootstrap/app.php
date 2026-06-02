<?php

use Nexion\Foundation\Application;

$app = new Application(dirname(__DIR__));

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
*/
$app->register(Nexion\Foundation\Providers\RoutingServiceProvider::class);
$app->register(Nexion\Foundation\Providers\DatabaseServiceProvider::class);
$app->register(Nexion\Foundation\Providers\ViewServiceProvider::class);

/*
|--------------------------------------------------------------------------
| Bind Kernels
|--------------------------------------------------------------------------
*/
$app->singleton(Nexion\Http\Kernel::class, App\Http\Kernel::class);
$app->singleton(Nexion\Console\Kernel::class, App\Console\Kernel::class);

$app->singleton(Nexion\Console\Application::class, function () {
    return new Nexion\Console\Application();
});

return $app;
