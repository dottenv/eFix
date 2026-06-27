<?php

namespace App\Core\Controller;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;

abstract class AbstractController
{
    protected View $view;

    public function __construct(View $view)
    {
        $this->view = $view;
    }

    protected function render(string $template, array $data = [], string $layout = null): Response
    {
        $content = $this->view->render($template, $data, $layout);
        return (new Response())->setContent($content);
    }

    protected function json(mixed $data, int $status = 200): Response
    {
        return (new Response())->json($data, $status);
    }

    protected function redirect(string $url, int $status = 302): Response
    {
        return (new Response())->redirect($url, $status);
    }

    protected function flash(string $key, string $message): void
    {
        $_SESSION['_flash'][$key] = $message;
    }

    protected function getFlash(string $key): ?string
    {
        return $_SESSION['_flash'][$key] ?? null;
    }

    protected function clearFlash(): void
    {
        unset($_SESSION['_flash']);
    }
}
