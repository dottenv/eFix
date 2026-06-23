<?php
$title = 'Шаблоны писем — ' . ($site_name ?? 'eFix');
$header = 'Шаблоны писем';
ob_start();
?>
<div class="card">
    <div class="card__header">
        <span class="card__title">Шаблоны</span>
        <a href="<?= url_for('admin.mail_template_add') ?>" class="btn btn--primary">+ Добавить</a>
    </div>
    <?php if(!empty($templates)): ?>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Название</th>
                    <th>Тема</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($templates as $t): ?>
                <tr>
                    <td><?= e($t['name']) ?></td>
                    <td><?= e($t['subject']) ?></td>
                    <td>
                        <div class="table-actions">
                            <a href="/admin/mail-templates/<?= $t['id'] ?>/edit" class="btn btn--outline btn--sm">Редактировать</a>
                            <form method="POST" action="/admin/mail-templates/<?= $t['id'] ?>/delete" class="inline-form" onsubmit="return confirm('Удалить шаблон?')">
                                <button class="btn btn--danger btn--sm">Удалить</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="empty">Нет шаблонов. <a href="<?= url_for('admin.mail_template_add') ?>">Добавить первый</a></div>
    <?php endif ?>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/base.php';
?>
