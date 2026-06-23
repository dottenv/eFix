<?php

namespace App\Controllers;

use App\Core\View;
use App\Core\Request;

class ContactController
{
    private View $view;

    public function __construct()
    {
        $this->view = new View();
    }

    public function index(): string
    {
        $content = $this->view->render('pages/contacts');

        if (Request::isHtmx()) {
            return $content;
        }

        return $this->view->layout('main', $content, [
            'title' => 'Контакты — eFix',
        ]);
    }

    public function send(): string
    {
        $name = Request::input('name');
        $phone = Request::input('phone');
        $device = Request::input('device');
        $model = Request::input('model');
        $message = Request::input('message');

        // TODO: save to DB, send email notification

        return json_encode(['success' => true, 'message' => 'Заявка отправлена']);
    }
}
