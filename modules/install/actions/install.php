<?php
header('Content-Type: application/json; charset=utf-8');
try {
    $admin_user = trim($_POST['admin_user'] ?? '');
    $admin_pass = $_POST['admin_pass'] ?? '';
    $admin_pass2 = $_POST['admin_pass2'] ?? '';
    $secret = trim($_POST['secret'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $site_name = trim($_POST['site_name'] ?? 'eFix');
    $address = trim($_POST['address'] ?? '');
    $db_type = $_POST['db_type'] ?? 'sqlite';
    $db_host = trim($_POST['db_host'] ?? '');
    $db_port = trim($_POST['db_port'] ?? '3306');
    $db_name = trim($_POST['db_name'] ?? '');
    $db_user = trim($_POST['db_user'] ?? '');
    $db_pass = $_POST['db_pass'] ?? '';

    if (!$admin_user || !$admin_pass) throw new Exception('Заполните имя администратора и пароль');
    if ($admin_pass !== $admin_pass2) throw new Exception('Пароли не совпадают');
    if (strlen($admin_pass) < 4) throw new Exception('Пароль минимум 4 символа');
    if ($db_type === 'mysql' && (!$db_host || !$db_name || !$db_user)) throw new Exception('Для MySQL заполните хост, имя БД и пользователя');

    if (!$secret) $secret = generate_secret();

    $env = "# eFix configuration\nSECRET_KEY={$secret}\n";
    if ($db_type === 'mysql') {
        $env .= "DATABASE_URL=mysql:host={$db_host};port={$db_port};dbname={$db_name};charset=utf8mb4\nDB_USER={$db_user}\nDB_PASS={$db_pass}\n";
    } else {
        $env .= "DATABASE_URL=sqlite:" . __DIR__ . "/../../efix.db\n";
    }
    if ($phone) $env .= "SITE_PHONE={$phone}\n";
    file_put_contents(__DIR__ . '/../../.env', $env);

    require_once __DIR__ . '/../../config.php';
    require_once __DIR__ . '/../../database.php';
    require_once __DIR__ . '/../../helpers.php';
    require_once __DIR__ . '/../../hooks.php';
    require_once __DIR__ . '/../../render.php';
    require_once __DIR__ . '/../../app/Router.php';
    foreach (PROJECT_FILES as $f) {
        if (str_starts_with($f, 'models/')) require_once __DIR__ . '/../../' . $f;
    }

    $db = Database::getInstance();
    $db->initSchema();

    $existing = Admin::getByUsername($admin_user);
    if (!$existing) Admin::create($admin_user, $admin_pass);

    $defaults = [
        'site_name' => $site_name, 'phone' => $phone ?: '+7 (999) 999-99-99',
        'email' => $email ?: 'info@efix.ru', 'address_short' => $address ?: 'Новосибирск, выезд по городу',
        'work_hours' => 'Ежедневно с 09:00 до 21:00',
        'meta_title' => "$site_name — Выездной сервисный центр в Новосибирске",
        'meta_description' => "$site_name — ремонт цифровой техники в Новосибирске. Бесплатная диагностика, гарантия.",
        'hero_badge' => 'Выездной сервисный центр',
        'hero_title' => 'Ремонтируем цифровую технику —<br>заберём, починим, вернём',
        'hero_subtitle' => 'Телефоны, планшеты, ноутбуки, ПК. Бесплатная диагностика, гарантия до 1 года.',
        'cta_button_text' => 'Вызвать мастера', 'prices_button_text' => 'Смотреть цены',
        'footer_description' => 'Выездной сервисный центр в Новосибирске.',
        'copyright' => '(c) ' . date('Y') . ' ' . $site_name,
    ];
    foreach ($defaults as $k => $v) SiteContent::set($k, $v);
    AppSetting::set('site_name', $site_name);
    AppSetting::set('default_email', $email ?: '');

    if (!file_exists(__DIR__ . '/../../index.html')) {
        file_put_contents(__DIR__ . '/../../index.html', '<!DOCTYPE html><html lang="ru"><head><meta charset="UTF-8"><meta http-equiv="refresh" content="0;url=index.php"><title>' . htmlspecialchars($site_name) . '</title></head><body><p><a href="index.php">Перейти на сайт</a></p></body></html>');
    }

    echo json_encode(['ok' => true, 'message' => "Установка завершена!", 'admin_user' => $admin_user]);
} catch (Exception $e) {
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
