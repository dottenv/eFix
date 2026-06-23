<?php
$DEVICE_TYPES = ['phone' => 'Телефоны', 'tablet' => 'Планшеты', 'laptop' => 'Ноутбуки', 'pc' => 'ПК и моноблоки'];

function render_raw($template, $vars = [], $exit = true) {
    extract($vars);
    $templateFile = __DIR__ . '/templates/' . $template . '.php';
    if (file_exists($templateFile)) {
        include $templateFile;
    } else {
        echo "Template not found: $template";
    }
    if ($exit) exit;
}

function render($template, $vars = []) {
    $vars['sc'] = getSiteContent();
    extract($vars);
    $templateFile = __DIR__ . '/templates/' . $template . '.php';
    if (file_exists($templateFile)) {
        include $templateFile;
    } else {
        echo "Template not found: $template";
    }
    exit;
}

function render_admin($template, $vars = []) {
    global $site_name;
    extract($vars);
    $site_name = $site_name ?? 'eFix';
    $templateFile = __DIR__ . '/templates/admin/' . $template . '.php';
    if (file_exists($templateFile)) {
        include $templateFile;
    } else {
        echo "Admin template not found: $template";
    }
    exit;
}

function get_price_display($from, $to = null) {
    if ($to && $to != $from) {
        return number_format($from, 0, '.', ' ') . ' — ' . number_format($to, 0, '.', ' ') . ' ₽';
    }
    return number_format($from, 0, '.', ' ') . ' ₽';
}

function truncate($str, $len = 200) {
    if (mb_strlen($str) <= $len) return $str;
    return mb_substr($str, 0, $len) . '...';
}

function status_label($status) {
    $labels = ['new' => 'Новая', 'in_progress' => 'В работе', 'completed' => 'Готова', 'archived' => 'Архив'];
    return $labels[$status] ?? $status;
}

function status_color($status) {
    $colors = ['new' => '--danger', 'in_progress' => '--warning', 'completed' => '--success', 'archived' => ''];
    return $colors[$status] ?? '';
}

function device_label($type) {
    $labels = ['phone' => 'Телефон', 'tablet' => 'Планшет', 'laptop' => 'Ноутбук', 'pc' => 'ПК'];
    return $labels[$type] ?? ($type ?? '');
}

function format_datetime($dt) {
    if (!$dt) return '';
    return date('d.m.Y H:i', strtotime($dt));
}
