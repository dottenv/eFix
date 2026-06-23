<?php

namespace App\Core;

class Router
{
    private array $routes = [];
    private array $globalMiddleware = [];

    public function add(string $method, string $path, array $handler, array $middleware = []): void
    {
        $this->routes[] = compact('method', 'path', 'handler', 'middleware');
    }

    public function addGlobalMiddleware(callable $mw): void
    {
        $this->globalMiddleware[] = $mw;
    }

    public function loadRoutes(array $routes): void
    {
        foreach ($routes as $path => [$controller, $action]) {
            $this->add('GET', $path, [$controller, $action]);
        }
    }

    public function dispatch(string $method, string $uri): void
    {
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = rtrim($uri, '/') ?: '/';

        foreach ($this->globalMiddleware as $mw) {
            $mw();
        }

        foreach ($this->routes as $route) {
            $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';

            if ($route['method'] !== $method || !preg_match($pattern, $uri, $matches)) {
                continue;
            }

            foreach ($route['middleware'] as $mw) {
                $mw();
            }

            [$controller, $action] = $route['handler'];
            $controllerClass = 'App\\Controllers\\' . $controller;
            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

            echo (new $controllerClass)->$action(...$params);
            return;
        }

        http_response_code(404);
        echo (new \App\Controllers\HomeController)->notFound();
    }
}
