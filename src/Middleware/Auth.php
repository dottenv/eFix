<?php

namespace App\Middleware;

class Auth
{
    public static function check(): void
    {
        if (empty($_SESSION['admin'])) {
            header('Location: /admin/login');
            exit;
        }
    }
}
