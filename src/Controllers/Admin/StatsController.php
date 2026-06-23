<?php

namespace App\Controllers\Admin;

use App\Core\View;

class StatsController
{
    private View $view;

    public function __construct()
    {
        $this->view = new View();
    }

    public function index(): string
    {
        return $this->view->layout('admin', $this->view->render('admin/stats'), [
            'title' => 'Аналитика — eFix Admin',
        ]);
    }
}
