<?php
$title = 'Прайс-лист — ' . ($site_name ?? 'eFix') . ' Admin';
$header = 'Редактирование прайс-листа';
ob_start();
?>
<div class="card">
    <div class="card__header">
        <h2 class="card__title">Все позиции</h2>
        <div style="display:flex;gap:8px">
            <form method="GET" style="display:flex;gap:8px">
                <input type="hidden" name="page" value="1">
                <input type="hidden" name="per_page" value="<?= $perPage ?? 10 ?>">
                <select name="type" onchange="this.form.submit()" style="padding:6px 10px;border:2px solid var(--border);border-radius:var(--radius-sm);font-size:13px">
                    <option value="">Все типы</option>
                    <option value="phone" <?= ($deviceType ?? '') === 'phone' ? 'selected' : '' ?>>Телефоны</option>
                    <option value="tablet" <?= ($deviceType ?? '') === 'tablet' ? 'selected' : '' ?>>Планшеты</option>
                    <option value="laptop" <?= ($deviceType ?? '') === 'laptop' ? 'selected' : '' ?>>Ноутбуки</option>
                    <option value="pc" <?= ($deviceType ?? '') === 'pc' ? 'selected' : '' ?>>ПК</option>
                </select>
            </form>
            <button class="btn btn--primary" onclick="openModal('addModal')">+ Добавить</button>
        </div>
    </div>
    <?php if(!empty($items ?? $prices ?? [])): ?>
    <?php $items = $items ?? $prices ?? []; ?>
    <div class="table-wrap" x-data="bulkDelete()">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px" x-show="anySelected" x-cloak>
            <span class="text-sm" style="color:var(--text-muted)">Выбрано: <strong x-text="selectedCount"></strong></span>
            <button class="btn btn--danger btn--sm" @click="if(!anySelected) return; if(!confirm('Удалить '+selectedCount+' записей?')) return; $refs.bulkIds.value=selected.join(','); $refs.bulkForm.submit()">Удалить выбранные</button>
        </div>
        <table>
            <thead>
                <tr>
                    <th style="width:36px"><input type="checkbox" @click="toggleAll($el.closest('.table-wrap'))" :checked="anySelected && selected.length === $el.closest('.table-wrap').querySelectorAll('.bulk-select').length"></th>
                    <th>ID</th>
                    <th>Тип</th>
                    <th>Бренд</th>
                    <th>Модель</th>
                    <th>Услуга</th>
                    <th>Цена от</th>
                    <th>Цена до</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($items as $item): ?>
                <tr>
                    <td style="width:36px"><input type="checkbox" class="bulk-select" :value="<?= $item['id'] ?>" x-model="selected"></td>
                    <td><?= $item['id'] ?></td>
                    <td><span class="badge badge--<?= e($item['device_type']) ?>"><?= e($item['device_type']) ?></span></td>
                    <td><?= e($item['brand'] ?? '') ?></td>
                    <td><?= e($item['model_name'] ?? '') ?></td>
                    <td><?= e($item['service'] ?? '') ?></td>
                    <td><?= e($item['price_from'] ?? '') ?></td>
                    <td><?= e($item['price_to'] ?? '—') ?></td>
                    <td>
                        <span class="badge badge--<?= ($item['is_active'] ?? 0) ? 'active' : 'inactive' ?>">
                            <?= ($item['is_active'] ?? 0) ? 'Активна' : 'Скрыта' ?>
                        </span>
                    </td>
                    <td>
                        <div class="table-actions">
                            <form method="POST" class="inline-form">
                                <input type="hidden" name="action" value="toggle">
                                <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                <button type="submit" class="btn btn--outline btn--sm"><?= ($item['is_active'] ?? 0) ? 'Скрыть' : 'Показать' ?></button>
                            </form>
                            <button class="btn btn--outline btn--sm" onclick='editPrice(<?= json_encode($item['id']) ?>, <?= json_encode($item['device_type']) ?>, <?= json_encode($item['brand'] ?? '—') ?>, <?= json_encode($item['model_name']) ?>, <?= json_encode($item['service']) ?>, <?= json_encode($item['price_from']) ?>, <?= json_encode($item['price_to'] ?? null) ?>)'>&#9998;</button>
                            <form method="POST" class="inline-form" onsubmit="return confirm('Удалить позицию?')">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                <button type="submit" class="btn btn--danger btn--sm">&#10005;</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
        <?php if(($totalPages ?? 0) > 1): ?>
        <div class="pagination">
            <a class="pagination__btn" href="<?= url_for('admin.prices', ['page' => ($page ?? 1) - 1, 'per_page' => $perPage ?? 10, 'type' => $deviceType ?? '']) ?>" <?= !($hasPrev ?? ($page ?? 1) > 1) ? 'disabled' : '' ?>>&#8249;</a>
            <?php for($p = 1; $p <= ($totalPages ?? 1); $p++): ?>
            <a class="pagination__btn <?= $p == ($page ?? 1) ? 'pagination__btn--active' : '' ?>" href="<?= url_for('admin.prices', ['page' => $p, 'per_page' => $perPage ?? 10, 'type' => $deviceType ?? '']) ?>"><?= $p ?></a>
            <?php endfor ?>
            <a class="pagination__btn" href="<?= url_for('admin.prices', ['page' => ($page ?? 1) + 1, 'per_page' => $perPage ?? 10, 'type' => $deviceType ?? '']) ?>" <?= !($hasNext ?? ($page ?? 1) < ($totalPages ?? 1)) ? 'disabled' : '' ?>>&#8250;</a>
        </div>
        <?php endif ?>
        <div class="pagination-info">
            <span class="pagination-info__text"><?= $totalItems ?? count($items) ?> записей, страница <?= $page ?? 1 ?> из <?= $totalPages ?? 1 ?></span>
            <label class="pagination-info__per-page">
                Показывать:
                <select onchange="setPerPage(this.value)">
                    <option value="10" <?= ($perPage ?? 10) == 10 ? 'selected' : '' ?>>10</option>
                    <option value="25" <?= ($perPage ?? 10) == 25 ? 'selected' : '' ?>>25</option>
                    <option value="50" <?= ($perPage ?? 10) == 50 ? 'selected' : '' ?>>50</option>
                    <option value="100" <?= ($perPage ?? 10) == 100 ? 'selected' : '' ?>>100</option>
                </select>
            </label>
        </div>
        <form method="POST" x-ref="bulkForm" style="display:none">
            <input type="hidden" name="action" value="bulk_delete">
            <input type="hidden" name="ids" x-ref="bulkIds">
        </form>
    </div>
    <?php else: ?>
    <div class="empty">Позиций не найдено</div>
    <?php endif ?>
