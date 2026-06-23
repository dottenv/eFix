<?php
$title = 'Услуги — ' . ($site_name ?? 'eFix') . ' Admin';
$header = 'Управление услугами';
ob_start();
?>
<div class="card">
    <div class="card__header">
        <h2 class="card__title">Список услуг</h2>
        <button class="btn btn--primary" onclick="openModal('addModal')">+ Добавить услугу</button>
    </div>
    <?php if(!empty($services)): ?>
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
                    <th>Название</th>
                    <th>Категория</th>
                    <th>Цена</th>
                    <th>Порядок</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($services as $s): ?>
                <tr>
                    <td style="width:36px"><input type="checkbox" class="bulk-select" :value="<?= $s['id'] ?>" x-model="selected"></td>
                    <td><?= $s['id'] ?></td>
                    <td><strong><?= e($s['title']) ?></strong></td>
                    <td><?= e($s['category'] ?? '—') ?></td>
                    <td><?= e($s['price'] ?? '—') ?></td>
                    <td><?= $s['sort_order'] ?? $s['order'] ?? 0 ?></td>
                    <td>
                        <div class="table-actions">
                            <button class="btn btn--outline btn--sm" onclick='editService(<?= json_encode($s['id']) ?>, <?= json_encode($s['title']) ?>, <?= json_encode($s['description'] ?? '') ?>, <?= json_encode($s['price'] ?? '') ?>, <?= json_encode($s['icon'] ?? '') ?>, <?= json_encode($s['category'] ?? '') ?>, <?= json_encode($s['sort_order'] ?? $s['order'] ?? 0) ?>)'>&#9998;</button>
                            <form method="POST" class="inline-form" onsubmit="return confirm('Удалить услугу?')">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $s['id'] ?>">
                                <button type="submit" class="btn btn--danger btn--sm">&#10005;</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
        <?php if(($totalServices ?? 0) > ($perPage ?? 10)): ?>
        <div class="pagination">
            <a class="pagination__btn" href="<?= url_for('admin.services', ['page' => $page - 1]) ?>" <?= ($page ?? 1) <= 1 ? 'disabled' : '' ?>>&#8249;</a>
            <?php for($p = 1; $p <= ($totalPages ?? 1); $p++): ?>
            <a class="pagination__btn <?= $p == ($page ?? 1) ? 'pagination__btn--active' : '' ?>" href="<?= url_for('admin.services', ['page' => $p, 'per_page' => $perPage ?? 10]) ?>"><?= $p ?></a>
            <?php endfor ?>
            <a class="pagination__btn" href="<?= url_for('admin.services', ['page' => ($page ?? 1) + 1]) ?>" <?= ($page ?? 1) >= ($totalPages ?? 1) ? 'disabled' : '' ?>>&#8250;</a>
        </div>
        <?php endif ?>
        <div class="pagination-info">
            <span class="pagination-info__text"><?= $totalServices ?? count($services) ?> записей, страница <?= $page ?? 1 ?> из <?= $totalPages ?? 1 ?></span>
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
    <div class="empty">Услуг пока нет. Добавьте первую услугу.</div>
    <?php endif ?>
</div>

<div class="modal-overlay" id="addModal">
    <div class="modal">
        <div class="modal__header">
            <h3 class="modal__title">Добавить услугу</h3>
            <button class="modal__close" onclick="closeModal('addModal')">&#10005;</button>
        </div>
        <div class="modal__body">
            <form method="POST">
                <input type="hidden" name="action" value="add">
                <div class="form-group">
                    <label>Название *</label>
                    <input name="title" required>
                </div>
                <div class="form-group">
                    <label>Описание</label>
                    <textarea name="description" rows="3"></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Цена (текст)</label>
                        <input name="price" placeholder="от 500 ₽">
                    </div>
                    <div class="form-group">
                        <label>Категория</label>
                        <input name="category" placeholder="phones, tablets, laptops, pc">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Иконка (SVG-код)</label>
                        <textarea name="icon" rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Порядок сортировки</label>
                        <input name="sort_order" type="number" value="0">
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
            <h3 class="modal__title">Редактировать услугу</h3>
            <button class="modal__close" onclick="closeModal('editModal')">&#10005;</button>
        </div>
        <div class="modal__body">
            <form method="POST">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" id="edit-id">
                <div class="form-group">
                    <label>Название *</label>
                    <input name="title" id="edit-title" required>
                </div>
                <div class="form-group">
                    <label>Описание</label>
                    <textarea name="description" id="edit-description" rows="3"></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Цена (текст)</label>
                        <input name="price" id="edit-price" placeholder="от 500 ₽">
                    </div>
                    <div class="form-group">
                        <label>Категория</label>
                        <input name="category" id="edit-category" placeholder="phones, tablets, laptops, pc">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Иконка (SVG-код)</label>
                        <textarea name="icon" id="edit-icon" rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Порядок сортировки</label>
                        <input name="sort_order" id="edit-order" type="number" value="0">
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
function editService(id, title, desc, price, icon, category, order) {
    document.getElementById('edit-id').value = id;
    document.getElementById('edit-title').value = title;
    document.getElementById('edit-description').value = desc || '';
    document.getElementById('edit-price').value = price || '';
    document.getElementById('edit-icon').value = icon || '';
    document.getElementById('edit-category').value = category || '';
    document.getElementById('edit-order').value = order;
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
