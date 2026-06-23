<?php
// ============================================================
// eFix — Standalone Installer (auto-downloads from GitHub)
// ============================================================
// 1. Upload ONLY install.php to your hosting via FTP
// 2. Open https://your-site.com/install.php in browser
// 3. Script will download project from GitHub and install it
// 4. DELETE install.php after successful installation
// ============================================================
// Repo: https://github.com/dottenv/eFix.git
// ============================================================

$step = $_GET['step'] ?? 'start';
$error = '';
$success = '';
$GITHUB_ZIP = 'https://github.com/dottenv/eFix/archive/main.zip';

// ---------- .htaccess template ----------
$HTACCESS_CONTENT = "DirectoryIndex index.php

<IfModule mod_rewrite.c>
RewriteEngine On

# Root -> index.php
RewriteRule ^$ index.php [L]

# Clean URLs: /install -> /install.php, /update -> /update.php (only if file exists)
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{DOCUMENT_ROOT}/$1.php -f
RewriteRule ^([a-zA-Z0-9_-]+)$ $1.php [L]

# All other requests -> index.php (front controller)
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]
</IfModule>
";

// ---------- ensure .htaccess exists ----------
if (!file_exists(__DIR__ . '/.htaccess')) {
    @file_put_contents(__DIR__ . '/.htaccess', $HTACCESS_CONTENT);
}

// ---------- helpers ----------
function check_ext($name) {
    return extension_loaded($name)
        ? '<span class="ok">&#10003;</span>'
        : '<span class="fail">&#10007;</span>';
}

function generate_secret() {
    return 'eFix-' . bin2hex(random_bytes(16));
}

function check_mod_rewrite() {
    if (function_exists('apache_get_modules')) {
        return in_array('mod_rewrite', apache_get_modules());
    }
    if (function_exists('phpinfo')) {
        ob_start();
        phpinfo(INFO_MODULES);
        $info = ob_get_clean();
        return strpos($info, 'mod_rewrite') !== false;
    }
    return null;
}

// ---------- required project files ----------
$PROJECT_FILES = [
    'config.php', 'database.php', 'helpers.php', 'hooks.php', 'render.php', 'index.php',
    'models/Admin.php', 'models/SiteContent.php', 'models/Service.php',
    'models/PriceItem.php', 'models/PartnerWorkshop.php', 'models/ContactRequest.php',
    'models/PageView.php', 'models/SearchQuery.php', 'models/IpLocation.php',
    'models/FormInteraction.php', 'models/MailConfig.php', 'models/MailTemplate.php',
    'models/AppSetting.php',
    'routes/main.php', 'routes/api.php', 'routes/admin.php',
    'templates/base.php', 'templates/index.php', 'templates/404.php',
    'static/css/style.css', 'static/js/main.js',
];

function check_files() {
    global $PROJECT_FILES;
    $missing = [];
    foreach ($PROJECT_FILES as $f) {
        if (!file_exists(__DIR__ . '/' . $f)) {
            $missing[] = $f;
        }
    }
    return $missing;
}

function rrmdir($dir) {
    if (!is_dir($dir)) return;
    $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
    $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
    foreach ($files as $f) {
        $f->isDir() ? @rmdir($f->getRealPath()) : @unlink($f->getRealPath());
    }
    @rmdir($dir);
}

function recurse_copy($src, $dst) {
    $dir = opendir($src);
    @mkdir($dst, 0755, true);
    while (($f = readdir($dir)) !== false) {
        if ($f === '.' || $f === '..') continue;
        $sp = $src . '/' . $f;
        $dp = $dst . '/' . $f;
        if (is_dir($sp)) {
            recurse_copy($sp, $dp);
        } else {
            copy($sp, $dp);
        }
    }
    closedir($dir);
}

// ---------- download from GitHub ----------
$download_step_done = false;
$missing_files = check_files();

