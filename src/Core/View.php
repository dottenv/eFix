<?php

namespace App\Core;

class View
{
    private string $templatesPath;
    private array $globalData = [];

    public function __construct(string $templatesPath)
    {
        $this->templatesPath = rtrim($templatesPath, '/\\');
    }

    public function addGlobal(string $key, mixed $value): void
    {
        $this->globalData[$key] = $value;
    }

    public function render(string $template, array $data = [], string $layout = null): string
    {
        $content = $this->renderPartial($template, $data);

        if ($layout) {
            return $this->renderPartial($layout, array_merge($data, ['content' => $content]));
        }

        return $content;
    }

    public function renderPartial(string $template, array $data = []): string
    {
        $file = $this->templatesPath . '/' . $template . '.php';

        if (!file_exists($file)) {
            throw new \RuntimeException("Template not found: {$file}");
        }

        extract(array_merge($this->globalData, $data), EXTR_SKIP);

        ob_start();
        include $file;
        return ob_get_clean();
    }

    public function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
