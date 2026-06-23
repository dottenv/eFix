<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_starts_with(trim($line), '#')) continue;
        if (str_contains($line, '=')) {
            [$k, $v] = explode('=', $line, 2);
            putenv(trim($k) . '=' . trim($v));
        }
    }
}

define('SECRET_KEY', getenv('SECRET_KEY') ?: 'eFix-secret-key-2024');
define('DATABASE_URL', getenv('DATABASE_URL') ?: 'sqlite:' . __DIR__ . '/../efix.db');
define('DB_USER', getenv('DB_USER') ?: '');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('SITE_NAME', 'eFix');
