<?php

namespace App\Controllers\Admin;

use App\Core\View;

class ContentController
{
    private View $view;

    public function __construct()
    {
        $this->view = new View();
    }

    public function index(): string
    {
        return $this->view->layout('admin', $this->view->render('admin/content'), [
            'title' => 'Редактор контента — eFix Admin',
        ]);
    }
}
