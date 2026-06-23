<?php $title = 'О нас — ' . e($sc['site_name'] ?? 'eFix') . '. Сервисный центр в Новосибирске' ?>
<?php $extra_head = '<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">
<style>[x-cloak] { display: none !important; }</style>' ?>
<?php ob_start() ?>
<section class="page-hero">
    <div class="container">
        <h1 class="page-hero__title"><?= $sc['about_title'] ?? ('О компании ' . ($sc['site_name'] ?? 'eFix')) ?></h1>
        <p class="page-hero__desc"><?= e($sc['about_subtitle'] ?? 'Выездной сервисный центр — ремонтируем технику там, где вам удобно.') ?></p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="about-content">
            <div class="about-text">
                <h2>Как всё начиналось</h2>
                <p><?= e($sc['about_text'] ?? ($sc['site_name'] ?? 'eFix') . ' — это команда профессиональных инженеров, которые объединились, чтобы сделать ремонт цифровой техники простым, быстрым и доступным. Мы работаем в Новосибирске с 2019 года. Наш принцип — забрать устройство у вас, отремонтировать в мастерской на профессиональном оборудовании и вернуть обратно. Никаких очередей и ожидания в сервисе.') ?></p>
                <div class="about-stats">
                    <div class="about-stat">
                        <span class="about-stat__num"><?= e($sc['stat_years_num'] ?? '5 лет') ?></span>
                        <span class="about-stat__label"><?= e($sc['stat_years_label'] ?? 'успешной работы') ?></span>
                    </div>
                    <div class="about-stat">
                        <span class="about-stat__num"><?= e($sc['stat_repaired_num'] ?? '1500+') ?></span>
                        <span class="about-stat__label"><?= e($sc['stat_repaired_label'] ?? 'отремонтированных устройств') ?></span>
                    </div>
                    <div class="about-stat">
                        <span class="about-stat__num"><?= e($sc['stat_satisfaction_num'] ?? '98%') ?></span>
                        <span class="about-stat__label">положительных отзывов</span>
                    </div>
                    <div class="about-stat">
                        <span class="about-stat__num"><?= e($sc['stat_pickup_num'] ?? '1 час') ?></span>
                        <span class="about-stat__label"><?= e($sc['stat_pickup_label'] ?? 'среднее время забора') ?></span>
                    </div>
                </div>
            </div>
            <div class="about-values">
                <h2>Наши ценности</h2>
                <div class="values-list">
                    <div class="value-item">
                        <div class="value-item__icon">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        </div>
                        <div>
                            <h3>Честность</h3>
                            <p>Говорим правду о поломке и стоимости. Без накруток и скрытых платежей.</p>
                        </div>
                    </div>
                    <div class="value-item">
                        <div class="value-item__icon">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                        </div>
                        <div>
                            <h3>Скорость</h3>
                            <p>Ценим ваше время. Забираем устройство за 1 час, ремонт в мастерской — от 2 часов.</p>
                        </div>
                    </div>
                    <div class="value-item">
                        <div class="value-item__icon">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0 1 12 2.944a11.955 11.955 0 0 1-8.618 3.04A12.02 12.02 0 0 0 3 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <div>
                            <h3>Гарантия</h3>
                            <p>Уверены в качестве работы. Даём гарантию до 1 года на все виды ремонта.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section section--alt">
    <div class="container">
        <div class="section__header">
            <span class="section__badge">Партнёрская сеть</span>
            <h2 class="section__title">Сотрудничаем с мастерскими по всему городу</h2>
            <p class="section__desc">
                Мы объединили десятки проверенных мастерских по всему Новосибирску. 
                Работаем со всеми поставщиками запчастей, поэтому находим любые комплектующие 
                по лучшим ценам. Ваше устройство попадёт в руки профильного специалиста 
                с нужным оборудованием.
            </p>
        </div>
        <div class="partner-stats">
            <div class="partner-stat">
                <span class="partner-stat__num">14</span>
                <span class="partner-stat__label">точек приёма по городу</span>
            </div>
            <div class="partner-stat">
                <span class="partner-stat__num">7</span>
                <span class="partner-stat__label">районов Новосибирска</span>
            </div>
            <div class="partner-stat">
                <span class="partner-stat__num">12</span>
                <span class="partner-stat__label">категорий техники</span>
            </div>
        </div>
    </div>
</section>

<section class="section" id="map-section">
    <div class="container">
        <div class="section__header">
            <span class="section__badge">Карта мастерских</span>
            <h2 class="section__title">Партнёрские сервисы на карте</h2>
            <p class="section__desc">Забираем устройство у вас и доставляем в проверенный сервис рядом с вами</p>
        </div>
        <div class="map-wrap" id="map"></div>
    </div>
</section>

<section class="section section--alt">
    <div class="container">
        <div class="section__header">
            <span class="section__badge">График работы</span>
            <h2 class="section__title">Большинство сервисов открыты ежедневно</h2>
            <p class="section__desc">Точное время работы каждого партнёра указано на карте в карточке сервиса.</p>
        </div>
        <div class="schedule">
            <div class="schedule__item"><span>Пн — Вс</span><span>10:00 — 20:00</span></div>
            <div class="schedule__item"><span>ТРЦ (Аура, Сибирский Молл)</span><span>10:00 — 22:00</span></div>
            <div class="schedule__item"><span>Заявки через <?= e($sc['site_name'] ?? 'eFix') ?></span><span>круглосуточно</span></div>
        </div>
    </div>
</section>

<section class="section cta-section">
    <div class="container">
        <div class="cta-card">
            <div class="cta-card__content">
                <h2 class="cta-card__title"><?= $sc['cta_about_title'] ?? 'Станьте нашим клиентом' ?></h2>
                <p class="cta-card__desc"><?= e($sc['cta_about_desc'] ?? 'Оставьте заявку прямо сейчас и получите скидку 10% на первый ремонт!') ?></p>
                <a href="<?= url_for('main.contacts') ?>" class="btn btn--large btn--primary">Оставить заявку</a>
            </div>
        </div>
    </div>
</section>
<?php $content = ob_get_clean() ?>
<?php ob_start() ?>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
document.addEventListener('DOMContentLoaded', async () => {
    const resp = await fetch('/api/workshops');
    const workshops = await resp.json();
    if (!workshops.length) return;

    const map = L.map('map').setView([55.0084, 82.9357], 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://openstreetmap.org">OSM</a>',
        maxZoom: 18,
    }).addTo(map);

    const icon = L.divIcon({
        html: '<svg width="28" height="28" viewBox="0 0 24 24" fill="#FF6B35" stroke="#fff" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3" fill="#fff" stroke="none"/></svg>',
        className: 'marker-icon',
        iconSize: [28, 28],
        iconAnchor: [14, 28],
    });

    workshops.forEach(w => {
        const popup = `
            <div class="map-popup">
                <h4>Партнёрский сервис <?= e($sc['site_name'] ?? 'eFix') ?></h4>
                <p class="map-popup__desc">${w.desc}</p>
            </div>
        `;
        L.marker([w.lat, w.lng], { icon }).addTo(map).bindPopup(popup);
    });

    if (workshops.length === 1) {
        map.setView([workshops[0].lat, workshops[0].lng], 15);
    } else {
        map.fitBounds(workshops.map(w => [w.lat, w.lng]), { padding: [50, 50] });
    }
});
</script>
<?php $extra_scripts = ob_get_clean() ?>
<?php include __DIR__ . '/base.php' ?>
