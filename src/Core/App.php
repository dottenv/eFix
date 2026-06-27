<?php

namespace App\Core;

use App\Core\Middleware\MiddlewarePipeline;

class App
{
    private Router $router;
    private MiddlewarePipeline $pipeline;

    public function __construct()
    {
        $this->router = new Router();
        $this->pipeline = new MiddlewarePipeline();
    }

    public function router(): Router
    {
        return $this->router;
    }

    public function pipeline(): MiddlewarePipeline
    {
        return $this->pipeline;
    }

    public function addMiddleware(callable|MiddlewareInterface|array $middleware): void
    {
        $this->pipeline->add($middleware);
    }

    public function run(): void
    {
        $request = new Request();
        $response = new Response();

        $route = $this->router->dispatch($request);

        if (empty($route)) {
            (new Response())
                ->setStatusCode(404)
                ->setContent('404 Not Found')
                ->send();
            return;
        }

        foreach ($route['middleware'] as $mw) {
            $this->pipeline->add($mw);
        }

        $handler = $route['handler'];
        $params = $route['params'];

        $response = $this->pipeline->handle($request, function (Request $req) use ($handler, $params) {
            if (is_array($handler)) {
                [$controller, $action] = $handler;
                $controllerInstance = is_string($controller) ? new $controller() : $controller;
                return call_user_func_array([$controllerInstance, $action], array_merge([$req], $params));
            }
            return call_user_func_array($handler, array_merge([$req], $params));
        });

        $response->send();
    }
}
