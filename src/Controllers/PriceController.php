<?php

namespace App\Controllers;

use App\Core\View;
use App\Core\Request;

class PriceController
{
    private View $view;

    public function __construct()
    {
        $this->view = new View();
    }

    public function index(): string
    {
        $content = $this->view->render('pages/prices');

        if (Request::isHtmx()) {
            return $content;
        }

        return $this->view->layout('main', $content, [
            'title' => 'Цены — eFix',
            'metaDescription' => 'Прайс-лист на ремонт цифровой техники',
        ]);
    }
}