</div>

<div class="modal-overlay" id="addModal">
    <div class="modal">
        <div class="modal__header">
            <h3 class="modal__title">Добавить позицию</h3>
            <button class="modal__close" onclick="closeModal('addModal')">&#10005;</button>
        </div>
        <div class="modal__body">
            <form method="POST">
                <input type="hidden" name="action" value="add">
                <div class="form-row">
                    <div class="form-group">
                        <label>Тип устройства *</label>
                        <select name="device_type" required>
                            <option value="phone">Телефоны</option>
                            <option value="tablet">Планшеты</option>
                            <option value="laptop">Ноутбуки</option>
                            <option value="pc">ПК</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Бренд</label>
                        <input name="brand" placeholder="Apple, Samsung...">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Модель *</label>
                        <input name="model_name" required placeholder="iPhone 14">
                    </div>
                    <div class="form-group">
                        <label>Услуга *</label>
                        <input name="service" required placeholder="Замена дисплея">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Цена от *</label>
                        <input name="price_from" type="number" required value="0">
                    </div>
                    <div class="form-group">
                        <label>Цена до</label>
                        <input name="price_to" type="number" placeholder="Оставьте пустым, если нет диапазона">
                    </div>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn--outline" onclick="closeModal('addModal')">Отмена</button>
                    <button type="submit" class="btn btn--primary">Добавить</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal-overlay" id="editModal">
    <div class="modal">
        <div class="modal__header">
            <h3 class="modal__title">Редактировать позицию</h3>
            <button class="modal__close" onclick="closeModal('editModal')">&#10005;</button>
        </div>
        <div class="modal__body">
            <form method="POST">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" id="edit-id">
                <div class="form-row">
                    <div class="form-group">
                        <label>Тип устройства</label>
                        <select name="device_type" id="edit-device_type">
                            <option value="phone">Телефоны</option>
                            <option value="tablet">Планшеты</option>
                            <option value="laptop">Ноутбуки</option>
                            <option value="pc">ПК</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Бренд</label>
                        <input name="brand" id="edit-brand">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Модель</label>
                        <input name="model_name" id="edit-model_name" required>
                    </div>
                    <div class="form-group">
                        <label>Услуга</label>
                        <input name="service" id="edit-service" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Цена от</label>
                        <input name="price_from" id="edit-price_from" type="number" required>
                    </div>
                    <div class="form-group">
                        <label>Цена до</label>
                        <input name="price_to" id="edit-price_to" type="number">
                    </div>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn--outline" onclick="closeModal('editModal')">Отмена</button>
                    <button type="submit" class="btn btn--primary">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
ob_start();
?>
<script>
function openModal(id) { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }
function editPrice(id, type, brand, model, service, from, to) {
    document.getElementById('edit-id').value = id;
    document.getElementById('edit-device_type').value = type;
    document.getElementById('edit-brand').value = brand === '—' ? '' : brand;
    document.getElementById('edit-model_name').value = model;
    document.getElementById('edit-service').value = service;
    document.getElementById('edit-price_from').value = from;
    document.getElementById('edit-price_to').value = to || '';
    openModal('editModal');
}
document.querySelectorAll('.modal-overlay').forEach(el => {
    el.addEventListener('click', function(e) { if (e.target === this) this.classList.remove('open'); });
});
</script>
<?php
$extra_scripts = ob_get_clean();
include __DIR__ . '/base.php';
?>
