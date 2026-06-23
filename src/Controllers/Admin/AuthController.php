<?php

namespace App\Controllers\Admin;

use App\Core\View;
use App\Core\Request;

class AuthController
{
    private View $view;

    public function __construct()
    {
        $this->view = new View();
    }

    public function login(): string
    {
        if (Request::isPost()) {
            // TODO: validate credentials
            $_SESSION['admin'] = true;
            header('Location: /admin/dashboard');
            exit;
        }

        return $this->view->render('admin/login');
    }

    public function logout(): void
    {
        unset($_SESSION['admin']);
        header('Location: /admin/login');
        exit;
    }
}
