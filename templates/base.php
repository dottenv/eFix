<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? ($sc['meta_title'] ?? ($sc['site_name'] ?? 'eFix') . ' — Выездной сервисный центр в Новосибирске')) ?></title>
    <meta name="description" content="<?= e($meta_description ?? ($sc['meta_description'] ?? ($sc['site_name'] ?? 'eFix') . ' — ремонт цифровой техники в Новосибирске с выездом. Телефоны, планшеты, ноутбуки, ПК. Бесплатная диагностика, гарантия.')) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Montserrat:wght@500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/htmx.org@2.0.4" crossorigin="anonymous"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.socket.io/4.7.5/socket.io.min.js"></script>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>&#9881;</text></svg>">
    <link rel="stylesheet" href="/static/css/style.css">
    <?= $extra_head ?? '' ?>
    <?= render_hook('public_head_end', get_defined_vars()) ?>
    <script defer src="/static/js/main.js"></script>
</head>
<body>
    <header class="header" id="header">
        <div class="container header__inner">
            <a href="<?= url_for('main.index') ?>" class="logo">
                <span class="logo__icon">&#9881;</span>
                <span class="logo__text">e<span class="logo__accent">Fix</span></span>
            </a>
            <nav class="nav" id="nav">
                <ul class="nav__list">
                    <li><a href="<?= url_for('main.index') ?>" class="nav__link <?= $active === 'home' ? 'nav__link--active' : '' ?>">Главная</a></li>
                    <li><a href="<?= url_for('main.services') ?>" class="nav__link <?= $active === 'services' ? 'nav__link--active' : '' ?>">Услуги</a></li>
                    <li><a href="<?= url_for('main.prices') ?>" class="nav__link <?= $active === 'prices' ? 'nav__link--active' : '' ?>">Цены</a></li>
                    <li><a href="<?= url_for('main.about') ?>" class="nav__link <?= $active === 'about' ? 'nav__link--active' : '' ?>">О нас</a></li>
                    <li><a href="<?= url_for('main.contacts') ?>" class="nav__link <?= $active === 'contacts' ? 'nav__link--active' : '' ?>">Контакты</a></li>
                    <?= render_hook('public_nav_items', get_defined_vars()) ?>
                </ul>
            </nav>
            <div class="header__contacts">
                    <a href="tel:<?= e($sc['phone'] ?? '+7 (999) 999-99-99') ?>" class="header__phone"><?= e($sc['phone'] ?? '+7 (999) 999-99-99') ?></a>
                    <span class="header__work-hours"><?= e($sc['work_hours'] ?? 'Ежедневно с 09:00 до 21:00') ?></span>
                <a href="<?= url_for('main.contacts') ?>" class="btn btn--small btn--primary">Заказать звонок</a>
            </div>
            <button class="burger" id="burger" aria-label="Меню">
                <span></span><span></span><span></span>
            </button>
        </div>
    </header>

    <main>
        <?= $content ?? '' ?>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer__grid">
                <div class="footer__col">
                    <a href="<?= url_for('main.index') ?>" class="logo logo--footer">
                        <span class="logo__icon">&#9881;</span>
                        <span class="logo__text">e<span class="logo__accent">Fix</span></span>
                    </a>
                    <p class="footer__desc"><?= e($sc['footer_description'] ?? 'Выездной сервисный центр в Новосибирске. Ремонтируем цифровую технику с заботой о вашем времени.') ?></p>
                </div>
                <div class="footer__col">
                    <h4 class="footer__title">Услуги</h4>
                    <ul class="footer__links">
                        <li><a href="<?= url_for('main.services') ?>#phones">Ремонт телефонов</a></li>
                        <li><a href="<?= url_for('main.services') ?>#tablets">Ремонт планшетов</a></li>
                        <li><a href="<?= url_for('main.services') ?>#laptops">Ремонт ноутбуков</a></li>
                        <li><a href="<?= url_for('main.services') ?>#pc">Ремонт ПК</a></li>
                    </ul>
                </div>
                <div class="footer__col">
                    <h4 class="footer__title">Информация</h4>
                    <ul class="footer__links">
                        <li><a href="<?= url_for('main.about') ?>">О нас</a></li>
                        <li><a href="<?= url_for('main.prices') ?>">Цены</a></li>
                        <li><a href="<?= url_for('main.contacts') ?>">Контакты</a></li>
                    </ul>
                </div>
                <div class="footer__col">
                    <h4 class="footer__title">Контакты</h4>
                    <ul class="footer__contacts">
                        <li>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            <?= e($sc['address_short'] ?? 'Новосибирск, выезд по городу') ?>
                        </li>
                        <li>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            <a href="tel:<?= e($sc['phone'] ?? '+7 (999) 999-99-99') ?>"><?= e($sc['phone'] ?? '+7 (999) 999-99-99') ?></a>
                        </li>
                        <li>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            <a href="mailto:<?= e($sc['email'] ?? 'info@efix.ru') ?>"><?= e($sc['email'] ?? 'info@efix.ru') ?></a>
                        </li>
                    </ul>
                    <?php $socials = [
                        ['social_whatsapp', 'WhatsApp', '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>'],
                        ['social_telegram', 'Telegram', '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>'],
                        ['social_vk', 'VK', '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 1.1 3 1.5 3 1.5z"/></svg>'],
                        ['social_instagram', 'Instagram', '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>'],
                        ['social_youtube', 'YouTube', '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58 2.78 2.78 0 0 0 1.94 2C5.12 20 12 20 12 20s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02"/></svg>'],
                    ] ?>
                    <div class="footer__social">
                        <?php foreach($socials as $s): ?>
                            <?php $prefix = $s[0]; $name = $s[1]; $icon = $s[2]; ?>
                            <?php if (($sc[$prefix . '_enabled'] ?? '1') === '1' && !empty($sc[$prefix . '_url'])): ?>
                            <a href="<?= e($sc[$prefix . '_url']) ?>" class="footer__social-link" target="_blank" rel="noopener noreferrer" aria-label="<?= e($name) ?>"><?= $icon ?></a>
                            <?php endif ?>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
            <div class="footer__bottom">
                <p><?= e($sc['copyright'] ?? '© 2024 eFix. Все права защищены.') ?></p>
            </div>
        </div>
    </footer>

    <button class="callback-fab" onclick="document.dispatchEvent(new Event('openModalCallback'))" aria-label="Заказать звонок">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
    </button>
    <?php include __DIR__ . '/_modal_callback.php' ?>
    <?= $extra_scripts ?? '' ?>
    <?= render_hook('public_body_end', get_defined_vars()) ?>
</body>
</html>
