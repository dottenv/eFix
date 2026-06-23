<?php

return [
    '/'             => ['HomeController', 'index'],
    '/services'     => ['ServiceController', 'index'],
    '/prices'       => ['PriceController', 'index'],
    '/about'        => ['AboutController', 'index'],
    '/contacts'     => ['ContactController', 'index'],
    '/contacts/send'=> ['ContactController', 'send'],

    '/admin/login'      => ['Admin\\AuthController', 'login'],
    '/admin/logout'     => ['Admin\\AuthController', 'logout'],
    '/admin/dashboard'  => ['Admin\\DashboardController', 'index'],
    '/admin/site'       => ['Admin\\ContentController', 'index'],
    '/admin/services'   => ['Admin\\ServicesController', 'index'],
    '/admin/prices'     => ['Admin\\PricesController', 'index'],
    '/admin/requests'   => ['Admin\\RequestsController', 'index'],
    '/admin/workshops'  => ['Admin\\WorkshopsController', 'index'],
    '/admin/stats'      => ['Admin\\StatsController', 'index'],
    '/admin/mail-config' => ['Admin\\MailConfigController', 'index'],
    '/admin/mail-templates' => ['Admin\\MailTemplatesController', 'index'],
    '/admin/settings'   => ['Admin\\SettingsController', 'index'],
];
