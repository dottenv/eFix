<?php

namespace App\Controllers;

use App\Core\View;
use App\Core\Request;

class AboutController
{
    private View $view;

    public function __construct()
    {
        $this->view = new View();
    }

    public function index(): string
    {
        $content = $this->view->render('pages/about');

        if (Request::isHtmx()) {
            return $content;
        }

        return $this->view->layout('main', $content, [
            'title' => 'О нас — eFix',
            'metaDescription' => 'Выездной сервисный центр eFix — ремонтируем технику с выездом к вам',
        ]);
    }
}
