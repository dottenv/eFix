<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'eFix' ?></title>
    <meta name="description" content="<?= $metaDescription ?? '' ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/htmx.org@2"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body hx-boost="true" hx-target="#main" hx-select="#main" hx-swap="outerHTML">
    <?= $this->component('header') ?>

    <main id="main">
        <?= $slot ?>
    </main>

    <?= $this->component('footer') ?>
    <?= $this->component('floating_button') ?>
    <?= $this->component('modal') ?>

    <script src="/assets/js/app.js"></script>
</body>
</html>
