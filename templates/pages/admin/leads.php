<div class="leads-page">
    <h1 class="admin-title">Заявки</h1>

    <div class="admin-tabs">
        <a href="/admin/leads" class="admin-tab <?= $currentStatus === null ? 'admin-tab--active' : '' ?>">
            Все <span class="badge"><?= $countNew + $countContacted + $countClosed ?></span>
        </a>
        <a href="/admin/leads?status=new" class="admin-tab <?= $currentStatus === 'new' ? 'admin-tab--active' : '' ?>">
            Новые <span class="badge badge--new"><?= $countNew ?></span>
        </a>
        <a href="/admin/leads?status=contacted" class="admin-tab <?= $currentStatus === 'contacted' ? 'admin-tab--active' : '' ?>">
            В работе <span class="badge badge--contacted"><?= $countContacted ?></span>
        </a>
        <a href="/admin/leads?status=closed" class="admin-tab <?= $currentStatus === 'closed' ? 'admin-tab--active' : '' ?>">
            Закрыты <span class="badge badge--closed"><?= $countClosed ?></span>
        </a>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Телефон</th>
                <th>Email</th>
                <th>Услуга</th>
                <th>Устройство</th>
                <th>Сообщение</th>
                <th>Дата</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($leads)): ?>
            <tr><td colspan="10">Нет заявок</td></tr>
            <?php endif; ?>
            <?php foreach ($leads as $lead): ?>
            <tr>
                <td>#<?= $lead['id'] ?></td>
                <td><?= $this->escape($lead['name']) ?></td>
                <td><?= $this->escape($lead['phone']) ?></td>
                <td><?= $this->escape($lead['email'] ?? '—') ?></td>
                <td><?= $this->escape($lead['service_type']) ?></td>
                <td><?= $this->escape($lead['device_brand'] ?? '') ?> <?= $this->escape($lead['device_model'] ?? '') ?></td>
                <td><?= $this->escape(mb_substr($lead['message'] ?? '—', 0, 50)) ?></td>
                <td><?= $this->escape($lead['created_at']) ?></td>
                <td>
                    <select class="status-select" data-id="<?= $lead['id'] ?>" onchange="updateStatus(this)">
                        <option value="new" <?= $lead['status'] === 'new' ? 'selected' : '' ?>>Новый</option>
                        <option value="contacted" <?= $lead['status'] === 'contacted' ? 'selected' : '' ?>>В работе</option>
                        <option value="closed" <?= $lead['status'] === 'closed' ? 'selected' : '' ?>>Закрыт</option>
                    </select>
                </td>
                <td>
                    <button class="btn btn--danger btn--sm" onclick="deleteLead(<?= $lead['id'] ?>)">Удалить</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
async function updateStatus(select) {
    const id = select.dataset.id;
    const status = select.value;
    await fetch('/admin/leads/' + id + '/status', {
        method: 'POST',
        body: new URLSearchParams({ status }),
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    });
}

async function deleteLead(id) {
    if (!confirm('Удалить заявку?')) return;
    await fetch('/admin/leads/' + id + '/delete', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    });
    location.reload();
}
</script>
