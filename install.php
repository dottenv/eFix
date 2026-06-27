<?php

/**
 * eFix Installer
 *
 * Usage:
 *   CLI:   php install.php
 *   Web:   open http://your-site/install.php
 *
 * Requirements: PHP 7.4+, ext-pdo, ext-pdo_mysql, ext-json, ext-mbstring, git
 */

const EFIX_REPO = 'https://github.com/dottenv/eFix.git';
const EFIX_VERSION = '1.0.0';

// ─── Boot ───────────────────────────────────────────────────────────────

$isCli = (php_sapi_name() === 'cli');
$output = '';
$errors = [];

function out(string $msg, string $type = 'info'): void
{
    global $isCli, $output;
    if ($isCli) {
        $prefix = match ($type) {
            'success' => "✓",
            'error'   => "✗",
            'warning' => "!",
            'step'    => "→",
            default   => " ",
        };
        fwrite(STDOUT, "$prefix $msg\n");
    } else {
        $class = match ($type) {
            'success' => 'success',
            'error'   => 'error',
            'warning' => 'warning',
            default   => '',
        };
        $output .= "<li class=\"$class\">" . htmlspecialchars($msg) . "</li>\n";
    }
}

function fail(string $msg): never
{
    global $isCli;
    if ($isCli) {
        fwrite(STDERR, "✗ $msg\n");
        fwrite(STDERR, "Установка прервана.\n");
        exit(1);
    }
    renderWeb(error: $msg);
    exit;
}

// ─── Web renderer ───────────────────────────────────────────────────────

