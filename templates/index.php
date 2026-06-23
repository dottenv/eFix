<?php $title = e($sc['site_name'] ?? 'eFix') . ' — Ремонт цифровой техники в Новосибирске с выездом' ?>
<?php ob_start() ?>
<section class="hero" id="hero">
    <div class="hero__bg"></div>
    <div class="container hero__inner">
        <div class="hero__content">
            <span class="hero__badge"><?= e($sc['hero_badge'] ?? 'Выездной сервисный центр') ?></span>
            <h1 class="hero__title">
                <?= $sc['hero_title'] ?? 'Ремонтируем цифровую технику —<br>заберём, починим, вернём' ?>
            </h1>
            <p class="hero__subtitle">
                <?= e($sc['hero_subtitle'] ?? 'Телефоны, планшеты, ноутбуки, ПК. Бесплатная диагностика, гарантия до 1 года. Приедем, заберём устройство и вернём обратно после ремонта.') ?>
            </p>
            <div class="hero__actions">
                <a href="<?= url_for('main.contacts') ?>" class="btn btn--large btn--primary"><?= e($sc['cta_button_text'] ?? 'Вызвать мастера') ?></a>
                <a href="<?= url_for('main.prices') ?>" class="btn btn--large btn--outline"><?= e($sc['prices_button_text'] ?? 'Смотреть цены') ?></a>
            </div>
            <div class="hero__stats">
                <div class="stat">
                    <span class="stat__num"><?= e($sc['stat_years_num'] ?? '5+') ?></span>
                    <span class="stat__label"><?= e($sc['stat_years_label'] ?? 'лет опыта') ?></span>
                </div>
                <div class="stat">
                    <span class="stat__num"><?= e($sc['stat_repaired_num'] ?? '1500+') ?></span>
                    <span class="stat__label"><?= e($sc['stat_repaired_label'] ?? 'отремонтировано') ?></span>
                </div>
                <div class="stat">
                    <span class="stat__num"><?= e($sc['stat_satisfaction_num'] ?? '98%') ?></span>
                    <span class="stat__label"><?= e($sc['stat_satisfaction_label'] ?? 'довольных клиентов') ?></span>
                </div>
            </div>
        </div>
        <div class="hero__form-wrap">
            <div class="hero__form-card">
                <h3 class="hero__form-title">Закажите обратный звонок</h3>
                <p class="hero__form-subtitle">Мастер перезвонит через 15 минут и согласует удобное время выезда</p>
                <?php include __DIR__ . '/_callback_form.php' ?>
            </div>
        </div>
    </div>
</section>

<section class="section services-preview" id="services">
    <div class="container">
        <div class="section__header">
            <span class="section__badge">Что мы ремонтируем</span>
            <h2 class="section__title">Услуги сервисного центра</h2>
            <p class="section__desc">Ремонтируем любую цифровую технику. Выезжаем по всему Новосибирску.</p>
        </div>
        <div class="services-grid">
            <a href="<?= url_for('main.services') ?>#phones" class="service-card">
                <div class="service-card__icon">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
                </div>
                <h3 class="service-card__title">Телефоны</h3>
                <p class="service-card__desc">Замена экрана, аккумулятора, разъёмов, ремонт после воды и любой сложности.</p>
                <span class="service-card__price">от 500 ₽</span>
            </a>
            <a href="<?= url_for('main.services') ?>#tablets" class="service-card">
                <div class="service-card__icon">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="4" y="2" width="16" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
                </div>
                <h3 class="service-card__title">Планшеты</h3>
                <p class="service-card__desc">Замена стекла, дисплея, батареи, восстановление после ударов и залития.</p>
                <span class="service-card__price">от 800 ₽</span>
            </a>
            <a href="<?= url_for('main.services') ?>#laptops" class="service-card">
                <div class="service-card__icon">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="2" y1="20" x2="22" y2="20"/></svg>
                </div>
                <h3 class="service-card__title">Ноутбуки</h3>
                <p class="service-card__desc">Замена матрицы, клавиатуры, термопасты, чистка от пыли, модернизация.</p>
                <span class="service-card__price">от 1000 ₽</span>
            </a>
            <a href="<?= url_for('main.services') ?>#pc" class="service-card">
                <div class="service-card__icon">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="4" y="4" width="16" height="12" rx="2"/><line x1="9" y1="20" x2="15" y2="20"/><line x1="12" y1="16" x2="12" y2="20"/></svg>
                </div>
                <h3 class="service-card__title">ПК и моноблоки</h3>
                <p class="service-card__desc">Сборка, апгрейд, диагностика, замена комплектующих, настройка ПО.</p>
                <span class="service-card__price">от 700 ₽</span>
            </a>
        </div>
        <div class="section__footer">
            <a href="<?= url_for('main.services') ?>" class="btn btn--outline">Все услуги</a>
        </div>
    </div>
</section>

