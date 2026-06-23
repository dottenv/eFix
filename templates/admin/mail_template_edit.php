<?php
$title = (isset($tpl) ? 'Редактировать' : 'Добавить') . ' шаблон — ' . ($site_name ?? 'eFix');
$header = (isset($tpl) ? 'Редактировать' : 'Добавить') . ' шаблон';
ob_start();
?>
<div class="card" style="max-width:640px">
    <?php if(!empty($error)): ?><div style="background:#FEE2E2;color:#DC2626;padding:10px 16px;border-radius:8px;margin-bottom:16px;font-size:13px"><?= e($error) ?></div><?php endif ?>
    <form method="POST">
        <div class="form-group">
            <label>Название</label>
            <input type="text" name="name" value="<?= e($tpl['name'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Тема письма</label>
            <input type="text" name="subject" value="<?= e($tpl['subject'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Текст письма</label>
            <textarea name="body" rows="12" required style="width:100%;padding:10px 14px;border:2px solid #E5E7EB;border-radius:8px;font-family:var(--font);font-size:14px;resize:vertical"><?= e($tpl['body'] ?? '') ?></textarea>
            <div style="font-size:12px;color:#9CA3AF;margin-top:4px">Доступные переменные: <code>{name}</code> — имя клиента, <code>{phone}</code> — телефон, <code>{status}</code> — статус заявки</div>
        </div>
        <div class="form-actions">
            <a href="<?= url_for('admin.mail_templates') ?>" class="btn btn--outline">Отмена</a>
            <button type="submit" class="btn btn--primary"><?= isset($tpl) ? 'Сохранить' : 'Добавить' ?></button>
        </div>
    </form>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/base.php';
?>
