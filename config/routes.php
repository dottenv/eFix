<?php

use App\Controllers\AdminController;
use App\Controllers\HomeController;
use App\Controllers\LeadController;
use App\Middleware\AuthMiddleware;

return function (App\Core\Router $router, App\Core\View $view) {

    $home = new HomeController($view);
    $lead = new LeadController($view);
    $admin = new AdminController($view);

    $auth = [new AuthMiddleware()];

    // Public routes
    $router->get('/', [$home, 'index']);
    $router->get('/service/{slug}', [$home, 'service']);

    // Lead submission
    $router->post('/lead', [$lead, 'submit']);

    // Admin auth
    $router->get('/admin/login', [$admin, 'loginForm']);
    $router->post('/admin/login', [$admin, 'login']);
    $router->get('/admin/logout', [$admin, 'logout']);

    // Admin protected routes
    $router->get('/admin', [$admin, 'dashboard'], $auth);
    $router->get('/admin/leads', [$lead, 'adminList'], $auth);
    $router->post('/admin/leads/{id}/status', [$lead, 'updateStatus'], $auth);
    $router->post('/admin/leads/{id}/delete', [$lead, 'delete'], $auth);

    $router->get('/admin/services', [$admin, 'services'], $auth);
    $router->post('/admin/services/save', [$admin, 'saveService'], $auth);
    $router->post('/admin/services/save/{id}', [$admin, 'saveService'], $auth);
    $router->get('/admin/services/delete/{id}', [$admin, 'deleteService'], $auth);

    $router->get('/admin/pages', [$admin, 'pages'], $auth);
    $router->post('/admin/pages/save', [$admin, 'savePage'], $auth);
    $router->post('/admin/pages/save/{id}', [$admin, 'savePage'], $auth);
    $router->get('/admin/pages/delete/{id}', [$admin, 'deletePage'], $auth);
};
