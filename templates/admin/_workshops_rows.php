<?php if(!empty($workshops)): ?>
<?php foreach($workshops as $w): ?>
<tr>
    <td style="width:36px"><input type="checkbox" class="bulk-select" :value="<?= $w['id'] ?>" x-model="selected"></td>
    <td><?= $w['id'] ?></td>
    <td><strong><?= e($w['name'] ?? '—') ?></strong></td>
    <td><?= e($w['address'] ?? '—') ?></td>
    <td><?= e($w['lat']) ?>, <?= e($w['lng']) ?></td>
    <td><?= e($w['phone'] ?? '—') ?></td>
    <td>
        <span class="badge badge--<?= ($w['is_active'] ?? 0) ? 'active' : 'inactive' ?>">
            <?= ($w['is_active'] ?? 0) ? 'Активна' : 'Скрыта' ?>
        </span>
    </td>
    <td>
        <div class="table-actions">
            <button class="btn btn--outline btn--sm" hx-post="<?= url_for('admin.workshops', ['page' => $page ?? 1, 'per_page' => $perPage ?? 10]) ?>" hx-vals='{"action": "toggle", "id": <?= $w['id'] ?>}' hx-target="#workshops-table-container" hx-swap="innerHTML"><?= ($w['is_active'] ?? 0) ? 'Скрыть' : 'Показать' ?></button>
            <button class="btn btn--outline btn--sm" @click='openEdit(<?= json_encode($w['id']) ?>, <?= json_encode($w['name'] ?? '') ?>, <?= json_encode($w['address'] ?? '') ?>, <?= json_encode($w['lat']) ?>, <?= json_encode($w['lng']) ?>, <?= json_encode($w['phone'] ?? '') ?>, <?= json_encode($w['description'] ?? '') ?>)'>&#9998;</button>
            <button class="btn btn--danger btn--sm" hx-post="<?= url_for('admin.workshops', ['page' => $page ?? 1, 'per_page' => $perPage ?? 10]) ?>" hx-vals='{"action": "delete", "id": <?= $w['id'] ?>}' hx-target="#workshops-table-container" hx-swap="innerHTML" hx-confirm="Удалить мастерскую?">&#10005;</button>
        </div>
    </td>
</tr>
<?php endforeach ?>
<?php else: ?>
<tr><td colspan="8" class="empty">Мастерских нет.</td></tr>
<?php endif ?>
