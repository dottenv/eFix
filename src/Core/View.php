<?php

namespace App\Core;

class View
{
    private string $viewsPath;

    public function __construct(?string $viewsPath = null)
    {
        $this->viewsPath = $viewsPath ?: __DIR__ . '/../../views';
    }

    public function render(string $template, array $data = []): string
    {
        $file = $this->viewsPath . '/' . $template . '.php';

        if (!file_exists($file)) {
            throw new \RuntimeException("Template not found: {$template}");
        }

        extract($data);
        ob_start();
        require $file;
        return ob_get_clean();
    }

    public function layout(string $layout, string $slot, array $data = []): string
    {
        $data['slot'] = $slot;
        return $this->render('layouts/' . $layout, $data);
    }

    public function component(string $name, array $data = []): string
    {
        return $this->render('components/' . $name, $data);
    }
}
