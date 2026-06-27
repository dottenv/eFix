<?php

namespace App\Core;

class Router
{
    private array $routes = [];
    private array $globalMiddleware = [];

    public function addGlobalMiddleware(callable|array|object $middleware): void
    {
        $this->globalMiddleware[] = $middleware;
    }

    public function add(string $method, string $pattern, callable|array $handler, array $middleware = []): void
    {
        $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $pattern);
        $this->routes[] = [
            'method' => strtoupper($method),
            'pattern' => '#^' . $pattern . '$#',
            'handler' => $handler,
            'middleware' => $middleware,
        ];
    }

    public function get(string $pattern, callable|array $handler, array $middleware = []): void
    {
        $this->add('GET', $pattern, $handler, $middleware);
    }

    public function post(string $pattern, callable|array $handler, array $middleware = []): void
    {
        $this->add('POST', $pattern, $handler, $middleware);
    }

    public function any(string $pattern, callable|array $handler, array $middleware = []): void
    {
        $this->add('GET', $pattern, $handler, $middleware);
        $this->add('POST', $pattern, $handler, $middleware);
    }

    public function dispatch(Request $request): array
    {
        $method = $request->method();
        $uri = $request->uri();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            if (preg_match($route['pattern'], $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                return [
                    'handler' => $route['handler'],
                    'params' => $params,
                    'middleware' => array_merge($this->globalMiddleware, $route['middleware']),
                ];
            }
        }

        return [];
    }
}
