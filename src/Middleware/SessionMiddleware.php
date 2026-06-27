<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Core\Middleware\MiddlewareInterface;

class SessionMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next): Response
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return $next($request);
    }
}
