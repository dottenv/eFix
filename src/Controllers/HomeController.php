<?php

namespace App\Controllers;

use App\Core\View;
use App\Core\Request;

class HomeController
{
    private View $view;

    public function __construct()
    {
        $this->view = new View();
    }

    public function index(): string
    {
        $content = $this->view->render('pages/home');

        if (Request::isHtmx()) {
            return $content;
        }

        return $this->view->layout('main', $content, [
            'title' => 'eFix — Ремонт цифровой техники',
            'metaDescription' => 'Выездной сервисный центр по ремонту телефонов, планшетов, ноутбуков и ПК',
        ]);
    }

    public function notFound(): string
    {
        http_response_code(404);
        $content = $this->view->render('pages/404', [
            'title' => 'Страница не найдена',
        ]);

        if (Request::isHtmx()) {
            return $content;
        }

        return $this->view->layout('main', $content, [
            'title' => '404 — eFix',
        ]);
    }
}
