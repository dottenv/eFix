<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->escape($title ?? 'Админ-панель') ?> — eFix Admin</title>
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body>
    <?php if ($_SESSION['admin_logged_in'] ?? false): ?>
    <header class="admin-header">
        <div class="admin-header__inner">
            <a href="/admin" class="admin-header__logo">eFix Admin</a>
            <nav class="admin-nav">
                <a href="/admin" class="admin-nav__link">Дашборд</a>
                <a href="/admin/leads" class="admin-nav__link">Заявки</a>
                <a href="/admin/services" class="admin-nav__link">Услуги</a>
                <a href="/admin/pages" class="admin-nav__link">Страницы</a>
                <a href="/admin/logout" class="admin-nav__link admin-nav__link--logout">Выйти</a>
            </nav>
        </div>
    </header>
    <?php endif; ?>
    <main class="admin-main">
        <?php if (!empty($_SESSION['_flash'])): ?>
            <?php foreach ($_SESSION['_flash'] as $key => $msg): ?>
                <div class="alert alert--<?= $key ?>"><?= $this->escape($msg) ?></div>
            <?php endforeach; ?>
            <?php $this->clearFlash(); ?>
        <?php endif; ?>
        <?= $content ?>
    </main>
</body>
</html>
