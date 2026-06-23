<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'eFix Admin' ?></title>
    <script src="https://unpkg.com/htmx.org@2"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1/dist/leaflet.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body>
    <div class="admin-layout">
        <?= $this->component('admin_sidebar') ?>
        <div class="admin-content">
            <?= $slot ?>
        </div>
    </div>
    <script src="/assets/js/admin.js"></script>
</body>
</html>