if ($step === 'download' && !empty($missing_files)) {
    header('Content-Type: text/plain; charset=utf-8');
    echo "=== eFix: загрузка с GitHub ===\n\n";

    if (!extension_loaded('zip')) {
        die("ОШИБКА: требуется PHP-расширение zip. Включите его в хостинге.\n");
    }

    if (!ini_get('allow_url_fopen') && !function_exists('curl_init')) {
        die("ОШИБКА: нужен allow_url_fopen или curl для загрузки файлов.\n");
    }

    $tmp_zip = __DIR__ . '/_efix_tmp.zip';
    $tmp_dir = __DIR__ . '/_efix_tmp';

    echo "1. Скачиваю архив с GitHub...\n";

    $zip_data = false;
    if (ini_get('allow_url_fopen')) {
        $zip_data = @file_get_contents($GITHUB_ZIP);
    }
    if ($zip_data === false && function_exists('curl_init')) {
        $ch = curl_init($GITHUB_ZIP);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        $zip_data = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($http_code !== 200) $zip_data = false;
    }

    if ($zip_data === false) {
        @unlink($tmp_zip);
        die("ОШИБКА: не удалось скачать архив с GitHub. Проверьте, что сайт доступа к github.com.\n");
    }

    file_put_contents($tmp_zip, $zip_data);
    echo "   Архив скачан (" . round(strlen($zip_data) / 1024) . " KB)\n";

    echo "2. Распаковываю...\n";
    @rrmdir($tmp_dir);
    @mkdir($tmp_dir, 0755, true);

    $zip = new ZipArchive();
    if ($zip->open($tmp_zip) !== true) {
        @unlink($tmp_zip);
        die("ОШИБКА: не удалось открыть ZIP-архив.\n");
    }
    $zip->extractTo($tmp_dir);
    $zip->close();
    @unlink($tmp_zip);

    echo "3. Копирую файлы в корень сайта...\n";

    // Find the extracted directory (eFix-main/)
    $entries = scandir($tmp_dir);
    $extracted = null;
    foreach ($entries as $e) {
        if ($e !== '.' && $e !== '..' && is_dir($tmp_dir . '/' . $e)) {
            $extracted = $tmp_dir . '/' . $e;
            break;
        }
    }

    if (!$extracted) {
        @rrmdir($tmp_dir);
        die("ОШИБКА: не найдена папка с файлами в архиве.\n");
    }

    // Copy everything except .git and install.php to target
    $it = new RecursiveDirectoryIterator($extracted, RecursiveDirectoryIterator::SKIP_DOTS);
    $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::LEAVES_ONLY);
    $copied = 0;
    foreach ($files as $f) {
        $rel = substr($f->getPathname(), strlen($extracted) + 1);
        // Skip .git
        if (str_starts_with($rel, '.git')) continue;
        // Skip install.php from repo (ours is better)
        if ($rel === 'install.php' || $rel === 'deploy/docker-compose.yml' || $rel === 'deploy/setup.sh' || $rel === 'deploy/nginx.conf' || $rel === 'deploy/nginx-ssl.conf') continue;

        $target = __DIR__ . '/' . $rel;
        @mkdir(dirname($target), 0755, true);
        copy($f->getPathname(), $target);
        $copied++;
    }

    @rrmdir($tmp_dir);

    echo "   Скопировано $copied файлов\n";

    $still_missing = check_files();
    if (empty($still_missing)) {
        $download_step_done = true;
        echo "\n=== Готово! Все файлы проекта на месте. ===\n";
        echo "Переадресация на страницу установки...\n";
        echo '<script>setTimeout(function(){ window.location.href="install.php"; }, 1500);</script>';
        exit;
    } else {
        echo "\n=== ВНИМАНИЕ: некоторые файлы всё ещё отсутствуют: ===\n";
        foreach ($still_missing as $mf) {
            echo " - $mf\n";
        }
        echo "\nПопробуйте ещё раз или загрузите вручную через FTP.\n";
        exit;
    }
}

