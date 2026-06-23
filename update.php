<?php
// ============================================================
// eFix — Updater (pull latest from GitHub)
// ============================================================
// Run: https://your-site.com/update.php
// ============================================================

$step = $_GET['step'] ?? 'start';
$error = '';
$output = [];
$GITHUB_ZIP = 'https://github.com/dottenv/eFix/archive/main.zip';

// Files to NEVER overwrite during update
$PRESERVE = [
    '.env', 'efix.db', 'efix.db-journal', 'install.php', 'update.php',
    'index.html',
];

// .htaccess template (applied if missing)
$HTACCESS_TEMPLATE = "DirectoryIndex index.php

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

// ---------- helpers ----------
function rrmdir($dir) {
    if (!is_dir($dir)) return;
    $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
    $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
    foreach ($files as $f) {
        $f->isDir() ? @rmdir($f->getRealPath()) : @unlink($f->getRealPath());
    }
    @rmdir($dir);
}

function format_size($bytes) {
    if ($bytes > 1024*1024) return round($bytes/1024/1024, 1) . ' MB';
    if ($bytes > 1024) return round($bytes/1024, 1) . ' KB';
    return $bytes . ' B';
}

// ---------- process ----------
if ($step === 'run') {
    header('Content-Type: text/plain; charset=utf-8');
    echo "=== eFix Updater ===\n\n";

    if (!extension_loaded('zip')) {
        die("Ошибка: требуется PHP-расширение zip.\n");
    }

    $tmp_zip = __DIR__ . '/_efix_update.zip';
    $tmp_dir = __DIR__ . '/_efix_update_tmp';

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
        die("Ошибка: не удалось скачать архив с GitHub.\n");
    }

    file_put_contents($tmp_zip, $zip_data);
    echo "   Загружено " . format_size(strlen($zip_data)) . "\n";

    echo "2. Распаковываю...\n";
    @rrmdir($tmp_dir);
    @mkdir($tmp_dir, 0755, true);

    $zip = new ZipArchive();
    if ($zip->open($tmp_zip) !== true) {
        @unlink($tmp_zip);
        die("Ошибка: не удалось открыть ZIP-архив.\n");
    }
    $zip->extractTo($tmp_dir);
    $zip->close();
    @unlink($tmp_zip);

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
        die("Ошибка: не найдена папка с файлами в архиве.\n");
    }

    echo "3. Сравниваю файлы...\n";

    $new_files = [];
    $updated_files = [];
    $skipped_files = [];
    $deleted_files = [];

    // Scan all files in the extracted repo
    $it = new RecursiveDirectoryIterator($extracted, RecursiveDirectoryIterator::SKIP_DOTS);
    $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::LEAVES_ONLY);

    foreach ($files as $f) {
        $rel = substr($f->getPathname(), strlen($extracted) + 1);
        $rel = str_replace('\\', '/', $rel);

        // Skip git and deploy
        if (str_starts_with($rel, '.git')) continue;
        if (str_starts_with($rel, 'deploy/')) continue;

        // Check preserve list
        if (in_array($rel, $PRESERVE)) {
            $skipped_files[] = $rel;
            continue;
        }

        $target = __DIR__ . '/' . $rel;

        if (!file_exists($target)) {
            $new_files[] = $rel;
        } elseif (md5_file($f->getPathname()) !== md5_file($target)) {
            $updated_files[] = $rel;
        }

        @mkdir(dirname($target), 0755, true);
        copy($f->getPathname(), $target);
    }

    @rrmdir($tmp_dir);

    echo "\n";

    if (!empty($new_files)) {
        echo "   Новые файлы (" . count($new_files) . "):\n";
        foreach ($new_files as $nf) echo "     + $nf\n";
    }
    if (!empty($updated_files)) {
        echo "   Обновлено файлов (" . count($updated_files) . "):\n";
        foreach ($updated_files as $uf) echo "     ~ $uf\n";
    }
    if (!empty($skipped_files)) {
        echo "   Пропущено (защищено):\n";
        foreach ($skipped_files as $sf) echo "     - $sf\n";
    }

    if (empty($new_files) && empty($updated_files)) {
        echo "\n   Всё актуально, изменений нет.\n";
    }

    // Re-run DB schema (adds new tables if any)
    echo "\n4. Обновляю структуру БД...\n";
    try {
        require_once __DIR__ . '/app/Config.php';
        require_once __DIR__ . '/app/Database.php';
        $db = Database::getInstance();
        $db->initSchema();
        echo "   База данных в актуальном состоянии.\n";
    } catch (Exception $e) {
        echo "   Ошибка БД: " . $e->getMessage() . "\n";
    }

    // Ensure .htaccess has root redirect
    echo "5. Проверяю .htaccess...\n";
    $ht_file = __DIR__ . '/.htaccess';
    $needs_ht = !file_exists($ht_file);
    if (!$needs_ht) {
        $content = file_get_contents($ht_file);
        if (strpos($content, 'RewriteRule ^$ index.php') === false) {
            $needs_ht = true;
        }
    }
    if ($needs_ht) {
        file_put_contents($ht_file, $HTACCESS_TEMPLATE);
        echo "   .htaccess обновлён (добавлен редирект корня на index.php).\n";
    } else {
        echo "   .htaccess в порядке.\n";
    }

    echo "\n=== Готово! ===\n";
    exit;
}

