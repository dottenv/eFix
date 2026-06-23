<?php
session_start();

// HTTP → HTTPS redirect
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
    header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit;
}

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

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

if (str_starts_with($uri, '/static/')) {
    $file = __DIR__ . $uri;
    if (file_exists($file)) {
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $mime = ['css' => 'text/css', 'js' => 'application/javascript', 'png' => 'image/png', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'gif' => 'image/gif', 'svg' => 'image/svg+xml', 'webp' => 'image/webp', 'ico' => 'image/x-icon', 'woff2' => 'font/woff2'];
        header('Content-Type: ' . ($mime[$ext] ?? 'application/octet-stream'));
        readfile($file);
        exit;
    }
    notFound();
}

// Clean URLs: /install -> install.php, /update -> update.php, etc.
if (preg_match('#^/([a-zA-Z0-9_-]+)$#', $uri, $m)) {
    $script = __DIR__ . '/' . $m[1] . '.php';
    if (file_exists($script) && !in_array($m[1], ['index', 'config', 'database', 'helpers', 'hooks', 'render'])) {
        require $script;
        exit;
    }
}

trackPageView($uri);

require_once __DIR__ . '/routes/main.php';
require_once __DIR__ . '/routes/api.php';
require_once __DIR__ . '/routes/admin.php';

notFound();