<section class="section advantages" id="advantages">
    <div class="container">
        <div class="section__header">
            <span class="section__badge">Почему выбирают нас</span>
            <h2 class="section__title">Преимущества <?= e($sc['site_name'] ?? 'eFix') ?></h2>
        </div>
        <div class="advantages-grid">
            <div class="advantage-card">
                <div class="advantage-card__num">01</div>
                <h3 class="advantage-card__title">Забор за 1 час</h3>
                <p class="advantage-card__desc">Курьер приедет к вам, заберёт устройство и доставит в мастерскую. После ремонта вернём обратно.</p>
            </div>
            <div class="advantage-card">
                <div class="advantage-card__num">02</div>
                <h3 class="advantage-card__title">Бесплатная диагностика</h3>
                <p class="advantage-card__desc">Диагностика — бесплатно. Вы узнаете причину поломки и стоимость до начала ремонта.</p>
            </div>
            <div class="advantage-card">
                <div class="advantage-card__num">03</div>
                <h3 class="advantage-card__title">Гарантия до 1 года</h3>
                <p class="advantage-card__desc">Даём гарантию на все виды работ. Используем только качественные запчасти.</p>
            </div>
            <div class="advantage-card">
                <div class="advantage-card__num">04</div>
                <h3 class="advantage-card__title">Прозрачные цены</h3>
                <p class="advantage-card__desc">Фиксированная стоимость без скрытых платежей. Вы платите ровно то, что согласовали.</p>
            </div>
            <div class="advantage-card">
                <div class="advantage-card__num">05</div>
                <h3 class="advantage-card__title">Ремонт любой сложности</h3>
                <p class="advantage-card__desc">От замены стекла до восстановления после воды — справимся с любой задачей.</p>
            </div>
            <div class="advantage-card">
                <div class="advantage-card__num">06</div>
                <h3 class="advantage-card__title">Срочный ремонт</h3>
                <p class="advantage-card__desc">Нужно срочно? Возможен экспресс-ремонт за 1–2 часа после забора устройства.</p>
            </div>
        </div>
    </div>
</section>

<section class="section process" id="process">
    <div class="container">
        <div class="section__header">
            <span class="section__badge">Как мы работаем</span>
            <h2 class="section__title">Процесс ремонта</h2>
        </div>
        <div class="process-steps">
            <div class="process-step">
                <div class="process-step__num">1</div>
                <h3 class="process-step__title">Заявка</h3>
                <p class="process-step__desc">Оставьте заявку на сайте или позвоните нам</p>
            </div>
            <div class="process-step">
                <div class="process-step__num">2</div>
                <h3 class="process-step__title">Забор</h3>
                <p class="process-step__desc">Курьер забирает устройство в удобное для вас время</p>
            </div>
            <div class="process-step">
                <div class="process-step__num">3</div>
                <h3 class="process-step__title">Диагностика</h3>
                <p class="process-step__desc">Бесплатно определяем причину и согласуем стоимость</p>
            </div>
            <div class="process-step">
                <div class="process-step__num">4</div>
                <h3 class="process-step__title">Ремонт</h3>
                <p class="process-step__desc">Чиним в мастерской на профессиональном оборудовании</p>
            </div>
            <div class="process-step">
                <div class="process-step__num">5</div>
                <h3 class="process-step__title">Доставка</h3>
                <p class="process-step__desc">Возвращаем устройство с гарантией и рекомендациями</p>
            </div>
        </div>
    </div>
</section>

<section class="section reviews" id="reviews">
    <div class="container">
        <div class="section__header">
            <span class="section__badge">Отзывы</span>
            <h2 class="section__title">Нам доверяют</h2>
        </div>
        <div class="reviews-grid">
            <div class="review-card">
                <div class="review-card__header">
                    <div class="review-card__avatar">А</div>
                    <div>
                        <h4 class="review-card__name">Алексей</h4>
                        <div class="review-card__stars">
                            <span>&#9733;</span><span>&#9733;</span><span>&#9733;</span><span>&#9733;</span><span>&#9733;</span>
                        </div>
                    </div>
                </div>
                <p class="review-card__text">Разбил экран на iPhone 13. Оформил заявку, курьер забрал телефон, через 2 часа уже забрал обратно с новым экраном. Всё отлично, рекомендую!</p>
                <span class="review-card__date">3 дня назад</span>
            </div>
            <div class="review-card">
                <div class="review-card__header">
                    <div class="review-card__avatar">Е</div>
                    <div>
                        <h4 class="review-card__name">Екатерина</h4>
                        <div class="review-card__stars">
                            <span>&#9733;</span><span>&#9733;</span><span>&#9733;</span><span>&#9733;</span><span>&#9733;</span>
                        </div>
                    </div>
                </div>
                <p class="review-card__text">Перестал заряжаться ноутбук. Оказалось, проблема в разъёме. Починили быстро и недорого. Спасибо большое!</p>
                <span class="review-card__date">Неделю назад</span>
            </div>
            <div class="review-card">
                <div class="review-card__header">
                    <div class="review-card__avatar">Д</div>
                    <div>
                        <h4 class="review-card__name">Дмитрий</h4>
                        <div class="review-card__stars">
                            <span>&#9733;</span><span>&#9733;</span><span>&#9733;</span><span>&#9733;</span><span>&#9733;</span>
                        </div>
                    </div>
                </div>
                <p class="review-card__text">Залил чаем ноутбук. Думал всё, конец. Ребята сделали чудо — восстановили, работает как новый. Обращаться только к вам!</p>
                <span class="review-card__date">2 недели назад</span>
            </div>
        </div>
    </div>
</section>

<?= render_hook('public_home_sections', get_defined_vars()) ?>

<section class="section cta-section" id="cta">
    <div class="container">
        <div class="cta-card">
            <div class="cta-card__content">
                <h2 class="cta-card__title"><?= $sc['cta_home_title'] ?? 'Нужен ремонт? Свяжитесь с нами!' ?></h2>
                <p class="cta-card__desc"><?= e($sc['cta_home_desc'] ?? 'Оставьте заявку и мастер свяжется с вами в течение 15 минут. Бесплатная диагностика и консультация.') ?></p>
                <a href="<?= url_for('main.contacts') ?>" class="btn btn--large btn--primary">Оставить заявку</a>
            </div>
            <div class="cta-card__visual">
                <svg width="200" height="200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" opacity="0.2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            </div>
        </div>
    </div>
</section>
<?php $content = ob_get_clean() ?>
<?php include __DIR__ . '/base.php' ?>