function renderWeb(string $error = ''): never
{
    global $output;
    $out = $output;
    $version = EFIX_VERSION;
    $errorBox = $error !== '' ? '<div class="error-box">' . htmlspecialchars($error) . '</div>' : '';
    echo <<<HTML
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Установка eFix</title>
<style>
  *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
  body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;
       background:#f1f5f9;color:#1e293b;display:flex;justify-content:center;padding:40px 20px}
  .box{max-width:720px;width:100%;background:#fff;border-radius:12px;padding:40px;
       box-shadow:0 1px 3px rgba(0,0,0,.1)}
  h1{font-size:28px;margin-bottom:8px}
  p{color:#64748b;margin-bottom:24px}
  ul{list-style:none;margin-bottom:24px}
  li{padding:8px 12px;border-radius:6px;margin-bottom:4px;font-size:14px;background:#f8fafc}
  li.success{background:#dcfce7;color:#166534}
  li.error{background:#fee2e2;color:#991b1b}
  li.warning{background:#fef3c7;color:#92400e}
  form{display:flex;flex-direction:column;gap:16px}
  label{font-weight:500;font-size:14px}
  input,select{padding:10px 14px;border:1px solid #e2e8f0;border-radius:8px;font-size:15px;font-family:inherit;width:100%}
  input:focus{outline:none;border-color:#2563eb}
  .row{display:grid;grid-template-columns:1fr 1fr;gap:16px}
  button{padding:12px 24px;background:#2563eb;color:#fff;border:none;border-radius:8px;
         font-size:16px;font-weight:600;cursor:pointer}
  button:hover{background:#1d4ed8}
  .error-box{background:#fee2e2;color:#991b1b;padding:16px;border-radius:8px;margin-bottom:16px}
  .success-box{background:#dcfce7;color:#166534;padding:16px;border-radius:8px;margin-bottom:16px}
  hr{border:none;border-top:1px solid #e2e8f0;margin:24px 0}
</style>
</head>
<body>
<div class="box">
  <h1>⚡ Установка eFix</h1>
  <p>Установщик версии $version. Заполните параметры подключения к БД.</p>
  $errorBox
  <ul>$out</ul>
  <hr>
  <form method="POST">
    <h2 style="font-size:18px">Подключение к базе данных</h2>
    <div class="row">
      <label>Хост <input type="text" name="db_host" value="localhost" required></label>
      <label>Порт <input type="number" name="db_port" value="3306" required></label>
    </div>
    <label>Название БД <input type="text" name="db_name" value="efix" required></label>
    <label>Пользователь <input type="text" name="db_user" value="root" required></label>
    <label>Пароль <input type="password" name="db_pass"></label>
    <hr>
    <h2 style="font-size:18px">Администратор</h2>
    <label>Логин <input type="text" name="admin_user" value="admin" required></label>
    <div class="row">
      <label>Пароль <input type="password" name="admin_pass" required></label>
      <label>Повторите <input type="password" name="admin_pass2" required></label>
    </div>
    <hr>
    <label>URL сайта <input type="text" name="app_url" value="http://localhost:8000"></label>
    <button type="submit">Установить</button>
  </form>
</div>
</body>
</html>
HTML;
    exit;
}

function renderWebSuccess(): never
{
    global $output;
    $out = $output;
    echo <<<HTML
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Установка eFix завершена</title>
<style>
  *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
  body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;
       background:#f1f5f9;color:#1e293b;display:flex;justify-content:center;padding:40px 20px}
  .box{max-width:720px;width:100%;background:#fff;border-radius:12px;padding:40px;
       box-shadow:0 1px 3px rgba(0,0,0,.1)}
  h1{font-size:28px;margin-bottom:8px;color:#166534}
  ul{list-style:none;margin-bottom:24px}
  li{padding:8px 12px;border-radius:6px;margin-bottom:4px;font-size:14px;background:#f8fafc}
  li.success{background:#dcfce7;color:#166534}
  .btn{padding:12px 24px;background:#2563eb;color:#fff;border:none;border-radius:8px;
       font-size:16px;font-weight:600;cursor:pointer;text-decoration:none;display:inline-block}
  .btn:hover{background:#1d4ed8}
  .warning{background:#fef3c7;padding:12px;border-radius:8px;margin:16px 0}
</style>
</head>
<body>
<div class="box">
  <h1>✓ Установка завершена</h1>
  <ul>$out</ul>
  <div class="warning">
    <strong>Важно:</strong> удалите файл <code>install.php</code> с сервера или
    переименуйте его в <code>install.php.locked</code> для безопасности.
  </div>
  <p><a class="btn" href="/">Перейти на сайт</a>
     <a class="btn" href="/admin" style="background:#64748b">Админ-панель</a></p>
</div>
</body>
</html>
HTML;
    exit;
}

// ─── Pre-checks ─────────────────────────────────────────────────────────

function checkRequirements(): void
{
    if (version_compare(PHP_VERSION, '7.4.0', '<')) {
        fail('Требуется PHP 7.4 или выше. Текущая версия: ' . PHP_VERSION);
    }
    out("PHP " . PHP_VERSION . " — OK", 'success');

    $required = ['pdo', 'pdo_mysql', 'json', 'mbstring'];
    foreach ($required as $ext) {
        if (!extension_loaded($ext)) {
            fail("Расширение $ext не установлено");
        }
        out("Расширение $ext — OK", 'success');
    }

    exec('git --version 2>&1', $gitOut, $gitCode);
    if ($gitCode !== 0) {
        fail('Git не найден. Установите git и попробуйте снова.');
    }
    out("Git: " . trim($gitOut[0] ?? ''), 'success');

    if (!is_writable(__DIR__)) {
        fail("Директория " . __DIR__ . " недоступна для записи");
    }
    out("Права записи — OK", 'success');
}

// ─── Git clone ──────────────────────────────────────────────────────────

function copyRecursive(string $src, string $dst): void
{
    if (is_dir($src)) {
        if (!is_dir($dst)) {
            mkdir($dst, 0755, true);
        }
        $items = scandir($src);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            copyRecursive($src . '/' . $item, $dst . '/' . $item);
        }
    } else {
        copy($src, $dst);
    }
}

function removeDir(string $dir): void
{
    if (!is_dir($dir)) {
        return;
    }
    $items = scandir($dir);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }
        $path = $dir . '/' . $item;
        if (is_dir($path)) {
            removeDir($path);
        } else {
            unlink($path);
        }
    }
    rmdir($dir);
}

function cloneRepo(string $targetDir): void
{
    if (is_dir($targetDir . '/.git')) {
        out("Репозиторий уже склонирован, пропускаем", 'warning');
        return;
    }

    $tmpDir = sys_get_temp_dir() . '/efix-clone-' . uniqid();

    out("Клонирование репозитория...", 'step');
    $cmd = sprintf('git clone --depth=1 %s %s 2>&1', escapeshellarg(EFIX_REPO), escapeshellarg($tmpDir));
    exec($cmd, $cloneOut, $cloneCode);

    if ($cloneCode !== 0) {
        removeDir($tmpDir);
        fail("Ошибка клонирования: " . implode("\n", $cloneOut));
    }

    $items = scandir($tmpDir);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..' || $item === '.git') {
            continue;
        }
        copyRecursive($tmpDir . '/' . $item, $targetDir . '/' . $item);
    }

    removeDir($tmpDir);

    out("Репозиторий склонирован", 'success');
}

// ─── Database ───────────────────────────────────────────────────────────

function connectDb(array $config): PDO
{
    $dsn = sprintf('mysql:host=%s;port=%s;charset=utf8mb4', $config['host'], $config['port']);
    $pdo = new PDO($dsn, $config['username'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$config['dbname']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `{$config['dbname']}`");

    return $pdo;
}

function runMigrations(PDO $pdo): void
{
    $migrationsDir = __DIR__ . '/migrations';
    if (!is_dir($migrationsDir)) {
        fail("Директория migrations не найдена");
    }

    $files = glob($migrationsDir . '/*.sql');
    sort($files);

    foreach ($files as $file) {
        $sql = file_get_contents($file);
        if ($sql === false || trim($sql) === '') {
            continue;
        }

        $statements = explode(';', $sql);
        foreach ($statements as $stmt) {
            $stmt = trim($stmt);
            if ($stmt !== '') {
                $pdo->exec($stmt);
            }
        }
        out("Миграция " . basename($file) . " — OK", 'success');
    }
}

function createAdmin(PDO $pdo, string $username, string $password): void
{
    $hash = password_hash($password, PASSWORD_BCRYPT);

    $check = $pdo->prepare("SELECT id FROM admins WHERE username = ?");
    $check->execute([$username]);

    if ($check->fetch()) {
        $pdo->prepare("UPDATE admins SET password_hash = ? WHERE username = ?")->execute([$hash, $username]);
        out("Пароль администратора обновлён", 'success');
    } else {
        $pdo->prepare("INSERT INTO admins (username, password_hash, role) VALUES (?, ?, 'admin')")
            ->execute([$username, $hash]);
        out("Администратор создан", 'success');
    }
}

// ─── Config ─────────────────────────────────────────────────────────────

function writeConfig(array $db, string $appUrl): void
{
    $debug = 'false';

    $config = <<<PHP
<?php

return [
    'db' => [
        'host' => '{$db['host']}',
        'port' => '{$db['port']}',
        'dbname' => '{$db['dbname']}',
        'username' => '{$db['username']}',
        'password' => '{$db['password']}',
        'charset' => 'utf8mb4',
    ],
    'app' => [
        'name' => 'eFix',
        'debug' => $debug,
        'url' => '$appUrl',
    ],
];

PHP;

    $path = __DIR__ . '/config/config.php';
    $backup = __DIR__ . '/config/config.php.install-backup';

    if (file_exists($path)) {
        rename($path, $backup);
    }

    if (file_put_contents($path, $config) === false) {
        fail("Не удалось записать config/config.php");
    }

    out("Конфигурация записана", 'success');
}

// ─── Permissions ────────────────────────────────────────────────────────

function setPermissions(): void
{
    $dirs = [
        __DIR__ . '/config',
        __DIR__ . '/public/uploads',
    ];

    foreach ($dirs as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $set = chmod($dir, 0755);
        if (!$set) {
            out("Не удалось установить права на $dir", 'warning');
        }
    }

    $files = [
        __DIR__ . '/config/config.php',
    ];

    foreach ($files as $file) {
        if (file_exists($file)) {
            chmod($file, 0640);
        }
    }

    out("Права доступа установлены", 'success');
}

// ─── CLI helpers ────────────────────────────────────────────────────────

function cliPrompt(string $label, string $default = '', bool $secret = false): string
{
    if ($default !== '') {
        $label .= " [$default]";
    }
    fwrite(STDOUT, "$label: ");

    if ($secret) {
        system('stty -echo 2>/dev/null || true');
        $value = trim(fgets(STDIN));
        system('stty echo 2>/dev/null || true');
        fwrite(STDOUT, "\n");
    } else {
        $value = trim(fgets(STDIN));
    }

    return $value !== '' ? $value : $default;
}

function cliRun(): void
{
    out("⚡ Установка eFix v" . EFIX_VERSION, 'step');
    out("", 'info');

    // Pre-checks
    checkRequirements();

    // Clone
    $targetDir = __DIR__;
    cloneRepo($targetDir);

    // DB
    out("", 'info');
    out("Настройка базы данных", 'step');

    $dbHost = cliPrompt('Хост БД', 'localhost');
    $dbPort = cliPrompt('Порт', '3306');
    $dbName = cliPrompt('Название БД', 'efix');
    $dbUser = cliPrompt('Пользователь БД', 'root');
    $dbPass = cliPrompt('Пароль БД', '', true);

    $dbConfig = [
        'host' => $dbHost,
        'port' => $dbPort,
        'dbname' => $dbName,
        'username' => $dbUser,
        'password' => $dbPass,
    ];

    out("Подключение к БД...", 'step');
    $pdo = connectDb($dbConfig);
    out("Подключение установлено", 'success');

    runMigrations($pdo);

    $adminUser = cliPrompt('Логин администратора', 'admin');
    $adminPass = '';
    while ($adminPass === '') {
        $adminPass = cliPrompt('Пароль администратора', '', true);
        if (strlen($adminPass) < 6) {
            out("Пароль должен быть минимум 6 символов", 'error');
            $adminPass = '';
        }
    }

    createAdmin($pdo, $adminUser, $adminPass);

    $appUrl = cliPrompt('URL сайта', 'http://localhost:8000');

    writeConfig($dbConfig, $appUrl);
    setPermissions();

    out("", 'info');
    out("✓ Установка завершена!", 'success');
    out("Удалите install.php с сервера.", 'warning');
}

// ─── Web handler ────────────────────────────────────────────────────────

function webRun(array $post): void
{
    checkRequirements();

    $targetDir = __DIR__;
    cloneRepo($targetDir);

    $dbConfig = [
        'host' => $post['db_host'] ?? 'localhost',
        'port' => $post['db_port'] ?? '3306',
        'dbname' => $post['db_name'] ?? 'efix',
        'username' => $post['db_user'] ?? 'root',
        'password' => $post['db_pass'] ?? '',
    ];

    out("Подключение к БД...", 'step');
    try {
        $pdo = connectDb($dbConfig);
        out("Подключение установлено", 'success');
    } catch (\Throwable $e) {
        fail("Ошибка подключения: " . $e->getMessage());
    }

    runMigrations($pdo);

    $adminUser = trim($post['admin_user'] ?? 'admin');
    $adminPass = $post['admin_pass'] ?? '';
    $adminPass2 = $post['admin_pass2'] ?? '';

    if ($adminPass !== $adminPass2) {
        fail('Пароли администратора не совпадают');
    }
    if (strlen($adminPass) < 6) {
        fail('Пароль администратора должен быть минимум 6 символов');
    }

    createAdmin($pdo, $adminUser, $adminPass);

    $appUrl = trim($post['app_url'] ?? 'http://localhost:8000');

    writeConfig($dbConfig, $appUrl);
    setPermissions();

    renderWebSuccess();
}

// ─── Entry ──────────────────────────────────────────────────────────────

if ($isCli) {
    if ($argc > 1 && in_array($argv[1], ['--help', '-h', 'help'])) {
        echo "Установщик eFix v" . EFIX_VERSION . "\n\n";
        echo "Использование:\n";
        echo "  php install.php                    Интерактивная установка\n";
        echo "  php install.php --help             Эта справка\n\n";
        exit(0);
    }
    cliRun();
} else {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        webRun($_POST);
    } else {
        renderWeb();
    }
}
