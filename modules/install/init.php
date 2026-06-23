<?php
require_once __DIR__ . '/common.php';

$action = $_GET['action'] ?? '';

if ($action === 'download' && extension_loaded('zip')) {
    require __DIR__ . '/actions/download.php';
    exit;
}

if ($action === 'install' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    require __DIR__ . '/actions/install.php';
    exit;
}

if ($action === 'install') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
    exit;
}

$checks = [];
$checks[] = ['id' => 'phpver', 'label' => 'PHP ≥ 8.0', 'ok' => version_compare(PHP_VERSION, '8.0', '>='), 'detail' => PHP_VERSION];
$extList = ['pdo','pdo_sqlite','pdo_mysql','mbstring','json','session','zip'];
foreach ($extList as $e) {
    $checks[] = ['id' => 'ext_'.$e, 'label' => 'PHP: '.$e, 'ok' => extension_loaded($e), 'detail' => extension_loaded($e) ? '✓' : '✗'];
}
$checks[] = ['id' => 'writable', 'label' => 'Папка доступна для записи', 'ok' => is_writable(__DIR__ . '/../..')];
$doc_root = $_SERVER['DOCUMENT_ROOT'] ?? '';
$checks[] = ['id' => 'docroot', 'label' => 'DocumentRoot совпадает', 'ok' => realpath($doc_root) === realpath(__DIR__ . '/../..'), 'detail' => $doc_root];
$rw = check_mod_rewrite();
$checks[] = ['id' => 'rewrite', 'label' => 'mod_rewrite', 'ok' => $rw !== false, 'warn' => $rw === null, 'detail' => $rw === true ? 'работает' : ($rw === false ? 'не найден' : 'неизвестно')];
$all_ok = !array_filter($checks, fn($c) => !$c['ok'] && !($c['warn'] ?? false));
$missing = missing_files();
$files_ok = empty($missing);

require __DIR__ . '/views/layout.php';
