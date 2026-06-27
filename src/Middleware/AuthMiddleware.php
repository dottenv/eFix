<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Core\Middleware\MiddlewareInterface;

class AuthMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next): Response
    {
        $isLoggedIn = $_SESSION['admin_logged_in'] ?? false;

        if (!$isLoggedIn) {
            if ($request->isAjax()) {
                return (new Response())->json(['error' => 'Unauthorized'], 401);
            }
            return (new Response())->redirect('/admin/login');
        }

        return $next($request);
    }
}
