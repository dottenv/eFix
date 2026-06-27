<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Core\Middleware\MiddlewareInterface;

class StaticFileMiddleware implements MiddlewareInterface
{
    private string $publicDir;
    private array $allowedExtensions;

    public function __construct(string $publicDir, array $allowedExtensions = ['css', 'js', 'png', 'jpg', 'jpeg', 'gif', 'svg', 'ico', 'woff', 'woff2', 'ttf', 'eot'])
    {
        $this->publicDir = rtrim($publicDir, '/\\');
        $this->allowedExtensions = $allowedExtensions;
    }

    public function handle(Request $request, callable $next): Response
    {
        $uri = $request->uri();

        if ($uri === '/' || str_starts_with($uri, '/admin')) {
            return $next($request);
        }

        $ext = pathinfo($uri, PATHINFO_EXTENSION);
        if (!in_array(strtolower($ext), $this->allowedExtensions, true)) {
            return $next($request);
        }

        $filePath = $this->publicDir . '/' . ltrim($uri, '/');
        $realPath = realpath($filePath);

        if ($realPath === false || !is_file($realPath)) {
            return $next($request);
        }

        if (!str_starts_with($realPath, realpath($this->publicDir))) {
            return $next($request);
        }

        $mimeTypes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'ico' => 'image/x-icon',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject',
        ];

        $mime = $mimeTypes[$ext] ?? 'application/octet-stream';

        $response = new Response();
        $response->setHeader('Content-Type', $mime);
        $response->setHeader('Content-Length', (string) filesize($realPath));
        $response->setHeader('Cache-Control', 'public, max-age=31536000');
        $response->setContent(file_get_contents($realPath));

        return $response;
    }
}
