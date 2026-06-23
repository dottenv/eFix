<?php
class Router {
    private $uri;
    private $method;

    public function __construct() {
        $this->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->method = $_SERVER['REQUEST_METHOD'];
    }

    public function uri() { return $this->uri; }
    public function method() { return $this->method; }

    public function dispatch() {
        $parts = explode('/', trim($this->uri, '/'));
        $module = $parts[0] ?? '';

        // Static files
        if (str_starts_with($this->uri, '/static/')) {
            $this->serveStatic();
            return;
        }

        // Module routing: /install/… → modules/install/
        if ($module && is_dir(__DIR__ . '/../modules/' . $module)) {
            $this->loadModule($module);
            return;
        }

        // Legacy routing
        $this->legacyRoute();
    }

    private function serveStatic() {
        $file = __DIR__ . '/..' . $this->uri;
        if (!file_exists($file)) { notFound(); return; }
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $mime = [
            'css' => 'text/css', 'js' => 'application/javascript',
            'png' => 'image/png', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg',
            'gif' => 'image/gif', 'svg' => 'image/svg+xml',
            'webp' => 'image/webp', 'ico' => 'image/x-icon',
            'woff2' => 'font/woff2',
        ];
        header('Content-Type: ' . ($mime[$ext] ?? 'application/octet-stream'));
        readfile($file);
        exit;
    }

    private function loadModule($name) {
        $init = __DIR__ . '/../modules/' . $name . '/init.php';
        if (file_exists($init)) {
            require $init;
        } else {
            notFound();
        }
    }

    private function legacyRoute() {
        $uri = $this->uri;
        $method = $this->method;

        // Clean URLs: /script → script.php
        if (preg_match('#^/([a-zA-Z0-9_-]+)$#', $this->uri, $m)) {
            $script = __DIR__ . '/../' . $m[1] . '.php';
            if (file_exists($script) && !in_array($m[1], ['index','config','database','helpers','hooks','render','app'])) {
                require $script;
                exit;
            }
        }

        trackPageView($this->uri);

        require_once __DIR__ . '/../routes/main.php';
        require_once __DIR__ . '/../routes/api.php';
        require_once __DIR__ . '/../routes/admin.php';

        notFound();
    }
}
