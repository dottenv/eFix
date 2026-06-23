<?php
$title = 'Настройки почты — ' . ($site_name ?? 'eFix');
$header = 'Настройки почты';
ob_start();
?>
<div class="card" style="max-width:600px">
    <?php if(!empty($_GET['sent'])): ?>
        <div style="background:#D1FAE5;color:#065F46;padding:10px 16px;border-radius:8px;margin-bottom:16px;font-size:13px">Настройки сохранены</div>
    <?php endif ?>
    <form method="POST">
        <div class="form-row">
            <div class="form-group">
                <label>SMTP-сервер</label>
                <input type="text" name="smtp_host" value="<?= e($cfg['smtp_host'] ?? '') ?>" placeholder="smtp.gmail.com">
            </div>
            <div class="form-group">
                <label>Порт</label>
                <input type="number" name="smtp_port" value="<?= e($cfg['smtp_port'] ?? '587') ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Логин</label>
                <input type="text" name="smtp_user" value="<?= e($cfg['smtp_user'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Пароль</label>
                <input type="password" name="smtp_pass" value="" placeholder="••••••••">
            </div>
        </div>
        <div class="form-group">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
                <input type="checkbox" name="smtp_use_tls" value="1" <?= !empty($cfg['smtp_use_tls']) ? 'checked' : '' ?>>
                Использовать TLS
            </label>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Email отправителя</label>
                <input type="email" name="from_email" value="<?= e($cfg['from_email'] ?? '') ?>" placeholder="info@efix.ru">
            </div>
            <div class="form-group">
                <label>Имя отправителя</label>
                <input type="text" name="from_name" value="<?= e($cfg['from_name'] ?? '') ?>" placeholder="eFix">
            </div>
        </div>
        <div class="form-group">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
                <input type="checkbox" name="notify_on_new_request" value="1" <?= !empty($cfg['notify_on_new_request']) ? 'checked' : '' ?>>
                Уведомлять о новых заявках
            </label>
        </div>
        <div class="form-group">
            <label>Email для уведомлений</label>
            <input type="email" name="notify_email" value="<?= e($cfg['notify_email'] ?? '') ?>">
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn--primary">Сохранить</button>
            <button type="button" class="btn btn--outline" onclick="sendTest()">Отправить тест</button>
        </div>
    </form>
    <div id="testResult" style="margin-top:12px;font-size:13px"></div>
</div>
<script>
function sendTest() {
    const div = document.getElementById('testResult');
    div.textContent = 'Отправка...';
    fetch('/admin/api/send-test-email', { method: 'POST' })
        .then(r => r.json())
        .then(d => { div.innerHTML = d.ok ? '<span style="color:#065F46">' + d.message + '</span>' : '<span style="color:#DC2626">' + (d.error || 'Ошибка') + '</span>'; })
        .catch(e => { div.innerHTML = '<span style="color:#DC2626">' + e.message + '</span>'; });
}
</script>
<?php
$content = ob_get_clean();
include __DIR__ . '/base.php';
?>
