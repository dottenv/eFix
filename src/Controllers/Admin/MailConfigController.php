<?php

namespace App\Controllers\Admin;

use App\Core\View;

class MailConfigController
{
    private View $view;

    public function __construct()
    {
        $this->view = new View();
    }

    public function index(): string
    {
        return $this->view->layout('admin', $this->view->render('admin/mail_config'), [
            'title' => 'Настройки SMTP — eFix Admin',
        ]);
    }
}