// ---------- checks ----------
$missing_files = check_files();
$files_ok = empty($missing_files);
$php_ok = version_compare(PHP_VERSION, '8.0', '>=');
$exts = ['pdo', 'pdo_sqlite', 'mbstring', 'json', 'session'];
$ext_ok = true;
foreach ($exts as $e) { if (!extension_loaded($e)) $ext_ok = false; }
$root_writable = is_writable(__DIR__);
$htaccess_exists = file_exists(__DIR__ . '/.htaccess');
$db_exists = file_exists(__DIR__ . '/efix.db');
$zip_available = extension_loaded('zip');

// ---------- process install ----------
if ($step === 'install' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin_user = trim($_POST['admin_user'] ?? '');
    $admin_pass = $_POST['admin_pass'] ?? '';
    $admin_pass2 = $_POST['admin_pass2'] ?? '';
    $secret = trim($_POST['secret'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $site_name = trim($_POST['site_name'] ?? 'eFix');
    $address = trim($_POST['address'] ?? '');

    if (!$files_ok) {
        $error = 'Отсутствуют файлы проекта. Нажмите "Скачать с GitHub" выше.';
    } elseif (!$admin_user || !$admin_pass) {
        $error = 'Заполните имя администратора и пароль.';
    } elseif ($admin_pass !== $admin_pass2) {
        $error = 'Пароли не совпадают.';
    } elseif (strlen($admin_pass) < 4) {
        $error = 'Пароль должен быть минимум 4 символа.';
    } elseif (!$secret) {
        $secret = generate_secret();
    }

    if (!$error) {
        try {
            $env = "# eFix configuration\n";
            $env .= "SECRET_KEY={$secret}\n";
            $env .= "DATABASE_URL=sqlite:" . __DIR__ . "/efix.db\n";
            if ($phone) $env .= "SITE_PHONE={$phone}\n";
            file_put_contents(__DIR__ . '/.env', $env);

            require_once __DIR__ . '/config.php';
            require_once __DIR__ . '/database.php';
            require_once __DIR__ . '/helpers.php';
            require_once __DIR__ . '/hooks.php';
            require_once __DIR__ . '/render.php';
            require_once __DIR__ . '/models/Admin.php';
            require_once __DIR__ . '/models/SiteContent.php';
            require_once __DIR__ . '/models/Service.php';
            require_once __DIR__ . '/models/PriceItem.php';
            require_once __DIR__ . '/models/PartnerWorkshop.php';
            require_once __DIR__ . '/models/ContactRequest.php';
            require_once __DIR__ . '/models/PageView.php';
            require_once __DIR__ . '/models/SearchQuery.php';
            require_once __DIR__ . '/models/IpLocation.php';
            require_once __DIR__ . '/models/FormInteraction.php';
            require_once __DIR__ . '/models/MailConfig.php';
            require_once __DIR__ . '/models/MailTemplate.php';
            require_once __DIR__ . '/models/AppSetting.php';

            $db = Database::getInstance();
            $db->initSchema();

            $existing = Admin::getByUsername($admin_user);
            if (!$existing) {
                Admin::create($admin_user, $admin_pass);
            }

            $defaults = [
                'site_name' => $site_name,
                'phone' => $phone ?: '+7 (999) 999-99-99',
                'email' => $email ?: 'info@efix.ru',
                'address_short' => $address ?: 'Новосибирск, выезд по городу',
                'work_hours' => 'Ежедневно с 09:00 до 21:00',
                'meta_title' => $site_name . ' — Выездной сервисный центр в Новосибирске',
                'meta_description' => $site_name . ' — ремонт цифровой техники в Новосибирске с выездом. Телефоны, планшеты, ноутбуки, ПК. Бесплатная диагностика, гарантия.',
                'hero_badge' => 'Выездной сервисный центр',
                'hero_title' => 'Ремонтируем цифровую технику —<br>заберём, починим, вернём',
                'hero_subtitle' => 'Телефоны, планшеты, ноутбуки, ПК. Бесплатная диагностика, гарантия до 1 года. Приедем, заберём устройство и вернём обратно после ремонта.',
                'cta_button_text' => 'Вызвать мастера',
                'prices_button_text' => 'Смотреть цены',
                'footer_description' => 'Выездной сервисный центр в Новосибирске. Ремонтируем цифровую технику с заботой о вашем времени.',
                'copyright' => '(c) ' . date('Y') . ' ' . $site_name . '. Все права защищены.',
            ];
            foreach ($defaults as $key => $value) {
                SiteContent::set($key, $value);
            }

            AppSetting::set('site_name', $site_name);
            AppSetting::set('default_email', $email ?: '');

            @chmod(__DIR__ . '/.env', 0644);
            @chmod(__DIR__ . '/.htaccess', 0644);
            if (file_exists(__DIR__ . '/efix.db')) {
                @chmod(__DIR__ . '/efix.db', 0644);
            }

            // Create index.html fallback
            if (!file_exists(__DIR__ . '/index.html')) {
                $fallback = '<!DOCTYPE html><html lang="ru"><head><meta charset="UTF-8"><meta http-equiv="refresh" content="0;url=index.php"><title>' . htmlspecialchars($site_name) . '</title></head><body><p><a href="index.php">Перейти на сайт</a></p></body></html>';
                file_put_contents(__DIR__ . '/index.html', $fallback);
            }

            $success = 'Установка завершена успешно!';
        } catch (Exception $e) {
            $error = 'Ошибка при установке: ' . $e->getMessage();
        } catch (Throwable $e) {
            $error = 'Ошибка при установке: ' . $e->getMessage();
        }
    }
}
?><!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Установка eFix</title>
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
:root {
    --primary: #0B2447;
    --accent: #FF6B35;
    --bg: #F5F7FA;
    --surface: #FFFFFF;
    --text: #1A1A2E;
    --text-muted: #6B7280;
    --border: #E5E7EB;
    --success: #10B981;
    --danger: #EF4444;
    --radius: 12px;
}
body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif;
    background: var(--bg);
    color: var(--text);
    line-height: 1.6;
    padding: 40px 20px;
}
.container { max-width: 720px; margin: 0 auto; }
.card {
    background: var(--surface);
    border-radius: var(--radius);
    box-shadow: 0 4px 24px rgba(0,0,0,.08);
    padding: 40px;
    margin-bottom: 24px;
}
h1 {
    font-size: 28px;
    font-weight: 800;
    color: var(--primary);
    margin-bottom: 8px;
}
h1 span { color: var(--accent); }
p.subtitle { color: var(--text-muted); margin-bottom: 32px; }
h2 {
    font-size: 18px;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 16px;
    padding-bottom: 8px;
    border-bottom: 2px solid var(--border);
}
.check-list { list-style: none; margin-bottom: 24px; }
.check-list li {
    padding: 8px 0;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
    flex-wrap: wrap;
}
.ok { color: var(--success); font-weight: bold; }
.fail { color: var(--danger); font-weight: bold; }
.label { color: var(--text-muted); min-width: 140px; }
.form-group { margin-bottom: 20px; }
.form-group label {
    display: block;
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 6px;
    color: var(--primary);
}
.form-group .hint {
    font-size: 12px;
    color: var(--text-muted);
    margin-top: 4px;
}
.form-group .field-error {
    font-size: 12px;
    color: var(--danger);
    margin-top: 4px;
    display: none;
}
.form-group.invalid input { border-color: var(--danger); }
.form-group.invalid .field-error { display: block; }
.form-group.valid input { border-color: var(--success); }
input, select, textarea {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid var(--border);
    border-radius: 8px;
    font-size: 15px;
    font-family: inherit;
    transition: border-color .2s;
    background: var(--surface);
}
input:focus, select:focus, textarea:focus {
    outline: none;
    border-color: var(--accent);
}
.password-wrap {
    position: relative;
}
.password-wrap input { padding-right: 44px; }
.toggle-pass {
    position: absolute;
    right: 8px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    padding: 8px;
    color: var(--text-muted);
    font-size: 18px;
    line-height: 1;
}
.toggle-pass:hover { color: var(--text); }
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 14px 32px;
    font-size: 16px;
    font-weight: 700;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all .2s;
    background: var(--accent);
    color: #fff;
    width: 100%;
}
.btn:hover { opacity: .9; transform: translateY(-1px); }
.btn:disabled { opacity: .5; cursor: not-allowed; }
.btn--secondary {
    background: var(--primary);
}
.btn--outline {
    background: transparent;
    color: var(--primary);
    border: 2px solid var(--primary);
}
.btn--outline:hover { background: var(--primary); color: #fff; }
.alert {
    padding: 16px 20px;
    border-radius: 8px;
    margin-bottom: 24px;
    font-size: 14px;
    font-weight: 600;
}
.alert-error { background: #FEF2F2; color: var(--danger); border: 1px solid #FECACA; }
.alert-success { background: #F0FDF4; color: #166534; border: 1px solid #BBF7D0; }
.alert-info {
    background: #EFF6FF;
    color: #1E40AF;
    border: 1px solid #BFDBFE;
}
.row { display: flex; gap: 16px; }
.row > * { flex: 1; }
@media (max-width: 600px) { .row { flex-direction: column; } }
.success-links {
    list-style: none;
    margin: 20px 0;
}
.success-links li { padding: 8px 0; }
.success-links a {
    color: var(--accent);
    font-weight: 600;
    text-decoration: none;
}
.success-links a:hover { text-decoration: underline; }
.warning {
    background: #FFFBEB;
    border: 1px solid #FDE68A;
    border-radius: 8px;
    padding: 12px 16px;
    font-size: 13px;
    color: #92400E;
    margin-top: 16px;
}
.footer-note {
    text-align: center;
    color: var(--text-muted);
    font-size: 13px;
    margin-top: 32px;
}
code {
    background: #F3F4F6;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 13px;
}
.missing-list {
    background: #FEF2F2;
    border: 1px solid #FECACA;
    border-radius: 8px;
    padding: 16px;
    margin-top: 16px;
    font-size: 13px;
    max-height: 200px;
    overflow-y: auto;
}
.missing-list code { display: inline-block; margin: 2px 0; }
.download-btn-wrap { text-align: center; margin: 20px 0; }
</style>
</head>
<body>
<div class="container">
    <div class="card" style="text-align:center;padding:24px">
        <h1>e<span>Fix</span></h1>
        <p class="subtitle" style="margin-bottom:0">Установка сервисного центра</p>
    </div>

    <?php if ($error): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif ?>

    <?php if ($success): ?>
    <div class="card" style="text-align:center">
        <h1 style="font-size:24px;margin-bottom:12px"><?= htmlspecialchars($success) ?></h1>
        <p style="color:var(--text-muted);margin-bottom:8px">
            Администратор <strong><?= htmlspecialchars($admin_user) ?></strong> создан.
        </p>
        <ul class="success-links">
            <li><a href="index.php">Открыть сайт</a></li>
            <li><a href="admin/login">Войти в админ-панель</a></li>
        </ul>
        <div class="warning">
            <strong>ВАЖНО:</strong> Удалите файл <code>install.php</code> с сервера!
        </div>
    </div>
    <?php else: ?>

    <!-- Directory diagnostic -->
    <?php
        $doc_root = $_SERVER['DOCUMENT_ROOT'] ?? 'неизвестно';
        $cur_dir = __DIR__;
        $roots_match = realpath($doc_root) === realpath($cur_dir);
    ?>
    <div class="card">
        <h2>Диагностика</h2>
        <ul class="check-list">
            <li>
                <?= $roots_match ? '<span class="ok">&#10003;</span>' : '<span class="fail">&#10007;</span>' ?>
                <span class="label">Корень сайта (DocumentRoot)</span>
                <code style="font-size:13px"><?= htmlspecialchars($doc_root) ?></code>
            </li>
            <li>
                <?= $roots_match ? '<span class="ok">&#10003;</span>' : '<span class="fail">&#10007;</span>' ?>
                <span class="label">Текущая папка (__DIR__)</span>
                <code style="font-size:13px"><?= htmlspecialchars($cur_dir) ?></code>
            </li>
        </ul>
        <?php if (!$roots_match): ?>
            <div class="alert alert-error" style="margin-top:12px">
                <strong>Папки не совпадают.</strong> Скорее всего, ты залил файлы не в ту директорию.<br><br>
                Reg.ru: зайди в панель управления хостингом → раздел «Сайты» → проверь поле «Корневая папка» (Document Root).<br>
                Обычно это <code>www</code> или <code>public_html</code> внутри твоего FTP-аккаунта.<br><br>
                Залей файлы проекта в папку: <code><?= htmlspecialchars(rtrim($doc_root, '/\\')) ?></code>
            </div>
        <?php elseif ($files_ok): ?>
            <div class="alert alert-success" style="margin-top:12px">
                Файлы в правильной папке.
            </div>
            <?php if ($rewrite_ok === false && !empty($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'], 'nginx') !== false): ?>
            <div class="alert alert-error" style="margin-top:12px">
                <strong>Сервер использует nginx.</strong> На Reg.ru .htaccess не работает с nginx.<br><br>
                Чтобы сайт открывался по адресу <code>/</code>:
                <ol style="margin:8px 0 0 20px">
                    <li>Зайди в <strong>панель управления Reg.ru</strong> → Хостинг → Управление сайтом</li>
                    <li>Найди раздел <strong>«Веб-сервер»</strong> — переключи на <strong>Apache</strong> (если есть выбор)</li>
                    <li>Или найди <strong>«Документ по умолчанию»</strong> — добавь <code>index.php</code> первым в списке</li>
                    <li>Или в разделе <strong>«ЧПУ» / «Rewrite»</strong> добавь правило: все запросы на <code>index.php</code></li>
                </ol>
                После этого <code>/</code> будет открывать сайт.
            </div>
            <?php endif ?>
        <?php endif ?>
    </div>

    <!-- System check -->
    <div class="card">
        <h2>Проверка системы</h2>
        <ul class="check-list">
            <li>
                <?= check_ext('pdo') ?>
                <span class="label">PHP PDO</span>
                <?php if (!extension_loaded('pdo')): ?><span class="fail">Необходим для работы с БД</span><?php endif ?>
            </li>
            <li>
                <?= check_ext('pdo_sqlite') ?>
                <span class="label">PDO SQLite</span>
                <?php if (!extension_loaded('pdo_sqlite')): ?><span class="fail">Необходим для SQLite</span><?php endif ?>
            </li>
            <li>
                <?= check_ext('mbstring') ?>
                <span class="label">mbstring</span>
                <?php if (!extension_loaded('mbstring')): ?><span class="fail">Необходим для работы с UTF-8</span><?php endif ?>
            </li>
            <li>
                <?= check_ext('json') ?>
                <span class="label">JSON</span>
            </li>
            <li>
                <?= check_ext('session') ?>
                <span class="label">Session</span>
            </li>
            <li>
                <?= check_ext('zip') ?>
                <span class="label">PHP Zip</span>
                <?php if (!extension_loaded('zip')): ?><span class="fail">Нужен для автозагрузки с GitHub</span><?php endif ?>
            </li>
            <li>
                <?= $php_ok ? '<span class="ok">&#10003;</span>' : '<span class="fail">&#10007;</span>' ?>
                <span class="label">PHP >= 8.0</span>
                <span style="color:var(--text-muted)">(текущая: <?= PHP_VERSION ?>)</span>
                <?php if (!$php_ok): ?><span class="fail">Требуется PHP 8.0+</span><?php endif ?>
            </li>
            <li>
                <?= $root_writable ? '<span class="ok">&#10003;</span>' : '<span class="fail">&#10007;</span>' ?>
                <span class="label">Права на запись</span>
                <?php if (!$root_writable): ?><span class="fail">Папка недоступна для записи</span><?php endif ?>
            </li>
            <li>
                <?php
                    $rewrite_ok = check_mod_rewrite();
                    $rewrite_mark = $rewrite_ok === true ? '<span class="ok">&#10003;</span>' : ($rewrite_ok === false ? '<span class="fail">&#10007;</span>' : '<span class="ok">? (неизвестно)</span>');
                    $rewrite_text = $rewrite_ok === true ? 'Работает' : ($rewrite_ok === false ? 'Не обнаружен' : 'Не удалось проверить');
                ?>
                <?= $rewrite_mark ?>
                <span class="label">mod_rewrite</span>
                <span style="color:var(--text-muted)">(<?= $rewrite_text ?>)</span>
                <?php if ($rewrite_ok === false): ?>
                    <span class="fail">.htaccess будет создан, но может не работать — обратитесь в поддержку хостинга</span>
                <?php endif ?>
            </li>
            <li>
                <?php if ($files_ok): ?>
                    <span class="ok">&#10003;</span>
                <?php else: ?>
                    <span class="fail">&#10007;</span>
                <?php endif ?>
                <span class="label">Файлы проекта</span>
                <?php if ($files_ok): ?>
                    <span style="color:var(--text-muted)">Все файлы на месте</span>
                <?php else: ?>
                    <span class="fail">Не загружены (<?= count($missing_files) ?> отсутствует)</span>
                <?php endif ?>
            </li>
        </ul>

        <?php if (!$files_ok): ?>
            <?php if ($zip_available): ?>
                <div class="alert alert-info" style="margin-top:16px">
                    Файлы проекта не найдены. Нажмите кнопку ниже, чтобы скачать их с GitHub.
                </div>
                <div class="download-btn-wrap">
                    <a href="?step=download" class="btn btn--secondary" style="width:auto;display:inline-flex;padding:14px 40px">
                        Скачать проект с GitHub
                    </a>
                </div>
            <?php else: ?>
                <div class="missing-list">
                    <strong>Отсутствующие файлы:</strong><br>
                    <?php foreach ($missing_files as $mf): ?>
                        <code><?= htmlspecialchars($mf) ?></code><br>
                    <?php endforeach ?>
                    <br>
                    Расширение PHP Zip не найдено. Загрузите файлы проекта через FTP вручную
                    или включите php-zip в настройках хостинга.
                </div>
            <?php endif ?>
        <?php endif ?>

        <?php if ($db_exists): ?>
        <div class="warning">
            Файл базы данных <code>efix.db</code> уже существует. Установка перезапишет его.
        </div>
        <?php endif ?>

        <?php if (! $php_ok || ! $ext_ok || ! $root_writable): ?>
        <div class="alert alert-error">
            Некоторые требования не выполнены. Установка невозможна.
        </div>
        <?php endif ?>
    </div>

    <?php if ($php_ok && $ext_ok && $root_writable && $files_ok): ?>
    <!-- Configuration form -->
    <div class="card">
        <h2>Настройка сайта</h2>
        <form method="post" action="?step=install" id="installForm">
            <div class="form-group">
                <label for="site_name">Название сайта</label>
                <input type="text" id="site_name" name="site_name" value="eFix" placeholder="eFix">
            </div>

            <div class="row">
                <div class="form-group" id="fg-phone">
                    <label for="phone">Телефон</label>
                    <input type="text" id="phone" name="phone" value="+7 (383) 000-00-00" placeholder="+7 (383) 000-00-00"
                           data-validate="phone">
                    <div class="field-error">Введите корректный номер телефона</div>
                </div>
                <div class="form-group" id="fg-email">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="info@efix.ru" placeholder="info@efix.ru"
                           data-validate="email">
                    <div class="field-error">Введите корректный email</div>
                </div>
            </div>

            <div class="form-group">
                <label for="address">Адрес</label>
                <input type="text" id="address" name="address" value="Новосибирск, выезд по городу" placeholder="Новосибирск, выезд по городу">
                <div class="hint">Короткий адрес для отображения в шапке и подвале</div>
            </div>

            <h2 style="margin-top:24px">Администратор</h2>

            <div class="form-group" id="fg-user">
                <label for="admin_user">Имя пользователя *</label>
                <input type="text" id="admin_user" name="admin_user" required placeholder="admin"
                       data-validate="required" data-name="Имя пользователя">
                <div class="field-error">Обязательное поле</div>
            </div>

            <div class="row">
                <div class="form-group" id="fg-pass">
                    <label for="admin_pass">Пароль *</label>
                    <div class="password-wrap">
                        <input type="password" id="admin_pass" name="admin_pass" required minlength="4" placeholder="минимум 4 символа"
                               data-validate="password">
                        <button type="button" class="toggle-pass" data-toggle="admin_pass" aria-label="Показать пароль" tabindex="-1">&#128065;</button>
                    </div>
                    <div class="field-error">Минимум 4 символа</div>
                </div>
                <div class="form-group" id="fg-pass2">
                    <label for="admin_pass2">Повторите пароль *</label>
                    <div class="password-wrap">
                        <input type="password" id="admin_pass2" name="admin_pass2" required minlength="4" placeholder="подтверждение"
                               data-validate="password_match">
                        <button type="button" class="toggle-pass" data-toggle="admin_pass2" aria-label="Показать пароль" tabindex="-1">&#128065;</button>
                    </div>
                    <div class="field-error">Пароли не совпадают</div>
                </div>
            </div>

            <div class="form-group">
                <label for="secret">Секретный ключ</label>
                <input type="text" id="secret" name="secret" placeholder="оставьте пустым для автогенерации">
                <div class="hint">Используется для шифрования сессий. Оставьте пустым — сгенерируется автоматически.</div>
            </div>

            <button type="submit" id="install-btn" class="btn">
                Установить eFix
            </button>
        </form>
    </div>
    <?php endif ?>

    <?php endif ?>

    <div class="footer-note">
        eFix — выездной сервисный центр<br>
        <a href="https://github.com/dottenv/eFix" target="_blank" style="color:var(--accent)">GitHub</a>
    </div>
</div>

<script>
(function() {
    // Password show/hide
    document.querySelectorAll('.toggle-pass').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var input = document.getElementById(this.getAttribute('data-toggle'));
            if (!input) return;
            input.type = (input.type === 'password') ? 'text' : 'password';
        });
    });

    // Real-time validation
    var form = document.getElementById('installForm');
    if (!form) return;

    var validators = {
        required: function(input) {
            return input.value.trim().length > 0;
        },
        email: function(input) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value.trim());
        },
        phone: function(input) {
            var digits = input.value.replace(/\D/g, '');
            return digits.length >= 10;
        },
        password: function(input) {
            return input.value.length >= 4;
        },
        password_match: function(input) {
            var pass = document.getElementById('admin_pass');
            return pass && input.value === pass.value;
        }
    };

    function validateField(input) {
        var rule = input.getAttribute('data-validate');
        if (!rule) return true;
        var fn = validators[rule];
        if (!fn) return true;
        var ok = fn(input);
        var group = input.closest('.form-group');
        if (!group) return ok;
        if (ok) {
            group.classList.remove('invalid');
            group.classList.add('valid');
        } else {
            group.classList.add('invalid');
            group.classList.remove('valid');
        }
        return ok;
    }

    form.querySelectorAll('[data-validate]').forEach(function(input) {
        input.addEventListener('input', function() { validateField(this); });
        input.addEventListener('blur', function() { validateField(this); });
    });

    var pass1 = document.getElementById('admin_pass');
    var pass2 = document.getElementById('admin_pass2');
    if (pass1 && pass2) {
        pass1.addEventListener('input', function() {
            if (pass2.value) validateField(pass2);
        });
    }

    form.addEventListener('submit', function(e) {
        var allOk = true;
        form.querySelectorAll('[data-validate]').forEach(function(input) {
            if (!validateField(input)) allOk = false;
        });
        if (!allOk) {
            e.preventDefault();
            var first = form.querySelector('.form-group.invalid input, .form-group.invalid .password-wrap input');
            if (first) first.focus();
            return;
        }
        document.getElementById('install-btn').disabled = true;
        document.getElementById('install-btn').textContent = 'Установка...';
    });
})();
</script>
</body>
</html>
