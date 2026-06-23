<?php

/**
 * eFix — Bootstrap installer
 *
 * Использование:
 *   Веб:     загрузите install.php на сервер, откройте site.com/install.php
 *            или сразу перейдите на site.com/install/
 *   CLI:     php install.php
 *
 * Что делает:
 *   1. Клонирует репозиторий (если папка пуста)
 *   2. Создаёт public/.htaccess
 *   3. Редиректит на /install/ (web-установщик)
 */

$publicDir = __DIR__ . '/public';
$htaccess = $publicDir . '/.htaccess';
$lockFile = __DIR__ . '/storage/.installed';

// Уже установлено?
if (file_exists($lockFile)) {
    echo "eFix уже установлен.\n";
    exit;
}

// Клонирование репозитория, если папка пуста
$isBare = !is_dir($publicDir) || (count(scandir($publicDir)) <= 2);

if ($isBare) {
    $repo = 'https://github.com/dottenv/eFix.git';
    $tmp = sys_get_temp_dir() . '/efix_' . uniqid();

    echo "Клонирование репозитория...\n";
    exec("git clone --depth=1 $repo $tmp 2>&1", $out, $code);

    if ($code !== 0) {
        die("Ошибка клонирования: " . implode("\n", $out) . "\n");
    }

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($tmp, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($iterator as $item) {
        $dest = __DIR__ . '/' . $iterator->getSubPathname();
        if ($item->isDir()) {
            !is_dir($dest) && mkdir($dest, 0755, true);
        } else {
            copy($item, $dest);
        }
    }

    exec("rm -rf $tmp");
    echo "Репозиторий склонирован.\n";
}

// Создание .htaccess
if (!file_exists($htaccess)) {
    file_put_contents($htaccess, implode("\n", [
        'RewriteEngine On',
        'RewriteCond %{REQUEST_FILENAME} !-f',
        'RewriteCond %{REQUEST_FILENAME} !-d',
        'RewriteRule ^ index.php [QSA,L]',
        '',
    ]));
    echo ".htaccess создан.\n";
}

// Если запущен из браузера — редирект на установщик
if (PHP_SAPI !== 'cli') {
    header('Location: /install/');
    exit;
}

echo "Установщик доступен по адресу: /install/\n";
echo "Или запустите: php -S localhost:8000 -t public\n";
