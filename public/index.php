<?php

require __DIR__ . '/../autoload.php';

$config = require __DIR__ . '/../config/app.php';
$routes = require __DIR__ . '/../config/routes.php';

use App\Core\{Router, Request};

$router = new Router();

$router->loadRoutes($routes);

$router->addGlobalMiddleware(function () {
    session_start();
});

$router->dispatch(Request::method(), Request::uri());
