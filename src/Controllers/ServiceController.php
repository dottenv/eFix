<?php

namespace App\Controllers;

use App\Core\View;
use App\Core\Request;

class ServiceController
{
    private View $view;

    public function __construct()
    {
        $this->view = new View();
    }

    public function index(): string
    {
        $content = $this->view->render('pages/services');

        if (Request::isHtmx()) {
            return $content;
        }

        return $this->view->layout('main', $content, [
            'title' => 'Услуги — eFix',
            'metaDescription' => 'Полный список услуг по ремонту цифровой техники',
        ]);
    }
}
