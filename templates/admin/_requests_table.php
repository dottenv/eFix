<?php
$rPage = $page ?? 1;
$rPages = $totalPages ?? 1;
$rPerPage = $perPage ?? 10;
$rTotal = $totalItems ?? 0;
$rStatus = $statusFilter ?? '';
$rSearch = $search ?? '';
$rDateFrom = $dateFrom ?? '';
$rDateTo = $dateTo ?? '';
?>
<div class="card" style="padding:0;overflow:hidden" id="requestsTableWrap">
    <div style="padding:16px 20px 0;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px">
        <span class="text-muted text-sm">Всего: <?= $rTotal ?></span>
        <span class="text-muted text-sm" x-show="newCount > 0" style="color:var(--accent);font-weight:600" x-text="'Новых: ' + newCount"></span>
    </div>
    <?php if(!empty($requests)): ?>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width:32px"><input type="checkbox" class="request-checkbox" @change="toggleAll($event.target.checked)"></th>
                    <th style="width:50px">ID</th>
                    <th>Клиент</th>
                    <th>Телефон</th>
                    <th>Устройство</th>
                    <th>Проблема</th>
                    <th>Статус</th>
                    <th style="width:80px">Дата</th>
                    <th style="width:100px"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($requests as $r): ?>
                <tr class="request-<?= e($r['status']) ?><?= $r['status'] === 'new' ? ' request-new' : '' ?>">
                    <td><input type="checkbox" class="request-checkbox" value="<?= $r['id'] ?>" x-model="selected"></td>
                    <td class="text-muted" style="font-size:12px">#<?= $r['id'] ?></td>
                    <td><strong><?= e($r['name']) ?></strong></td>
                    <td><a href="tel:<?= e($r['phone']) ?>" class="request-phone"><?= e($r['phone']) ?></a></td>
                    <td style="font-size:13px">
                        <?php if(!empty($r['device_type']) || !empty($r['device_model'])): ?>
                            <?php if(!empty($r['device_type'])): ?><span class="badge badge--<?= e($r['device_type']) ?>" style="font-size:10px"><?= e(device_label($r['device_type'])) ?></span><?php endif ?>
                            <?= e($r['device_model'] ?? '') ?>
                        <?php else: ?>&mdash;<?php endif ?>
                    </td>
                    <td><span class="request-preview" title="<?= e($r['message'] ?? '') ?>"><?= e($r['message'] ?? '—') ?></span></td>
                    <td><span class="badge badge--<?= e($r['status']) ?>" style="font-size:11px"><?= e(status_label($r['status'])) ?></span></td>
                    <td><span class="request-time"><?= date('d.m H:i', strtotime($r['created_at'])) ?></span></td>
                    <td>
                        <div class="request-actions">
                            <button class="btn btn--outline btn--sm" @click="openDetail(<?= $r['id'] ?>)" title="Просмотр"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
                            <?php if($r['status'] !== 'archived'): ?>
                            <button class="btn btn--outline btn--sm" @click="doAction(<?= $r['id'] ?>, 'archive')" title="В архив"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="21 8 21 21 3 21 3 8"/><rect x="1" y="3" width="22" height="5"/><line x1="10" y1="12" x2="14" y2="12"/></svg></button>
                            <?php endif ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
        <?php if($rPages > 1): ?>
        <div class="pagination" style="padding:12px 20px" hx-boost="true" hx-target="#requestsTableWrap" hx-swap="outerHTML" hx-select="#requestsTableWrap">
            <a class="pagination__btn" href="<?= url_for('admin.requests_list', ['page' => $rPage - 1, 'per_page' => $rPerPage, 'status' => $rStatus, 'q' => $rSearch, 'from' => $rDateFrom, 'to' => $rDateTo]) ?>" <?= $rPage <= 1 ? 'disabled' : '' ?>>&#8249;</a>
            <?php for($p = 1; $p <= $rPages; $p++): ?>
            <a class="pagination__btn <?= $p == $rPage ? 'pagination__btn--active' : '' ?>" href="<?= url_for('admin.requests_list', ['page' => $p, 'per_page' => $rPerPage, 'status' => $rStatus, 'q' => $rSearch, 'from' => $rDateFrom, 'to' => $rDateTo]) ?>"><?= $p ?></a>
            <?php endfor ?>
            <a class="pagination__btn" href="<?= url_for('admin.requests_list', ['page' => $rPage + 1, 'per_page' => $rPerPage, 'status' => $rStatus, 'q' => $rSearch, 'from' => $rDateFrom, 'to' => $rDateTo]) ?>" <?= $rPage >= $rPages ? 'disabled' : '' ?>>&#8250;</a>
        </div>
        <?php endif ?>
        <div class="pagination-info" style="padding:0 20px 16px">
            <span class="pagination-info__text">Страница <?= $rPage ?> из <?= $rPages ?></span>
            <label class="pagination-info__per-page">
                Показывать:
                <select @change="document.getElementById('filterPerPage').value=$event.target.value;refreshTable()">
                    <option value="10" <?= $rPerPage == 10 ? 'selected' : '' ?>>10</option>
                    <option value="25" <?= $rPerPage == 25 ? 'selected' : '' ?>>25</option>
                    <option value="50" <?= $rPerPage == 50 ? 'selected' : '' ?>>50</option>
                    <option value="100" <?= $rPerPage == 100 ? 'selected' : '' ?>>100</option>
                </select>
            </label>
        </div>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <div class="empty-state__icon"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 14l-5-5 1.41-1.41L12 14.17l4.59-4.58L18 11l-6 6z"/></svg></div>
        <div class="empty-state__text">Заявок нет</div>
        <div class="empty-state__sub"><?= !empty($rStatus) ? 'Попробуйте изменить фильтр' : 'Новые заявки появятся здесь' ?></div>
    </div>
    <?php endif ?>
</div>
