<div class="dashboard">
    <h1 class="admin-title">Дашборд</h1>
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card__value"><?= $leadsNew ?></div>
            <div class="stat-card__label">Новых заявок</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__value"><?= $leadsTotal ?></div>
            <div class="stat-card__label">Всего заявок</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__value"><?= $servicesCount ?></div>
            <div class="stat-card__label">Активных услуг</div>
        </div>
    </div>

    <h2 class="admin-subtitle">Последние заявки</h2>
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Телефон</th>
                <th>Услуга</th>
                <th>Дата</th>
                <th>Статус</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach (array_slice($recentLeads, 0, 10) as $lead): ?>
            <tr>
                <td>#<?= $lead['id'] ?></td>
                <td><?= $this->escape($lead['name']) ?></td>
                <td><?= $this->escape($lead['phone']) ?></td>
                <td><?= $this->escape($lead['service_type']) ?></td>
                <td><?= $this->escape($lead['created_at']) ?></td>
                <td><span class="badge badge--<?= $lead['status'] ?>"><?= $lead['status'] ?></span></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
