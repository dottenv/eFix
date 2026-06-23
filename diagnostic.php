<?php
// ============================================================
// eFix — Full System Diagnostic
// ============================================================

$tests = [];

// ---------- helpers ----------
function test($name, $ok, $detail = '') {
    global $tests;
    $tests[] = ['name' => $name, 'ok' => $ok, 'detail' => $detail];
}

function check_ext($name) {
    return extension_loaded($name);
}

function check_file($path) {
    $full = __DIR__ . '/' . $path;
    if (!file_exists($full)) return 'not_found';
    if (!is_readable($full)) return 'not_readable';
    return 'ok';
}

// ========== 1. PHP Environment ==========
test('PHP версия', PHP_VERSION_ID >= 80000, PHP_VERSION);

$required_exts = ['pdo', 'pdo_sqlite', 'mbstring', 'json', 'session', 'zip'];
foreach ($required_exts as $e) {
    test("Расширение: $e", check_ext($e), check_ext($e) ? 'загружено' : 'ОТСУТСТВУЕТ');
}

test('allow_url_fopen', !!ini_get('allow_url_fopen'), ini_get('allow_url_fopen') ? 'включён' : 'выключен');
test('curl', function_exists('curl_init'), function_exists('curl_init') ? 'доступен' : 'недоступен');

// ========== 2. Server info ==========
$doc_root = $_SERVER['DOCUMENT_ROOT'] ?? '?';
$cur_dir = __DIR__;
$server_soft = $_SERVER['SERVER_SOFTWARE'] ?? '?';
$sapi = PHP_SAPI;

test('DocumentRoot', $doc_root === $cur_dir, "$doc_root  " . ($doc_root === $cur_dir ? '(совпадает с __DIR__)' : '(НЕ совпадает с __DIR__: ' . $cur_dir . ')'));
test('Веб-сервер', true, "$server_soft  (SAPI: $sapi)");
test('Режим PHP', true, $sapi);

// ========== 3. File system checks ==========
test('Корень доступен для записи', is_writable(__DIR__), is_writable(__DIR__) ? 'да' : 'НЕТ');

$key_files = [
    'index.php', 'config.php', 'database.php', 'helpers.php', 'render.php', 'hooks.php',
    '.htaccess', '.env' => file_exists(__DIR__ . '/.env') ? 'ok' : 'not_found',
];
$all_project_files = [
    'models/Admin.php', 'models/SiteContent.php', 'models/Service.php',
    'models/PriceItem.php', 'models/PartnerWorkshop.php', 'models/ContactRequest.php',
    'models/PageView.php', 'models/SearchQuery.php', 'models/IpLocation.php',
    'models/FormInteraction.php', 'models/MailConfig.php', 'models/MailTemplate.php',
    'models/AppSetting.php',
    'routes/main.php', 'routes/api.php', 'routes/admin.php',
    'templates/base.php', 'templates/index.php', 'templates/services.php',
    'templates/prices.php', 'templates/about.php', 'templates/contacts.php',
    'templates/404.php', 'templates/_prices_table.php',
    'templates/admin/base.php', 'templates/admin/dashboard.php',
    'templates/admin/login.php', 'templates/admin/services.php',
    'templates/admin/prices.php', 'templates/admin/workshops.php',
    'templates/admin/requests.php', 'templates/admin/site.php',
    'templates/admin/stats.php', 'templates/admin/settings.php',
    'templates/admin/mail_config.php', 'templates/admin/mail_templates.php',
    'templates/admin/mail_template_edit.php', 'templates/admin/env.php',
    'static/css/style.css', 'static/js/main.js',
];

$missing = [];
foreach ($all_project_files as $f) {
    $status = check_file($f);
    if ($status !== 'ok') $missing[] = "$f ($status)";
}
test('Файлы проекта (' . (count($all_project_files) - count($missing)) . '/' . count($all_project_files) . ')', empty($missing), empty($missing) ? 'все на месте' : 'отсутствуют: ' . implode(', ', $missing));

