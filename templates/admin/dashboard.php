<?php
$title = 'Дашборд — ' . ($site_name ?? 'eFix') . ' Admin';
$header = 'Дашборд';
$extra_head = <<<HTML
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js" crossorigin="anonymous"></script>
<style>
.chart-spark{height:80px;margin-top:4px}
.stat-card--sm{padding:14px 16px}
.stat-card--sm .stat-card__num{font-size:22px}
.stat-card--sm .stat-card__label{font-size:11px}
.dash-two{display:grid;grid-template-columns:1fr 1fr;gap:20px}
@media(max-width:768px){.dash-two{grid-template-columns:1fr}}
.activity-list{list-style:none;padding:0}
.activity-list li{display:flex;align-items:center;gap:8px;padding:6px 0;font-size:13px;border-bottom:1px solid var(--border)}
.activity-list li:last-child{border-bottom:none}
.activity-list__path{font-family:monospace;font-size:12px;color:var(--text);font-weight:500;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.activity-list__time{color:var(--text-light);font-size:11px;margin-left:auto;white-space:nowrap}
.activity-list__ip{color:var(--text-muted);font-size:11px;font-family:monospace}
</style>
HTML;
ob_start();
?>
<div class="stats">
    <div class="stat-card">
        <div class="stat-card__num"><?= $requests_count ?? 0 ?></div>
        <div class="stat-card__label">Всего заявок</div>
    </div>
    <div class="stat-card">
        <div class="stat-card__num"><?= $views_today ?? 0 ?></div>
        <div class="stat-card__label">Просмотров сегодня</div>
    </div>
    <div class="stat-card">
        <div class="stat-card__num"><?= $unique_today ?? 0 ?></div>
        <div class="stat-card__label">Уникальных сегодня</div>
    </div>
    <div class="stat-card">
        <div class="stat-card__num"><?= $views_week ?? 0 ?></div>
        <div class="stat-card__label">За неделю</div>
    </div>
    <div class="stat-card">
        <div class="stat-card__num"><?= $views_month ?? 0 ?></div>
        <div class="stat-card__label">За месяц</div>
    </div>
    <div class="stat-card">
        <div class="stat-card__num"><?= $prices_count ?? 0 ?></div>
        <div class="stat-card__label">Позиций в прайсе</div>
    </div>
</div>

<div class="dash-two">
    <div class="card">
        <div class="card__header">
            <h2 class="card__title">Просмотры за месяц</h2>
            <a href="<?= url_for('admin.stats') ?>" class="btn btn--outline btn--sm">Подробнее</a>
        </div>
        <div class="chart-spark"><canvas id="sparkChart"></canvas></div>
    </div>
    <div class="card">
        <div class="card__header">
            <h2 class="card__title">Последние визиты</h2>
            <a href="<?= url_for('admin.stats') ?>" class="btn btn--outline btn--sm">Вся аналитика</a>
        </div>
        <ul class="activity-list">
            <?php if(!empty($recent_views)): ?>
            <?php foreach($recent_views as $v): ?>
            <li>
                <span class="activity-list__path"><?= e($v['path']) ?></span>
                <span class="activity-list__ip"><?= e($v['ip'] ?? '—') ?></span>
                <span class="activity-list__time"><?= date('H:i', strtotime($v['created_at'])) ?></span>
            </li>
            <?php endforeach ?>
            <?php else: ?>
            <li style="color:var(--text-light);justify-content:center">Пока нет активности</li>
            <?php endif ?>
        </ul>
    </div>
</div>

<div class="dash-two">
    <div class="card">
        <div class="card__header"><h2 class="card__title">Популярные страницы</h2></div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Страница</th><th>Просмотры</th></tr></thead>
                <tbody>
                    <?php if(!empty($top_pages)): ?>
                    <?php foreach($top_pages as $p): ?>
                    <tr><td style="font-family:monospace;font-size:13px"><?= e($p['path']) ?></td><td><?= e($p['cnt']) ?></td></tr>
                    <?php endforeach ?>
                    <?php else: ?>
                    <tr><td colspan="2" class="empty">Нет данных</td></tr>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card">
        <div class="card__header"><h2 class="card__title">Быстрые ссылки</h2></div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
            <a href="<?= url_for('admin.site') ?>" class="btn btn--outline" style="justify-content:center;padding:12px">Информация сайта</a>
            <a href="<?= url_for('admin.services') ?>" class="btn btn--outline" style="justify-content:center;padding:12px">Услуги</a>
            <a href="<?= url_for('admin.prices') ?>" class="btn btn--outline" style="justify-content:center;padding:12px">Прайс-лист</a>
            <a href="<?= url_for('admin.workshops') ?>" class="btn btn--outline" style="justify-content:center;padding:12px">Мастерские</a>
            <a href="<?= url_for('admin.requests_list') ?>" class="btn btn--outline" style="justify-content:center;padding:12px">Заявки</a>
            <a href="<?= url_for('admin.stats') ?>" class="btn btn--outline" style="justify-content:center;padding:12px">Аналитика</a>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
ob_start();
?>
<script>
document.addEventListener('alpine:init', () => {
    const ctx = document.getElementById('sparkChart');
    if (!ctx) return;
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($chart_labels ?? [], JSON_UNESCAPED_UNICODE) ?>,
            datasets: [{
                data: <?= json_encode($chart_values ?? [], JSON_UNESCAPED_UNICODE) ?>,
                borderColor: '#FF6B35',
                backgroundColor: 'rgba(255,107,53,.08)',
                fill: true,
                tension: .3,
                pointRadius: 0,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { enabled: true } },
            scales: {
                x: { display: false },
                y: { display: false, beginAtZero: true }
            },
            elements: { point: { radius: 0 } }
        }
    });
});
</script>
<?php
$extra_scripts = ob_get_clean();
include __DIR__ . '/base.php';
?>
