<?php

namespace App\Http;

use Nexion\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     */
    protected array $middleware = [
        // \App\Middleware\TrustProxies::class,
    ];
}
