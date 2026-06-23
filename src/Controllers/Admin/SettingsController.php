<?php

namespace App\Controllers\Admin;

use App\Core\View;

class SettingsController
{
    private View $view;

    public function __construct()
    {
        $this->view = new View();
    }

    public function index(): string
    {
        return $this->view->layout('admin', $this->view->render('admin/settings'), [
            'title' => 'Настройки — eFix Admin',
        ]);
    }
}
