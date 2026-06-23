<?php
function e($str) {
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

function url_for($name, $params = []) {
    $urls = [
        'main.index' => '/',
        'main.services' => '/services',
        'main.prices' => '/prices',
        'main.about' => '/about',
        'main.contacts' => '/contacts',
        'api.callback' => '/api/callback',
        'api.brands' => '/api/brands',
        'api.models' => '/api/models',
        'api.prices-table' => '/api/prices-table',
        'api.workshops' => '/api/workshops',
        'admin.login' => '/admin/login',
        'admin.register' => '/admin/register',
        'admin.logout' => '/admin/logout',
        'admin.dashboard' => '/admin/dashboard',
        'admin.site' => '/admin/site',
        'admin.services' => '/admin/services',
        'admin.prices' => '/admin/prices',
        'admin.workshops' => '/admin/workshops',
        'admin.requests_list' => '/admin/requests',
        'admin.requests_check' => '/admin/requests/check',
        'admin.request_bulk' => '/admin/requests/bulk',
        'admin.stats' => '/admin/stats',
        'admin.mail_config' => '/admin/mail-config',
        'admin.mail_templates' => '/admin/mail-templates',
        'admin.mail_template_add' => '/admin/mail-templates/add',
        'admin.settings' => '/admin/settings',
        'admin.settings_save' => '/admin/settings/save',
        'admin.settings_env' => '/admin/settings/env',
        'admin.send_test_email' => '/admin/api/send-test-email',
        'admin.stats_summary' => '/admin/api/stats/summary',
        'admin.stats_page_views' => '/admin/api/stats/page-views',
        'admin.stats_pages' => '/admin/api/stats/pages',
        'admin.stats_referrers' => '/admin/api/stats/referrers',
        'admin.stats_searches' => '/admin/api/stats/searches',
        'admin.stats_frequent_searches' => '/admin/api/stats/frequent-searches',
        'admin.stats_device_breakdown' => '/admin/api/stats/device-breakdown',
        'admin.stats_locations' => '/admin/api/stats/locations',
        'admin.stats_device_types' => '/admin/api/stats/device-types',
        'admin.stats_realtime' => '/admin/api/stats/realtime',
        'admin.stats_utms' => '/admin/api/stats/utms',
        'admin.stats_browsers' => '/admin/api/stats/browsers',
        'admin.stats_os' => '/admin/api/stats/os',
        'admin.stats_screens' => '/admin/api/stats/screens',
        'admin.stats_forms' => '/admin/api/stats/forms',
        'admin.stats_languages' => '/admin/api/stats/languages',
        'admin.stats_sessions' => '/admin/api/stats/sessions',
    ];
    $url = $urls[$name] ?? '/';

    if (!empty($params)) {
        $query = http_build_query($params);
        if ($query) {
            $url .= (str_contains($url, '?') ? '&' : '?') . $query;
        }
    }
    return $url;
}

function redirect($url) {
    header('Location: ' . $url);
    exit;
}

function json_response($data, $code = 200) {
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function html_response($html, $code = 200) {
    http_response_code($code);
    echo $html;
    exit;
}

function notFound() {
    http_response_code(404);
    $sc = getSiteContent();
    render_raw('404', ['sc' => $sc], false);
    exit;
}

function getSiteContent() {
    $db = Database::getInstance();
    $rows = $db->fetchAll("SELECT key, value FROM site_content");
    $result = [];
    foreach ($rows as $r) {
        $result[$r['key']] = $r['value'];
    }
    return $result;
}

function getClientIp() {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '';
    if ($ip && str_contains($ip, ',')) {
        $ip = trim(explode(',', $ip)[0]);
    }
    return $ip;
}

function trackPageView($path) {
    if (str_starts_with($path, '/api/') || str_starts_with($path, '/static/') || str_starts_with($path, '/admin/')) {
        return;
    }
    if (!isset($_SESSION['visitor_session'])) {
        $_SESSION['visitor_session'] = bin2hex(random_bytes(16));
        $_SESSION['visitor_new'] = true;
    } else {
        $_SESSION['visitor_new'] = false;
    }

    $ip = getClientIp();
    $screen = $_SERVER['HTTP_SEC_CH_WIDTH'] ?? $_GET['_sw'] ?? '';
    $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '', 0, 20);
    $db = Database::getInstance();
    $db->insert('page_view', [
        'path' => $path,
        'ip' => $ip,
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
        'referrer' => $_SERVER['HTTP_REFERER'] ?? '',
        'screen' => $screen ?: null,
        'language' => $lang ?: null,
        'utm_source' => $_GET['utm_source'] ?? null,
        'utm_medium' => $_GET['utm_medium'] ?? null,
        'utm_campaign' => $_GET['utm_campaign'] ?? null,
        'session_id' => $_SESSION['visitor_session'],
        'is_new_visitor' => $_SESSION['visitor_new'] ? 1 : 0,
    ]);
}

function is_admin_authenticated() {
    return isset($_SESSION['admin_id']);
}

function require_admin() {
    if (!is_admin_authenticated()) {
        redirect('/admin/login');
    }
}
