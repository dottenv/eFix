<?php
$title = 'Аналитика — ' . ($site_name ?? 'eFix') . ' Admin';
$header = 'Аналитика';
$extra_head = <<<HTML
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<style>
.an{animation-duration:.6s;animation-fill-mode:both}
@keyframes anFadeUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}
@keyframes anFadeIn{from{opacity:0}to{opacity:1}}
@keyframes anScale{from{opacity:0;transform:scale(.8)}to{opacity:1;transform:scale(1)}}
@keyframes pulse-ring{0%{transform:scale(1);opacity:1}100%{transform:scale(3);opacity:0}}
.an-fade-up{animation-name:anFadeUp}
.an-fade-in{animation-name:anFadeIn}
.an-scale{animation-name:anScale}
.an-delay-1{animation-delay:.05s}
.an-delay-2{animation-delay:.1s}
.an-delay-3{animation-delay:.15s}
.an-delay-4{animation-delay:.2s}
.an-delay-5{animation-delay:.25s}
.an-delay-6{animation-delay:.3s}
.an-delay-7{animation-delay:.35s}
.an-delay-8{animation-delay:.4s}

.analytics-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:28px}
.stat-glow{position:relative;padding:20px;border-radius:var(--radius);overflow:hidden;transition:transform .25s,box-shadow .25s}
.stat-glow:hover{transform:translateY(-2px);box-shadow:0 8px 32px rgba(11,36,71,.12)}
.stat-glow__bg{position:absolute;inset:0;z-index:0}
.stat-glow__content{position:relative;z-index:1}
.stat-glow__num{font-size:30px;font-weight:800;line-height:1.1}
.stat-glow__label{font-size:12px;font-weight:500;margin-top:4px;opacity:.8}
.stat-glow__icon{position:absolute;right:16px;bottom:12px;width:40px;height:40px;opacity:.08;z-index:1}
.stat-glow__icon svg{width:100%;height:100%}
.stat-glow--primary{background:linear-gradient(135deg,#0B2447,#19376D);color:#fff}
.stat-glow--accent{background:linear-gradient(135deg,#FF6B35,#E85D2C);color:#fff}
.stat-glow--surface{background:var(--surface);border:1px solid var(--border);color:var(--text)}
.stat-glow--surface .stat-glow__num{color:var(--primary)}
.stat-glow--success{background:linear-gradient(135deg,#059669,#10B981);color:#fff}
.stat-glow--info{background:linear-gradient(135deg,#6366F1,#818CF8);color:#fff}

.two-col{display:grid;grid-template-columns:1fr 1fr;gap:20px}
.three-col{display:grid;grid-template-columns:1fr 1fr 1fr;gap:20px}
@media(max-width:768px){.two-col,.three-col{grid-template-columns:1fr}}

.chart-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);padding:20px;margin-bottom:20px;transition:box-shadow .2s}
.chart-card:hover{box-shadow:0 4px 20px rgba(0,0,0,.06)}
.chart-card__header{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;gap:12px;flex-wrap:wrap}
.chart-card__title{font-weight:700;font-size:15px;color:var(--text);display:flex;align-items:center;gap:8px}
.chart-card__title svg{width:16px;height:16px;color:var(--accent);flex-shrink:0}
.chart-card__badge{font-size:10px;font-weight:600;padding:2px 8px;border-radius:100px;background:rgba(255,107,53,.1);color:var(--accent)}
.chart-wrap{position:relative;height:260px}
.chart-wrap--sm{height:180px}
.chart-wrap--xs{height:140px}

.map-card{padding:0;overflow:hidden;border-radius:var(--radius);border:1px solid var(--border);margin-bottom:20px}
.map-card .chart-card__header{padding:20px 20px 0;margin-bottom:12px}
.map-wrap{height:420px;width:100%}

.pulse-marker{width:16px;height:16px;background:var(--accent);border-radius:50%;border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,.3)}
.pulse-marker::after{content:'';position:absolute;inset:-8px;border-radius:50%;border:2px solid var(--accent);animation:pulse-ring 2s cubic-bezier(.215,.61,.355,1) infinite;pointer-events:none}

.mini-table{width:100%;font-size:13px}
.mini-table th{padding:6px 8px;text-align:left;font-weight:600;font-size:11px;color:var(--text-muted);background:var(--bg);border-bottom:1px solid var(--border);white-space:nowrap}
.mini-table td{padding:6px 8px;border-bottom:1px solid var(--border)}
.mini-table tbody tr:hover td{background:rgba(255,107,53,.03)}
.mini-table .num{font-weight:700;font-family:monospace;text-align:right}
.mini-table .bar{display:inline-block;height:6px;border-radius:3px;background:linear-gradient(90deg,var(--accent),rgba(255,107,53,.3));min-width:4px;transition:width .6s ease}

.scroll-x{overflow-x:auto}
.pct-bar{display:flex;align-items:center;gap:8px}
.pct-bar__track{flex:1;height:6px;border-radius:3px;background:var(--bg);overflow:hidden}
.pct-bar__fill{height:100%;border-radius:3px;background:linear-gradient(90deg,var(--accent),rgba(255,107,53,.4));transition:width .8s ease}
.pct-bar__label{font-size:12px;color:var(--text-muted);white-space:nowrap;min-width:36px;text-align:right}

.realtime-item{display:flex;align-items:center;gap:8px;padding:5px 0;font-size:12px;border-bottom:1px solid var(--border);color:var(--text-muted)}
.realtime-item:last-child{border-bottom:none}
.realtime-item__path{font-weight:500;color:var(--text);max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-family:monospace;font-size:11px}
.realtime-dot{width:7px;height:7px;border-radius:50%;background:var(--success);flex-shrink:0;animation:pulse 2s ease-in-out infinite}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:.3}}
</style>
HTML;
$headerContent = <<<HTML
Аналитика
<button class="btn btn--outline btn--sm" onclick="refreshAll()" id="refreshBtn" style="margin-left:auto" title="Обновить данные">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="transition:transform .3s" id="refreshIcon"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
    <span style="margin-left:4px">Обновить</span>
</button>
HTML;
ob_start();
?>
<div class="analytics-grid" id="statsGrid"></div>

<div class="two-col">
    <div class="chart-card an an-fade-up an-delay-2">
        <div class="chart-card__header">
            <div class="chart-card__title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                Просмотры по дням
            </div>
            <span class="chart-card__badge">14 дней</span>
        </div>
        <div class="chart-wrap"><canvas id="pageViewsChart"></canvas></div>
    </div>
    <div class="chart-card an an-fade-up an-delay-3">
        <div class="chart-card__header">
            <div class="chart-card__title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-6.219-8.56"/><circle cx="12" cy="12" r="3"/></svg>
                Типы устройств
            </div>
            <span class="chart-card__badge">User-Agent</span>
        </div>
        <div class="chart-wrap chart-wrap--sm"><canvas id="deviceChart"></canvas></div>
    </div>
</div>

<div class="map-card an an-fade-up an-delay-3">
    <div class="chart-card__header">
        <div class="chart-card__title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            География посетителей
        </div>
        <span class="chart-card__badge" id="mapCount">0 точек</span>
    </div>
    <div class="map-wrap" id="map"></div>
</div>

<div class="three-col an an-fade-up an-delay-4">
    <div class="chart-card">
        <div class="chart-card__header"><div class="chart-card__title">Браузеры</div></div>
        <div id="browsersChart" class="chart-wrap chart-wrap--xs"></div>
    </div>
    <div class="chart-card">
        <div class="chart-card__header"><div class="chart-card__title">ОС</div></div>
        <div id="osChart" class="chart-wrap chart-wrap--xs"></div>
    </div>
    <div class="chart-card">
        <div class="chart-card__header"><div class="chart-card__title">Экраны</div></div>
        <div class="scroll-x"><table class="mini-table" id="screensTable"><tbody></tbody></table></div>
    </div>
</div>

<div class="two-col an an-fade-up an-delay-4">
    <div class="chart-card">
        <div class="chart-card__header"><div class="chart-card__title">UTM-метки</div></div>
        <div class="scroll-x" style="max-height:260px;overflow-y:auto"><table class="mini-table" id="utmsTable"><tbody></tbody></table></div>
    </div>
    <div class="chart-card">
        <div class="chart-card__header"><div class="chart-card__title">Языки</div></div>
        <div class="scroll-x"><table class="mini-table" id="langsTable"><tbody></tbody></table></div>
    </div>
</div>

<div class="two-col an an-fade-up an-delay-5">
    <div class="chart-card">
        <div class="chart-card__header"><div class="chart-card__title">Страницы</div></div>
        <div class="scroll-x"><table class="mini-table" id="pagesTable"><tbody></tbody></table></div>
    </div>
    <div class="chart-card">
        <div class="chart-card__header"><div class="chart-card__title">Referrers</div></div>
        <div class="scroll-x"><table class="mini-table" id="referrersTable"><tbody></tbody></table></div>
    </div>
</div>

<div class="two-col an an-fade-up an-delay-6">
    <div class="chart-card">
        <div class="chart-card__header"><div class="chart-card__title">Частые поиски</div></div>
        <div class="scroll-x"><table class="mini-table" id="freqSearchesTable"><tbody></tbody></table></div>
    </div>
    <div class="chart-card">
        <div class="chart-card__header"><div class="chart-card__title">Формы</div></div>
        <div class="scroll-x"><table class="mini-table" id="formsTable"><tbody></tbody></table></div>
    </div>
</div>

<div class="chart-card an an-fade-up an-delay-7">
    <div class="chart-card__header">
        <div class="chart-card__title">
            <span class="realtime-dot"></span>
            Активность сейчас
        </div>
        <span class="chart-card__badge">15 мин</span>
    </div>
    <div id="realtimeList" style="max-height:280px;overflow-y:auto"></div>
</div>
<?php
$content = ob_get_clean();
ob_start();
?>
<script>
document.addEventListener('alpine:init', () => {
    let pageViewsChart, deviceChart, leafletMap, browsersChart, osChart;
    const COLORS = { accent: '#FF6B35', primary: '#0B2447', success: '#10B981', info: '#6366F1', warning: '#F59E0B' };

    async function loadJSON(url) { const r = await fetch(url); return r.json(); }

    function animateCounter(el, target) {
        const duration = 1200, steps = 30;
        const increment = target / steps;
        let current = 0, step = 0;
        const timer = setInterval(() => {
            step++;
            current = Math.round(increment * step);
            if (step >= steps) { current = target; clearInterval(timer); }
            el.textContent = current.toLocaleString('ru-RU');
        }, duration / steps);
    }

    function renderStatsGrid(data) {
        const grid = document.getElementById('statsGrid');
        const cards = [
            { num: data.viewsToday, label: 'Просмотров сегодня', cls: 'stat-glow--primary', icon: 'M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z' },
            { num: data.uniqueToday, label: 'Уникальных сегодня', cls: 'stat-glow--accent', icon: 'M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z' },
            { num: data.viewsWeek, label: 'За неделю', cls: 'stat-glow--info', icon: 'M9 19v-6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2zm0 0V9a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v10m-6 0a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2m0 0V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2z' },
            { num: data.viewsMonth, label: 'За месяц', cls: 'stat-glow--surface', icon: 'M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4m14-8l-4-4m0 0L7 7m4-4v12' },
            { num: data.uniqueMonth, label: 'Уникальных за месяц', cls: 'stat-glow--success', icon: 'M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2' },
            { num: data.requestsCount, label: 'Заявок', cls: 'stat-glow--accent', icon: 'M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z' },
            { num: data.totalViews, label: 'Всего просмотров', cls: 'stat-glow--primary', icon: 'M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z' },
            { num: data.sessions, label: 'Сессий', cls: 'stat-glow--info', icon: 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z' },
        ];
        grid.innerHTML = cards.map((c, i) =>
            `<div class="stat-glow ${c.cls} an an-scale an-delay-${Math.min(i, 8)}" style="animation-duration:.4s">
                <div class="stat-glow__content">
                    <div class="stat-glow__num" id="statNum${i}">0</div>
                    <div class="stat-glow__label">${c.label}</div>
                </div>
                <div class="stat-glow__icon"><svg viewBox="0 0 24 24" fill="currentColor"><path d="${c.icon}"/></svg></div>
            </div>`
        ).join('');
        cards.forEach((c, i) => {
            const el = document.getElementById('statNum' + i);
            if (el) animateCounter(el, c.num);
        });
    }

    function renderPageViews(data) {
        const ctx = document.getElementById('pageViewsChart').getContext('2d');
        const grad = ctx.createLinearGradient(0, 0, 0, 260);
        grad.addColorStop(0, 'rgba(255,107,53,.25)');
        grad.addColorStop(1, 'rgba(255,107,53,.01)');
        pageViewsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    data: data.values,
                    borderColor: COLORS.accent,
                    backgroundColor: grad,
                    fill: true,
                    tension: .35,
                    pointRadius: 3,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: COLORS.accent,
                    pointBorderWidth: 2,
                    borderWidth: 2.5
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                interaction: { intersect: false, mode: 'index' },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0B2447',
                        titleFont: { size: 12 },
                        bodyFont: { size: 13, weight: 'bold' },
                        padding: 10,
                        cornerRadius: 8,
                        displayColors: false
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { maxTicksLimit: 7, font: { size: 10 }, color: '#9CA3AF' } },
                    y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 10 }, color: '#9CA3AF' }, grid: { color: 'rgba(0,0,0,.04)' } }
                }
            }
        });
    }

    function renderDeviceChart(data) {
        const ctx = document.getElementById('deviceChart').getContext('2d');
        deviceChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: data.map(d => d.label),
                datasets: [{
                    data: data.map(d => d.count),
                    backgroundColor: [COLORS.accent, COLORS.primary],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: { position: 'bottom', labels: { font: { size: 11 }, padding: 14, usePointStyle: true, pointStyle: 'circle' } }
                }
            }
        });
    }

    function renderMap(points) {
        const el = document.getElementById('map');
        if (!el) return;
        leafletMap = L.map('map', { zoomControl: true }).setView([55.75, 37.61], 3);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18, attribution: '&copy; <a href="https://openstreetmap.org">OSM</a>'
        }).addTo(leafletMap);
        document.getElementById('mapCount').textContent = points.length + ' точек';
        if (points.length === 0) return;
        const bounds = [];
        points.forEach(p => {
            const icon = L.divIcon({
                className: '',
                html: '<div class="pulse-marker"></div>',
                iconSize: [16, 16],
                iconAnchor: [8, 8]
            });
            const marker = L.marker([p.lat, p.lng], { icon }).addTo(leafletMap);
            marker.bindTooltip(p.city ? p.city + (p.country ? ', ' + p.country : '') : p.country || '',
                { direction: 'top', offset: [0, -10] });
            bounds.push([p.lat, p.lng]);
        });
        if (bounds.length > 1) leafletMap.fitBounds(bounds, { padding: [40, 40], maxZoom: 10 });
        setTimeout(() => leafletMap.invalidateSize(), 400);
    }

    function renderBarChart(containerId, data, color) {
        const container = document.getElementById(containerId);
        if (!container || data.length === 0) { if (container) container.innerHTML = '<div style="text-align:center;padding:24px;color:var(--text-light);font-size:13px">Нет данных</div>'; return; }
        const max = Math.max(...data.map(d => d.count), 1);
        container.innerHTML = data.map(d =>
            `<div class="pct-bar" style="margin-bottom:6px">
                <span style="flex:0 0 70px;font-size:12px;color:var(--text)">${d.label}</span>
                <div class="pct-bar__track"><div class="pct-bar__fill" style="width:${(d.count / max * 100).toFixed(1)}%"></div></div>
                <span class="pct-bar__label">${d.count}</span>
            </div>`
        ).join('');
    }

    function renderMiniTable(tableId, data, cols) {
        const tbody = document.querySelector('#' + tableId + ' tbody');
        if (!tbody) return;
        if (!data || data.length === 0) { tbody.innerHTML = '<tr><td colspan="99" style="text-align:center;padding:24px;color:var(--text-light);font-size:13px">Нет данных</td></tr>'; return; }
        tbody.innerHTML = data.map(row => {
            let cells = cols.map(c => {
                let val = row[c.key];
                if (c.class) val = `<span class="${c.class}">${val || '—'}</span>`;
                else if (val === undefined || val === null || val === '') val = '—';
                if (c.mono) val = `<span style="font-family:monospace;font-size:12px">${val}</span>`;
                return `<td>${val}</td>`;
            }).join('');
            return '<tr>' + cells + '</tr>';
        }).join('');
    }

    async function init() {
        const [stats, pageViews, deviceBreakdown, locations, browsers, os, screens,
               utms, langs, pages, referrers, freqSearches, forms, realtime] =
            await Promise.all([
                loadJSON('/admin/api/stats/summary'),
                loadJSON('/admin/api/stats/page-views?days=14'),
                loadJSON('/admin/api/stats/device-types'),
                loadJSON('/admin/api/stats/locations'),
                loadJSON('/admin/api/stats/browsers'),
                loadJSON('/admin/api/stats/os'),
                loadJSON('/admin/api/stats/screens'),
                loadJSON('/admin/api/stats/utms'),
                loadJSON('/admin/api/stats/languages'),
                loadJSON('/admin/api/stats/pages'),
                loadJSON('/admin/api/stats/referrers'),
                loadJSON('/admin/api/stats/frequent-searches'),
                loadJSON('/admin/api/stats/forms'),
                loadJSON('/admin/api/stats/realtime'),
            ]);
        renderStatsGrid(stats);
        renderPageViews(pageViews);
        renderDeviceChart(deviceBreakdown);
        renderMap(locations);
        renderBarChart('browsersChart', browsers, COLORS.accent);
        renderBarChart('osChart', os, COLORS.info);
        renderMiniTable('screensTable', screens, [{ key: 'screen', label: 'Экран' }, { key: 'count', label: 'Кол-во', class: 'num' }]);
        renderMiniTable('utmsTable', utms, [
            { key: 'source', label: 'Source', mono: true }, { key: 'medium', label: 'Medium', mono: true },
            { key: 'campaign', label: 'Campaign', mono: true }, { key: 'count', label: 'Клики', class: 'num' }
        ]);
        renderMiniTable('langsTable', langs, [{ key: 'lang', label: 'Язык', mono: true }, { key: 'count', label: 'Кол-во', class: 'num' }]);
        renderMiniTable('pagesTable', pages, [{ key: 'path', label: 'Страница', mono: true }, { key: 'count', label: 'Просмотры', class: 'num' }]);
        renderMiniTable('referrersTable', referrers, [{ key: 'referrer', label: 'Referrer', mono: true }, { key: 'count', label: 'Переходы', class: 'num' }]);
        renderMiniTable('freqSearchesTable', freqSearches, [{ key: 'query', label: 'Запрос', mono: true }, { key: 'count', label: 'Раз', class: 'num' }]);
        renderMiniTable('formsTable', forms, [{ key: 'form', label: 'Форма' }, { key: 'action', label: 'Действие' }, { key: 'count', label: 'Кол-во', class: 'num' }]);
        renderRealtime(realtime);
    }

    function renderRealtime(data) {
        const el = document.getElementById('realtimeList');
        if (!data || data.length === 0) {
            el.innerHTML = '<div style="text-align:center;padding:32px;color:var(--text-light);font-size:13px">Нет активности за последние 15 минут</div>';
            return;
        }
        const oldHtml = el.innerHTML;
        const newHtml = data.map(r =>
            `<div class="realtime-item">
                <span class="realtime-dot"></span>
                <span class="realtime-item__path">${r.path}</span>
                <span style="color:var(--text-light);font-size:11px">${r.ip || '—'}</span>
                <span style="margin-left:auto;font-size:11px">${new Date(r.created_at).toLocaleTimeString('ru-RU', {hour:'2-digit',minute:'2-digit'})}</span>
            </div>`
        ).join('');
        if (oldHtml !== newHtml) {
            el.innerHTML = newHtml;
            el.querySelectorAll('.realtime-item').forEach((item, i) => {
                if (i < 3) { item.style.animation = 'none'; item.offsetHeight;
                    item.style.animation = 'anFadeUp .4s ease forwards'; item.style.animationDelay = (i * 0.05) + 's'; }
            });
        }
    }

    async function refreshStats() {
        try {
            const data = await loadJSON('/admin/api/stats/summary');
            const grid = document.getElementById('statsGrid');
            const cards = grid.querySelectorAll('.stat-glow');
            const nums = [
                data.viewsToday, data.uniqueToday, data.viewsWeek, data.viewsMonth,
                data.uniqueMonth, data.requestsCount, data.totalViews, data.sessions
            ];
            cards.forEach((card, i) => {
                const el = card.querySelector('.stat-glow__num');
                if (el && nums[i] !== undefined) animateCounter(el, nums[i]);
            });
        } catch(e) {}
    }

    async function refreshRealtime() {
        try {
            const data = await loadJSON('/admin/api/stats/realtime');
            renderRealtime(data);
        } catch(e) {}
    }

    async function refreshAll() {
        const icon = document.getElementById('refreshIcon');
        const btn = document.getElementById('refreshBtn');
        if (btn) btn.disabled = true;
        if (icon) icon.style.transform = 'rotate(360deg)';
        await Promise.all([refreshStats(), refreshRealtime()]);
        if (icon) setTimeout(() => icon.style.transform = '', 400);
        if (btn) btn.disabled = false;
    }

    init();
    setInterval(refreshRealtime, 15000);
    setInterval(refreshStats, 30000);
});
</script>
<?php
$extra_scripts = ob_get_clean();
include __DIR__ . '/base.php';
?>