// Check directory structure
$required_dirs = ['models', 'routes', 'templates', 'templates/admin', 'static', 'static/css', 'static/js'];
$missing_dirs = [];
foreach ($required_dirs as $d) {
    if (!is_dir(__DIR__ . '/' . $d)) $missing_dirs[] = $d;
}
test('Директории', empty($missing_dirs), empty($missing_dirs) ? 'все созданы' : 'отсутствуют: ' . implode(', ', $missing_dirs));

// ========== 4. .htaccess check ==========
$ht_file = __DIR__ . '/.htaccess';
if (file_exists($ht_file)) {
    $ht_content = file_get_contents($ht_file);
    test('.htaccess существует', true, filesize($ht_file) . ' байт');
    test('.htaccess: DirectoryIndex', strpos($ht_content, 'DirectoryIndex index.php') !== false, '');
    test('.htaccess: редирект корня', strpos($ht_content, 'RewriteRule ^$ index.php') !== false, '');
    test('.htaccess: front-controller', strpos($ht_content, 'RewriteRule ^(.*)$ index.php') !== false, '');
    test('.htaccess: clean URLs (.php)', strpos($ht_content, '.php -f') !== false, '');
} else {
    test('.htaccess существует', false, 'ФАЙЛ НЕ НАЙДЕН — будет создан');
}

// ========== 5. Database check ==========
$db_file = __DIR__ . '/efix.db';
if (file_exists($db_file)) {
    test('Файл БД', true, sprintf('%.1f KB', filesize($db_file) / 1024));
    test('БД доступна для записи', is_writable($db_file), is_writable($db_file) ? 'да' : 'НЕТ');
} else {
    test('Файл БД', false, 'не создан — нужно запустить install.php');
}

// ========== 6. Test DB connection ==========
$dbOk = false;
if (file_exists($db_file) && extension_loaded('pdo') && extension_loaded('pdo_sqlite')) {
    $dbOk = true;
} elseif (file_exists(__DIR__ . '/.env') && extension_loaded('pdo') && extension_loaded('pdo_mysql')) {
    $dbOk = true;
}
if ($dbOk) {
    try {
        require_once __DIR__ . '/config.php';
        require_once __DIR__ . '/database.php';
        $db = Database::getInstance();
        $pdo = $db->getPdo();
        test('Подключение к БД', true, $db->getDriver() . ' — успешно');

        if ($db->getDriver() === 'mysql') {
            $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        } else {
            $tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name")->fetchAll(PDO::FETCH_COLUMN);
        }
        test('Таблицы в БД (' . count($tables) . ')', !empty($tables), implode(', ', $tables));

        // Check key tables have data
        $admin_count = $db->fetchColumn("SELECT COUNT(*) FROM admin");
        test('Администраторы', $admin_count > 0, "$admin_count записей");

        $service_count = $db->fetchColumn("SELECT COUNT(*) FROM service");
        test('Услуги', $service_count > 0, "$service_count записей");

        $price_count = $db->fetchColumn("SELECT COUNT(*) FROM price_item");
        test('Цены', $price_count > 0, "$price_count записей");

        $workshop_count = $db->fetchColumn("SELECT COUNT(*) FROM partner_workshop");
        test('Мастерские', $workshop_count > 0, "$workshop_count записей");

        $content_count = $db->fetchColumn("SELECT COUNT(*) FROM site_content");
        test('Контент сайта', $content_count > 0, "$content_count записей");

        // Test categories query
        $categories = Service::getCategories();
        test('Категории услуг', !empty($categories), json_encode(array_column($categories, 'category')));

        // Test prices query
        $prices = PriceItem::search('', '', '', '', 1, 5);
        test('Поиск цен', !empty($prices['items']), 'найдено: ' . $prices['total'] . ' всего, показано: ' . count($prices['items']));

    } catch (Exception $e) {
        test('Ошибка БД', false, $e->getMessage());
    } catch (Throwable $e) {
        test('Ошибка БД', false, $e->getMessage());
    }
}

