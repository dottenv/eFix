<?php

namespace App\Controllers\Admin;

use App\Core\View;

class MailTemplatesController
{
    private View $view;

    public function __construct()
    {
        $this->view = new View();
    }

    public function index(): string
    {
        return $this->view->layout('admin', $this->view->render('admin/mail_templates'), [
            'title' => 'Шаблоны писем — eFix Admin',
        ]);
    }
}
