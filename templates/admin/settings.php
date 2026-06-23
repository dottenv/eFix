<?php
$title = 'Настройки — ' . ($site_name ?? 'eFix');
$header = 'Настройки';
ob_start();
?>
<div id="settingsApp" x-data="settingsApp()">
    <div class="card" style="max-width:500px">
        <h2 class="card__title" style="margin-bottom:16px">Основные</h2>
        <?php if(!empty($_GET['saved'])): ?>
            <div style="background:#D1FAE5;color:#065F46;padding:10px 16px;border-radius:8px;margin-bottom:16px;font-size:13px">Настройки сохранены</div>
        <?php endif ?>
        <form method="POST">
            <div class="form-group">
                <label>Название сайта</label>
                <input type="text" name="site_name" value="<?= e($cfg['site_name'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Email для уведомлений</label>
                <input type="email" name="admin_email" value="<?= e($cfg['admin_email'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Заявок на странице (админка)</label>
                <input type="number" name="per_page" value="<?= e($cfg['per_page'] ?? '20') ?>">
            </div>
            <div class="form-group">
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
                    <input type="checkbox" name="maintenance_mode" value="1" <?= !empty($cfg['maintenance_mode']) ? 'checked' : '' ?>>
                    Режим обслуживания
                </label>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn--primary">Сохранить</button>
            </div>
        </form>
    </div>

    <div class="card" style="max-width:500px;margin-top:24px">
        <h2 class="card__title" style="margin-bottom:16px">Очистка данных</h2>
        <form method="POST" action="/admin/settings/clear-old" onsubmit="return confirm('Очистить заявки старше 90 дней?')">
            <p style="font-size:13px;color:#6B7280;margin-bottom:12px">Удалить заявки старше 90 дней и связанные с ними файлы.</p>
            <button type="submit" class="btn btn--danger">Очистить</button>
        </form>
    </div>

    <div class="card" style="max-width:500px;margin-top:24px">
        <h2 class="card__title" style="margin-bottom:16px">Резервное копирование</h2>
        <button class="btn btn--outline" @click="makeBackup">Создать backup</button>
        <div x-show="backupMsg" x-text="backupMsg" style="margin-top:8px;font-size:13px"></div>
        <?php if(!empty($backups)): ?>
        <div style="margin-top:16px">
            <h3 style="font-size:13px;font-weight:600;margin-bottom:8px">Сохранённые backups</h3>
            <ul style="font-size:13px">
                <?php foreach($backups as $b): ?>
                <li style="display:flex;justify-content:space-between;align-items:center;padding:6px 0">
                    <span><?= e(basename($b)) ?></span>
                    <form method="POST" action="/admin/settings/restore-backup" onsubmit="return confirm('Восстановить из ' + '<?= e(basename($b)) ?>?' + ' Текущие данные будут заменены.')">
                        <input type="hidden" name="backup" value="<?= e(basename($b)) ?>">
                        <button class="btn btn--outline btn--sm">Восстановить</button>
                    </form>
                </li>
                <?php endforeach ?>
            </ul>
        </div>
        <?php endif ?>
    </div>
</div>
<script>
function settingsApp() {
    return {
        backupMsg: '',
        makeBackup() {
            this.backupMsg = 'Создание backup...';
            fetch('/admin/settings/make-backup', { method: 'POST' })
                .then(r => r.json())
                .then(d => { this.backupMsg = d.ok ? 'Backup создан: ' + d.file : 'Ошибка: ' + (d.error || '?'); })
                .catch(e => { this.backupMsg = 'Ошибка: ' + e.message; });
        }
    }
}
</script>
<?php
$content = ob_get_clean();
include __DIR__ . '/base.php';
?>
