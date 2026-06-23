<?php
$pPage = $page ?? 1;
$pPages = $totalPages ?? 1;
$pPerPage = $perPage ?? 10;
$pTotal = $totalItems ?? 0;
$pHasPrev = $hasPrev ?? ($pPage > 1);
$pHasNext = $hasNext ?? ($pPage < $pPages);
$pPrevNum = max(1, $pPage - 1);
$pNextNum = min($pPages, $pPage + 1);
?>
<?php if(!empty($workshops)): ?>
<table>
    <thead>
        <tr>
            <th style="width:36px"><input type="checkbox" @click="toggleAll($el.closest('.table-wrap'))" :checked="anySelected && selected.length === $el.closest('.table-wrap').querySelectorAll('.bulk-select').length"></th>
            <th>ID</th>
            <th>Название</th>
            <th>Адрес</th>
            <th>Координаты</th>
            <th>Телефон</th>
            <th>Статус</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody id="workshops-table-body">
        <?php include __DIR__ . '/_workshops_rows.php' ?>
    </tbody>
</table>
<?php else: ?>
<div class="empty">Мастерских нет.</div>
<?php endif ?>

<?php if($pPages > 1): ?>
<div class="pagination">
    <button class="pagination__btn" <?= !$pHasPrev ? 'disabled' : '' ?>
            hx-get="<?= url_for('admin.workshops', ['page' => $pPrevNum, 'per_page' => $pPerPage]) ?>" hx-target="#workshops-table-container" hx-swap="innerHTML">&#8249;</button>
    <?php for($p = 1; $p <= $pPages; $p++): ?>
        <button class="pagination__btn <?= $p == $pPage ? 'pagination__btn--active' : '' ?>"
                hx-get="<?= url_for('admin.workshops', ['page' => $p, 'per_page' => $pPerPage]) ?>" hx-target="#workshops-table-container" hx-swap="innerHTML"><?= $p ?></button>
    <?php endfor ?>
    <button class="pagination__btn" <?= !$pHasNext ? 'disabled' : '' ?>
            hx-get="<?= url_for('admin.workshops', ['page' => $pNextNum, 'per_page' => $pPerPage]) ?>" hx-target="#workshops-table-container" hx-swap="innerHTML">&#8250;</button>
</div>
<?php endif ?>

<div class="pagination-info">
    <span class="pagination-info__text"><?= $pTotal ?> записей, страница <?= $pPage ?> из <?= $pPages ?></span>
    <label class="pagination-info__per-page">
        Показывать:
        <select onchange="setPerPage(this.value, true)">
            <option value="10" <?= $pPerPage == 10 ? 'selected' : '' ?>>10</option>
            <option value="25" <?= $pPerPage == 25 ? 'selected' : '' ?>>25</option>
            <option value="50" <?= $pPerPage == 50 ? 'selected' : '' ?>>50</option>
            <option value="100" <?= $pPerPage == 100 ? 'selected' : '' ?>>100</option>
        </select>
    </label>
</div>

<form id="bulk-form" method="POST" hx-post="<?= url_for('admin.workshops', ['page' => $pPage, 'per_page' => $pPerPage]) ?>" hx-target="#workshops-table-container" hx-swap="innerHTML">
    <input type="hidden" name="action" value="bulk_delete">
    <input type="hidden" name="ids" value="">
</form>
