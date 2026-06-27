<?php

return [
    'db' => [
        'host' => getenv('DB_HOST') ?: 'localhost',
        'port' => getenv('DB_PORT') ?: '3306',
        'dbname' => getenv('DB_NAME') ?: 'efix',
        'username' => getenv('DB_USER') ?: 'root',
        'password' => getenv('DB_PASS') ?: '',
        'charset' => 'utf8mb4',
    ],
    'app' => [
        'name' => 'eFix',
        'debug' => (bool) (getenv('APP_DEBUG') ?: false),
        'url' => getenv('APP_URL') ?: 'http://localhost:8000',
    ],
];
