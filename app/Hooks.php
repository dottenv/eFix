<?php
$_hook_registry = [];

function register_hook($name, $template) {
    global $_hook_registry;
    $_hook_registry[$name][] = $template;
}

function render_hook($name, $ctx = []) {
    global $_hook_registry;
    $parts = [];
    foreach ($_hook_registry[$name] ?? [] as $tpl) {
        ob_start();
        if (file_exists(__DIR__ . '/../templates/' . $tpl)) {
            extract($ctx);
            include __DIR__ . '/../templates/' . $tpl;
        }
        $parts[] = ob_get_clean();
    }
    return implode('', $parts);
}

function clear_hooks() {
    global $_hook_registry;
    $_hook_registry = [];
}
