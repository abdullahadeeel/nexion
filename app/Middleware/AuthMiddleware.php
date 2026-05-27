<?php

namespace App\Middleware;

use Phpify\Middleware\Middleware;
use Phpify\Http\Request;
use Phpify\Http\Response;

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
