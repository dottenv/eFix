<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->escape($title ?? 'eFix — Сервисный центр по ремонту цифровой техники') ?></title>
    <meta name="description" content="<?= $this->escape($metaDescription ?? 'Ремонт телефонов, планшетов, ноутбуков и компьютеров в Минске') ?>">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <?= $this->renderPartial('pages/partials/header') ?>
    <main>
        <?= $content ?>
    </main>
    <?= $this->renderPartial('pages/partials/footer') ?>
    <script src="/assets/js/main.js"></script>
</body>
</html>
