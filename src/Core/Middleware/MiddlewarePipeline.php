<?php

namespace App\Core\Middleware;

use App\Core\Request;
use App\Core\Response;

class MiddlewarePipeline
{
    private array $middleware = [];

    public function add(callable|MiddlewareInterface|array $middleware): void
    {
        $this->middleware[] = $middleware;
    }

    public function handle(Request $request, callable $coreHandler): Response
    {
        $pipeline = array_reverse($this->middleware);

        $next = $coreHandler;

        foreach ($pipeline as $middleware) {
            $next = $this->createNext($middleware, $next);
        }

        return $next($request);
    }

    private function createNext(callable|MiddlewareInterface|array $middleware, callable $next): callable
    {
        return function (Request $request) use ($middleware, $next): Response {
            if ($middleware instanceof MiddlewareInterface) {
                return $middleware->handle($request, $next);
            }

            if (is_array($middleware)) {
                [$class, $method] = $middleware;
                $instance = is_string($class) ? new $class() : $class;
                return $instance->$method($request, $next);
            }

            return $middleware($request, $next);
        };
    }
}
