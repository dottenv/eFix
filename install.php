<?php

const REPO = 'https://github.com/dottenv/eFix.git';
const VERSION_URL = 'https://raw.githubusercontent.com/dottenv/eFix/main/VERSION';

$root = __DIR__;
$versionFile = "$root/VERSION";
$lockFile = "$root/storage/.installed";
$htaccess = "$root/.htaccess";
$currentVersion = file_exists($versionFile) ? trim(file_get_contents($versionFile)) : '0.0.0';

// --- CLI ---
if (PHP_SAPI === 'cli') {
    $action = $argv[1] ?? 'install';
    match ($action) {
        '--check', 'check' => cliCheckUpdate($currentVersion),
        '--update', 'update' => cliRunUpdate($root, $currentVersion),
        default => cliInstall($root, $htaccess, $lockFile),
    };
    exit;
}

// --- Web ---
$action = $_GET['action'] ?? 'redirect';
match ($action) {
    'check' => webCheckUpdate($currentVersion),
    'update' => webRunUpdate($root, $currentVersion),
    default => webRedirect(),
};

// ============================================================
// CLI
// ============================================================

function cliInstall(string $root, string $htaccess, string $lockFile): void
{
    if (file_exists($lockFile)) {
        echo "eFix уже установлен. Используйте: php install.php --check\n";
        exit;
    }

    $isBare = !file_exists("$root/index.php") || !is_dir("$root/src");

    if ($isBare) {
        $tmpDir = sys_get_temp_dir() . '/efix_' . uniqid();
        echo "Клонирование репозитория...\n";
        exec("git clone --depth=1 " . REPO . " $tmpDir 2>&1", $out, $code);

        if ($code !== 0) {
            die("Ошибка клонирования: " . implode("\n", $out) . "\n");
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($tmpDir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            $dest = "$root/" . $iterator->getSubPathname();
            if ($item->isDir()) {
                !is_dir($dest) && mkdir($dest, 0755, true);
            } else {
                copy($item, $dest);
            }
        }

        exec("rm -rf $tmpDir");
        echo "Репозиторий склонирован.\n";
    }

    if (!file_exists($htaccess)) {
        file_put_contents($htaccess, implode("\n", [
            'DirectoryIndex index.php',
            '',
            'RewriteEngine On',
            'RewriteCond %{REQUEST_FILENAME} !-f',
            'RewriteCond %{REQUEST_FILENAME} !-d',
            'RewriteRule ^ index.php [QSA,L]',
            '',
        ]));
        echo ".htaccess создан.\n";
    }

    echo "Установщик доступен по адресу: /install/\n";
}

function cliCheckUpdate(string $currentVersion): void
{
    echo "Текущая версия: $currentVersion\n";
    echo "Проверка обновлений...\n";

    $remote = @file_get_contents(VERSION_URL);

    if ($remote === false) {
        echo "Не удалось проверить обновления.\n";
        exit(1);
    }

    $remoteVersion = trim($remote);
    echo "Последняя версия: $remoteVersion\n";

    if (version_compare($remoteVersion, $currentVersion, '>')) {
        echo "Доступно обновление: $currentVersion → $remoteVersion\n";
        echo "Выполните: php install.php --update\n";
    } else {
        echo "Установлена актуальная версия.\n";
    }
}

function cliRunUpdate(string $root, string $currentVersion): void
{
    echo "Обновление eFix $currentVersion → ...\n";

    if (!is_dir("$root/.git")) {
        echo "Репозиторий не найден. Выполните:\n";
        echo "  git init\n";
        echo "  git remote add origin " . REPO . "\n";
        echo "  git fetch --all\n";
        echo "  git reset --hard origin/main\n";
        exit(1);
    }

    $backup = saveBackup($root);
    echo "Создана резервная копия конфигурации.\n";

    exec("cd $root && git fetch --all 2>&1 && git reset --hard origin/main 2>&1", $out, $code);

    if ($code !== 0) {
        echo "Ошибка обновления:\n" . implode("\n", $out) . "\n";
        exit(1);
    }

    restoreBackup($root, $backup);

    $newVersion = file_exists("$root/VERSION") ? trim(file_get_contents("$root/VERSION")) : '???';
    echo "Обновление завершено: $newVersion\n";
}

function saveBackup(string $root): string
{
    $backup = "$root/storage/backup_" . date('Ymd_His');
    $keep = ['config/app.php', '.env', 'storage/.installed'];

    if (!is_dir($backup)) mkdir($backup, 0755, true);

    foreach ($keep as $file) {
        $src = "$root/$file";
        if (file_exists($src)) {
            copy($src, "$backup/" . basename($file));
        }
    }

    return $backup;
}

function restoreBackup(string $root, string $backup): void
{
    if (!is_dir($backup)) return;

    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($backup, RecursiveDirectoryIterator::SKIP_DOTS)
    );

    foreach ($files as $file) {
        if ($file->isFile()) {
            copy($file, "$root/" . $file->getFilename());
        }
    }

    exec("rm -rf $backup");
}

// ============================================================
// Web
// ============================================================

function webRedirect(): void
{
    header('Location: /install/');
    exit;
}

function webCheckUpdate(string $currentVersion): void
{
    header('Content-Type: application/json');

    $remote = @file_get_contents(VERSION_URL);

    if ($remote === false) {
        echo json_encode([
            'error' => 'Не удалось проверить обновления',
            'current' => $currentVersion,
        ]);
        exit;
    }

    $remoteVersion = trim($remote);
    $hasUpdate = version_compare($remoteVersion, $currentVersion, '>');

    echo json_encode([
        'current' => $currentVersion,
        'latest' => $remoteVersion,
        'has_update' => $hasUpdate,
        'update_url' => $hasUpdate ? '/install.php?action=update' : null,
    ]);
}

function webRunUpdate(string $root, string $currentVersion): void
{
    header('Content-Type: application/json');

    if (!is_dir("$root/.git")) {
        echo json_encode(['error' => 'Репозиторий не найден']);
        exit;
    }

    $backup = saveBackup($root);

    exec("cd $root && git fetch --all 2>&1 && git reset --hard origin/main 2>&1", $out, $code);

    if ($code !== 0) {
        echo json_encode(['error' => implode("\n", $out)]);
        exit;
    }

    restoreBackup($root, $backup);

    $newVersion = file_exists("$root/VERSION") ? trim(file_get_contents("$root/VERSION")) : '???';

    echo json_encode([
        'success' => true,
        'previous' => $currentVersion,
        'current' => $newVersion,
    ]);
}
