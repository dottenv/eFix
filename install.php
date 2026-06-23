<?php

$htaccess = __DIR__ . '/.htaccess';
$lockFile = __DIR__ . '/storage/.installed';

if (file_exists($lockFile)) {
    echo "eFix уже установлен.\n";
    exit;
}

$isBare = !file_exists(__DIR__ . '/index.php') || !is_dir(__DIR__ . '/src');

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

if (PHP_SAPI !== 'cli') {
    header('Location: /install/');
    exit;
}

echo "Установщик доступен по адресу: /install/\n";
