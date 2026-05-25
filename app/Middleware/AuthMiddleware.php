<?php

namespace App\Middleware;

use Phpify\Core\Middleware\Middleware;
use Phpify\Core\Http\Request;
use Phpify\Core\Http\Response;

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
