<?php

namespace Phpify\Core\Middleware;

use Phpify\Core\Http\Request;
use Phpify\Core\Http\Response;

interface Middleware
{
    public function handle(Request $request, \Closure $next): Response;
}
