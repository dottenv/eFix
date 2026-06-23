<?php

namespace App\Controllers\Admin;

use App\Core\View;

class ServicesController
{
    private View $view;

    public function __construct()
    {
        $this->view = new View();
    }

    public function index(): string
    {
        return $this->view->layout('admin', $this->view->render('admin/services'), [
            'title' => 'Услуги — eFix Admin',
        ]);
    }
}
