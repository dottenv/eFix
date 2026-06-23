<?php

session_start();

$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;
$errors = [];

$root = __DIR__ . '/..';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['install'] = array_merge($_SESSION['install'] ?? [], $_POST);
}

if (file_exists("$root/storage/.installed") && $step !== 'done') {
    header('Location: /');
    exit;
}

function req(string $root): array {
    $checks = [];
    $checks[] = ['PHP >= 8.1', PHP_VERSION_ID >= 80100, PHP_VERSION];
    $checks[] = ['PDO', extension_loaded('pdo'), null];
    $checks[] = ['PDO MySQL', extension_loaded('pdo_mysql'), null];
    $checks[] = ['JSON', extension_loaded('json'), null];
    $checks[] = ['config/ writable', is_writable("$root/config"), null];
    $checks[] = ['assets/ writable', is_writable("$root/assets"), null];
    return $checks;
}

function install(array $data, string $root): array {
    $errors = [];

    $cfg = "<?php\n\nreturn [\n"
        . "    'name' => '" . addslashes($data['site_name']) . "',\n"
        . "    'url' => '" . addslashes($data['site_url']) . "',\n"
        . "    'debug' => false,\n\n"
        . "    'db' => [\n"
        . "        'driver' => 'mysql',\n"
        . "        'host' => '" . addslashes($data['db_host']) . "',\n"
        . "        'port' => '" . addslashes($data['db_port']) . "',\n"
        . "        'database' => '" . addslashes($data['db_name']) . "',\n"
        . "        'username' => '" . addslashes($data['db_user']) . "',\n"
        . "        'password' => '" . addslashes($data['db_pass']) . "',\n"
        . "        'charset' => 'utf8mb4',\n"
        . "    ],\n"
        . "];\n";

    if (!file_put_contents("$root/config/app.php", $cfg)) {
        $errors[] = 'Не удалось записать config/app.php';
        return $errors;
    }

    $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
        $data['db_host'], $data['db_port'], $data['db_name']);

    try {
        $pdo = new PDO($dsn, $data['db_user'], $data['db_pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
        $sql = file_get_contents("$root/migrations/001_init.sql");
        $pdo->exec($sql);
    } catch (PDOException $e) {
        $errors[] = 'Ошибка БД: ' . $e->getMessage();
        return $errors;
    }

    $hash = password_hash($data['admin_pass'], PASSWORD_BCRYPT);
    $stmt = $pdo->prepare('INSERT INTO admins (username, password_hash) VALUES (?, ?)');
    $stmt->execute([$data['admin_user'], $hash]);

    $defaults = [
        'site_name' => $data['site_name'],
        'site_phone' => $data['site_phone'],
        'site_email' => $data['site_email'],
        'site_address' => $data['site_address'] ?? '',
        'hero_title' => 'Ремонтируем технику с выездом к вам',
        'hero_subtitle' => 'Заберём, починим и привезём обратно — бесплатно',
        'years_count' => '8',
        'repaired_count' => '5000+',
        'clients_percent' => '98%',
        'meta_title' => 'eFix — Ремонт цифровой техники',
        'meta_description' => 'Выездной сервисный центр по ремонту телефонов, планшетов, ноутбуков и ПК',
    ];

    $insert = $pdo->prepare(
        'INSERT INTO content (`key`, `value`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)'
    );

    foreach ($defaults as $key => $value) {
        $insert->execute([$key, $value]);
    }

    $env = "DB_HOST={$data['db_host']}\nDB_PORT={$data['db_port']}\nDB_NAME={$data['db_name']}\nDB_USER={$data['db_user']}\nDB_PASS={$data['db_pass']}\n";
    file_put_contents("$root/.env", $env);
    file_put_contents("$root/storage/.installed", date('c'));

    return $errors;
}

if ($step === 5) {
    $errors = install($_SESSION['install'] ?? [], $root);
    if (!$errors) {
        $_SESSION['install_done'] = true;
        header('Location: /install/?step=done');
        exit;
    }
}

?><!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Установка eFix</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:system-ui,-apple-system,sans-serif;background:linear-gradient(135deg,#0B2447,#1a3a6a);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:2rem}
.box{background:#fff;border-radius:16px;padding:2.5rem;max-width:540px;width:100%;box-shadow:0 20px 60px rgba(0,0,0,.3)}
.box h1{color:#0B2447;font-size:1.5rem;margin-bottom:.25rem}
.box .sub{color:#666;margin-bottom:1.5rem}
.dots{display:flex;gap:.5rem;margin-bottom:2rem}
.dot{width:10px;height:10px;border-radius:50%;background:#ddd}
.dot.on{background:#FF6B35}
.dot.ok{background:#0B2447}
label{display:block;margin-bottom:.9rem}
label span{display:block;font-weight:600;margin-bottom:.25rem;color:#333;font-size:.9rem}
input,select{width:100%;padding:.65rem .75rem;border:1.5px solid #ddd;border-radius:10px;font-size:.95rem;transition:border-color .2s}
input:focus{outline:none;border-color:#FF6B35}
.btn{display:inline-flex;align-items:center;justify-content:center;padding:.75rem 2rem;border:none;border-radius:12px;font-weight:600;cursor:pointer;font-size:1rem;background:#FF6B35;color:#fff;width:100%;transition:transform .15s,box-shadow .15s}
.btn:hover{transform:translateY(-1px);box-shadow:0 4px 15px rgba(255,107,53,.3)}
.btn:disabled{opacity:.6;cursor:not-allowed}
.row{display:grid;grid-template-columns:1fr 1fr;gap:0 .75rem}
.chk{display:flex;justify-content:space-between;padding:.5rem 0;border-bottom:1px solid #eee}
.chk.ok{color:#2e7d32}
.chk.fail{color:#c62828}
.chk .st{font-weight:700}
.err{background:#fff0f0;color:#c62828;padding:.75rem;border-radius:10px;margin-bottom:1rem;font-size:.9rem}
.ok-box{text-align:center}
.ok-box h2{color:#2e7d32;margin-bottom:.5rem}
.ok-box p{margin-bottom:1.5rem;color:#555}
</style>
</head>
<body>
<div class="box">

<?php if ($step === 'done'): ?>
    <div class="ok-box">
        <h2>✓ Установка завершена</h2>
        <p>eFix готов к работе. Удалите папку <strong>install</strong> для безопасности.</p>
        <a href="/" class="btn">На сайт</a>
        <br><br>
        <a href="/admin/login" class="btn" style="background:#0B2447">Войти в админку</a>
    </div>

<?php else: ?>
    <h1>Установка eFix</h1>
    <p class="sub">Шаг <?= $step ?> из 4</p>
    <div class="dots">
        <?php for ($i = 1; $i <= 4; $i++): ?>
            <div class="dot <?= $i < $step ? 'ok' : ($i === $step ? 'on' : '') ?>"></div>
        <?php endfor; ?>
    </div>

    <?php foreach ($errors as $e): ?>
        <div class="err"><?= htmlspecialchars($e) ?></div>
    <?php endforeach; ?>

    <form method="post" action="?step=<?= $step + 1 ?>">

    <?php if ($step === 1): $checks = req($root); $ok = true; ?>
        <?php foreach ($checks as $c): $pass = $c[1]; $ok = $ok && $pass; ?>
            <div class="chk <?= $pass ? 'ok' : 'fail' ?>">
                <span><?= htmlspecialchars($c[0]) ?></span>
                <span class="st"><?= $pass ? '✓' : ($c[2] ?: '✗') ?></span>
            </div>
        <?php endforeach; ?>
        <br>
        <button type="submit" class="btn" <?= $ok ? '' : 'disabled' ?>>
            <?= $ok ? 'Продолжить' : 'Исправьте ошибки' ?>
        </button>

    <?php elseif ($step === 2): ?>
        <div class="row">
            <label><span>Хост</span><input type="text" name="db_host" value="localhost" required></label>
            <label><span>Порт</span><input type="text" name="db_port" value="3306" required></label>
        </div>
        <label><span>Имя БД</span><input type="text" name="db_name" value="efix" required></label>
        <label><span>Пользователь</span><input type="text" name="db_user" required></label>
        <label><span>Пароль</span><input type="password" name="db_pass"></label>
        <button type="submit" class="btn">Подключиться</button>

    <?php elseif ($step === 3): ?>
        <label><span>Название сайта</span><input type="text" name="site_name" value="eFix" required></label>
        <label><span>URL сайта</span><input type="url" name="site_url" placeholder="https://efix.ru" required></label>
        <label><span>Телефон</span><input type="text" name="site_phone" placeholder="+7 (999) 999-99-99" required></label>
        <div class="row">
            <label><span>Email</span><input type="email" name="site_email" required></label>
            <label><span>Адрес</span><input type="text" name="site_address"></label>
        </div>
        <button type="submit" class="btn">Далее</button>

    <?php elseif ($step === 4): ?>
        <label><span>Логин</span><input type="text" name="admin_user" value="admin" required></label>
        <label><span>Email</span><input type="email" name="admin_email"></label>
        <label><span>Пароль</span><input type="password" name="admin_pass" required minlength="6"></label>
        <label><span>Ещё раз</span><input type="password" name="admin_pass_confirm" required></label>
        <button type="submit" class="btn">Установить</button>

    <?php endif; ?>
    </form>
<?php endif; ?>

</div>
</body>
</html>