// ========== 7. Routing tests ==========
$routes_to_test = [
    '/' => 'GET',
    '/services' => 'GET',
    '/prices' => 'GET',
    '/about' => 'GET',
    '/contacts' => 'GET',
    '/api/brands' => 'GET',
    '/api/models' => 'GET',
    '/api/categories' => 'GET',
    '/api/prices-table' => 'GET',
    '/api/workshops' => 'GET',
    '/index.php' => 'GET',
];
test('Маршрутов в url_for', true, 'в helpers.php: ' . substr_count(file_get_contents(__DIR__ . '/helpers.php'), "=> '/"));

// ========== 8. Template check ==========
$template_vars = [
    'base.php' => ['$content', '$sc', '$active'],
    'services.php' => ['$categories', '$services_by_cat'],
    'prices.php' => ['$deviceTypes', '$brands', '$items'],
    '_prices_table.php' => ['$items', '$total', '$page', '$totalPages'],
];
foreach ($template_vars as $tpl => $vars) {
    $path = __DIR__ . '/templates/' . $tpl;
    if (file_exists($path)) {
        test("Шаблон $tpl", true, 'существует');
    } else {
        test("Шаблон $tpl", false, 'ОТСУТСТВУЕТ');
    }
}

// ========== 9. Static files ==========
test('CSS стили', file_exists(__DIR__ . '/static/css/style.css'), file_exists(__DIR__ . '/static/css/style.css') ? sprintf('%.1f KB', filesize(__DIR__ . '/static/css/style.css') / 1024) : 'нет');
test('JS скрипты', file_exists(__DIR__ . '/static/js/main.js'), file_exists(__DIR__ . '/static/js/main.js') ? sprintf('%.1f KB', filesize(__DIR__ . '/static/js/main.js') / 1024) : 'нет');

// ========== 10. mod_rewrite check ==========
if (function_exists('apache_get_modules')) {
    $has_rewrite = in_array('mod_rewrite', apache_get_modules());
    test('mod_rewrite (Apache)', $has_rewrite, $has_rewrite ? 'доступен' : 'не обнаружен');
} else {
    test('mod_rewrite (Apache)', null, 'Apache не используется (возможно nginx)');
}

// ========== Summary ==========
$ok = count(array_filter($tests, fn($t) => $t['ok'] === true));
$fail = count(array_filter($tests, fn($t) => $t['ok'] === false));
$skip = count(array_filter($tests, fn($t) => $t['ok'] === null));
$total = count($tests);

