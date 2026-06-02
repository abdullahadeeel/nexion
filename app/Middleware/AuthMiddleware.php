<?php

namespace App\Middleware;

use Nexion\Middleware\Middleware;
use Nexion\Http\Request;
use Nexion\Http\Response;

class AuthMiddleware implements Middleware
{
    public function handle(Request $request, \Closure $next): Response
    {
        // Simple mock auth check
        if ($request->input('auth') !== '1') {
            return (new Response())->setStatusCode(401)->setContent('Unauthorized. Please add ?auth=1 to the URL.');
        }

        return $next($request);
    }
}