// ---------- current state ----------
$files_count = 0;
$db_version = '?';
if (file_exists(__DIR__ . '/efix.db')) {
    $db_version = format_size(filesize(__DIR__ . '/efix.db'));
}
// Count project files
$PROJECT_FILES = [
    'index.php', 'install.php', 'update.php',
    'app/Config.php', 'app/Database.php', 'app/Helpers.php',
    'app/Hooks.php', 'app/Render.php', 'app/Router.php',
    'app/Models/Admin.php', 'app/Models/SiteContent.php', 'app/Models/Service.php',
    'app/Models/PriceItem.php', 'app/Models/PartnerWorkshop.php', 'app/Models/ContactRequest.php',
    'app/Models/PageView.php', 'app/Models/SearchQuery.php', 'app/Models/IpLocation.php',
    'app/Models/FormInteraction.php', 'app/Models/MailConfig.php', 'app/Models/MailTemplate.php',
    'app/Models/AppSetting.php',
    'modules/install/common.php', 'modules/install/init.php',
    'modules/install/views/layout.php',
    'modules/install/actions/download.php', 'modules/install/actions/install.php',
    'routes/main.php', 'routes/api.php', 'routes/admin.php',
    'templates/base.php', 'templates/index.php', 'templates/404.php',
    'static/css/style.css', 'static/js/main.js',
];
$present = 0;
foreach ($PROJECT_FILES as $f) {
    if (file_exists(__DIR__ . '/' . $f)) $present++;
}
$total = count($PROJECT_FILES);
$all_ok = ($present === $total);
$zip_available = extension_loaded('zip');

?><!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Обновление eFix</title>
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
.container { max-width: 640px; margin: 0 auto; }
.card {
    background: var(--surface);
    border-radius: var(--radius);
    box-shadow: 0 4px 24px rgba(0,0,0,.08);
    padding: 32px;
    margin-bottom: 24px;
}
h1 {
    font-size: 28px;
    font-weight: 800;
    color: var(--primary);
    margin-bottom: 8px;
}
h1 span { color: var(--accent); }
p.subtitle { color: var(--text-muted); margin-bottom: 24px; }
h2 {
    font-size: 18px;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 16px;
    padding-bottom: 8px;
    border-bottom: 2px solid var(--border);
}
ul { list-style: none; }
li { padding: 6px 0; display: flex; align-items: center; gap: 8px; font-size: 14px; }
.ok { color: var(--success); font-weight: bold; }
.fail { color: var(--danger); font-weight: bold; }
.label { color: var(--text-muted); min-width: 100px; }
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
.alert {
    padding: 14px 18px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
}
.alert-error { background: #FEF2F2; color: var(--danger); border: 1px solid #FECACA; }
.alert-success { background: #F0FDF4; color: #166534; border: 1px solid #BBF7D0; }
.alert-info { background: #EFF6FF; color: #1E40AF; border: 1px solid #BFDBFE; }
.footer-note {
    text-align: center;
    color: var(--text-muted);
    font-size: 13px;
    margin-top: 32px;
}
code { background: #F3F4F6; padding: 2px 6px; border-radius: 4px; font-size: 13px; }
</style>
</head>
<body>
<div class="container">
    <div class="card" style="text-align:center;padding:24px">
        <h1>e<span>Fix</span></h1>
        <p class="subtitle" style="margin-bottom:0">Обновление с GitHub</p>
    </div>

    <div class="card">
        <h2>Текущее состояние</h2>
        <ul>
            <li>
                <span class="ok">&#10003;</span>
                <span class="label">Файлов проекта</span>
                <?= $present ?> / <?= $total ?>
                <?php if (!$all_ok): ?><span class="fail">(не все)</span><?php endif ?>
            </li>
            <li>
                <span class="ok">&#10003;</span>
                <span class="label">База данных</span>
                <?= file_exists(__DIR__ . '/efix.db') ? 'есть (' . $db_version . ')' : 'нет' ?>
            </li>
            <li>
                <?= $zip_available ? '<span class="ok">&#10003;</span>' : '<span class="fail">&#10007;</span>' ?>
                <span class="label">PHP Zip</span>
                <?php if (!$zip_available): ?><span class="fail">Требуется для обновления</span><?php endif ?>
            </li>
        </ul>
    </div>

    <?php if ($all_ok && $zip_available): ?>
    <div class="card" style="text-align:center">
        <p style="color:var(--text-muted);margin-bottom:20px">
            Будет скачан последний код с GitHub.<br>
            Файлы конфигурации (.env, .htaccess, install/update.php) не затрагиваются.
        </p>
        <a href="?step=run" class="btn" onclick="this.textContent='Обновление...';this.style.pointerEvents='none';this.style.opacity='0.6'">
            Запустить обновление
        </a>
    </div>
    <?php elseif (!$all_ok): ?>
    <div class="alert alert-error">
        Не все файлы проекта на месте. Сначала запустите <a href="install.php" style="color:var(--accent)">install.php</a>.
    </div>
    <?php endif ?>

    <div class="footer-note">
        <a href="index.php">На сайт</a>
    </div>
</div>
</body>
</html>
