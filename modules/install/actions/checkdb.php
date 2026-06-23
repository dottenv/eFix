<?php
while (ob_get_level()) ob_end_clean();
header('Content-Type: application/json; charset=utf-8');
try {
    $host = trim($_POST['host'] ?? '');
    $port = trim($_POST['port'] ?? '3306');
    $name = trim($_POST['name'] ?? '');
    $user = trim($_POST['user'] ?? '');
    $pass = $_POST['pass'] ?? '';
    if (!$host || !$name || !$user) throw new Exception('Заполните хост, имя БД и пользователя');
    $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 5,
    ]);
    echo json_encode(['ok' => true, 'message' => 'Подключение успешно']);
} catch (Throwable $e) {
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