?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Диагностика eFix</title>
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif;
    background: #F5F7FA; color: #1A1A2E; line-height: 1.6; padding: 30px 20px;
}
.container { max-width: 800px; margin: 0 auto; }
h1 { font-size: 26px; font-weight: 800; color: #0B2447; margin-bottom: 4px; }
h1 span { color: #FF6B35; }
.summary {
    font-size: 14px; color: #6B7280; margin-bottom: 24px; display: flex; gap: 16px;
}
.summary strong { font-weight: 700; }
.summary .ok { color: #10B981; }
.summary .fail { color: #EF4444; }
.card {
    background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,.06);
    padding: 24px; margin-bottom: 16px;
}
h2 {
    font-size: 16px; font-weight: 700; color: #0B2447; margin-bottom: 16px;
    padding-bottom: 8px; border-bottom: 2px solid #E5E7EB;
}
table { width: 100%; border-collapse: collapse; font-size: 13px; }
th { text-align: left; padding: 6px 8px; color: #6B7280; font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: .5px; border-bottom: 1px solid #E5E7EB; }
td { padding: 8px; border-bottom: 1px solid #F3F4F6; vertical-align: top; }
tr:last-child td { border-bottom: none; }
td.status { width: 30px; text-align: center; font-size: 16px; }
td.name { font-weight: 600; width: 30%; }
td.detail { color: #6B7280; word-break: break-all; }
.ok-status { color: #10B981; }
.fail-status { color: #EF4444; }
.skip-status { color: #F59E0B; }
.badge {
    display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 700;
}
.badge-ok { background: #D1FAE5; color: #065F46; }
.badge-fail { background: #FEE2E2; color: #991B1B; }
.badge-skip { background: #FEF3C7; color: #92400E; }
.warning {
    background: #FFFBEB; border: 1px solid #FDE68A; border-radius: 8px;
    padding: 14px 18px; font-size: 13px; color: #92400E; margin-top: 16px;
}
.section-toggle {
    cursor: pointer; user-select: none; display: flex; align-items: center; gap: 8px;
}
.section-toggle:hover { opacity: .7; }
.footer-note { text-align: center; color: #6B7280; font-size: 13px; margin-top: 24px; }
code { background: #F3F4F6; padding: 1px 5px; border-radius: 3px; font-size: 12px; }
</style>
</head>
<body>
<div class="container">
    <h1>e<span>Fix</span></h1>
    <p class="summary">
        <span><span class="ok">&#10003;</span> <strong><?= $ok ?></strong> ОК</span>
        <?php if ($fail): ?><span><span class="fail">&#10007;</span> <strong><?= $fail ?></strong> ошибок</span><?php endif ?>
        <?php if ($skip): ?><span><span class="skip-status">?</span> <strong><?= $skip ?></strong> пропущено</span><?php endif ?>
        <span>всего <strong><?= $total ?></strong> проверок</span>
    </p>

    <div class="card">
        <h2 onclick="this.nextElementSibling.style.display=this.nextElementSibling.style.display==='none'?'':'none'" class="section-toggle" style="cursor:pointer">Результаты проверок</h2>
        <div>
            <table>
                <tr><th></th><th>Проверка</th><th>Результат</th></tr>
                <?php foreach ($tests as $t): ?>
                <tr>
                    <td class="status">
                        <?php if ($t['ok'] === true): ?><span class="ok-status">&#10003;</span>
                        <?php elseif ($t['ok'] === false): ?><span class="fail-status">&#10007;</span>
                        <?php else: ?><span class="skip-status">?</span>
                        <?php endif ?>
                    </td>
                    <td class="name"><?= htmlspecialchars($t['name']) ?></td>
                    <td class="detail">
                        <?php if ($t['ok'] === true): ?><span class="badge badge-ok">OK</span>
                        <?php elseif ($t['ok'] === false): ?><span class="badge badge-fail">FAIL</span>
                        <?php else: ?><span class="badge badge-skip">SKIP</span>
                        <?php endif ?>
                        <?php if ($t['detail']): ?> <?= htmlspecialchars($t['detail']) ?><?php endif ?>
                    </td>
                </tr>
                <?php endforeach ?>
            </table>
        </div>
    </div>

    <?php if ($fail > 0): ?>
    <div class="warning">
        <strong>Найдено <?= $fail ?> проблем(ы).</strong><br>
        Исправьте их перед использованием сайта. Запустите <a href="install.php" style="color:#FF6B35;font-weight:600">install.php</a>
        или <a href="update.php" style="color:#FF6B35;font-weight:600">update.php</a> для автоматического исправления.
    </div>
    <?php else: ?>
    <div class="warning" style="background:#F0FDF4;border-color:#BBF7D0;color:#166534">
        <strong>Все проверки пройдены.</strong> Система работает корректно.
    </div>
    <?php endif ?>

    <div class="footer-note">
        <a href="index.php" style="color:#FF6B35;font-weight:600">На сайт</a>
        &nbsp;|&nbsp;
        <a href="install.php" style="color:#FF6B35;font-weight:600">install.php</a>
        &nbsp;|&nbsp;
        <a href="update.php" style="color:#FF6B35;font-weight:600">update.php</a>
    </div>
</div>
</body>
</html>
