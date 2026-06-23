<?php

namespace App\Controllers\Admin;

use App\Core\View;

class DashboardController
{
    private View $view;

    public function __construct()
    {
        $this->view = new View();
    }

    public function index(): string
    {
        return $this->view->layout('admin', $this->view->render('admin/dashboard'), [
            'title' => 'Дашборд — eFix Admin',
        ]);
    }
}
