<?php

require_once __DIR__ . '/../src/autoload.php';

$config = require __DIR__ . '/../config/config.php';

if ($config['app']['debug']) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}

\App\Core\Database::init($config['db']);

$app = new \App\Core\App();
$view = new \App\Core\View(__DIR__ . '/../templates');

$view->addGlobal('title', $config['app']['name']);
$view->addGlobal('metaDescription', 'Сервисный центр по ремонту телефонов, планшетов, ноутбуков и компьютеров');

$app->addMiddleware(new \App\Middleware\SessionMiddleware());
$app->addMiddleware(new \App\Middleware\CorsMiddleware());
$app->addMiddleware(new \App\Middleware\StaticFileMiddleware(__DIR__));

$routes = require __DIR__ . '/../config/routes.php';
$routes($app->router(), $view);

$app->run();
