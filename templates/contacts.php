<?php $title = 'Контакты — ' . e($sc['site_name'] ?? 'eFix') . '. Свяжитесь с нами' ?>
<?php ob_start() ?>
<section class="page-hero">
    <div class="container">
        <h1 class="page-hero__title">Контакты</h1>
        <p class="page-hero__desc"><?= e($sc['cta_contacts_desc'] ?? 'Свяжитесь с нами любым удобным способом. Выезжаем по всему Новосибирску.') ?></p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="contacts-grid">
            <div class="contacts-info">
                <h2>Наши контакты</h2>
                <div class="contacts-list">
                    <div class="contacts-item">
                        <div class="contacts-item__icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        </div>
                        <div>
                            <h3>Телефон</h3>
                            <a href="tel:<?= e($sc['phone'] ?? '+79999999999') ?>"><?= e($sc['phone'] ?? '+7 (999) 999-99-99') ?></a>
                            <p><?= e($sc['work_hours'] ?? 'Ежедневно с 09:00 до 21:00') ?></p>
                        </div>
                    </div>
                    <div class="contacts-item">
                        <div class="contacts-item__icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        </div>
                        <div>
                            <h3>Почта</h3>
                            <a href="mailto:<?= e($sc['email'] ?? 'info@efix.ru') ?>"><?= e($sc['email'] ?? 'info@efix.ru') ?></a>
                            <p>Ответим в течение часа</p>
                        </div>
                    </div>
                    <div class="contacts-item">
                        <div class="contacts-item__icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                        <div>
                            <h3>Адрес</h3>
                            <p><?= e($sc['address_short'] ?? 'Новосибирск, выезд по городу') ?></p>
                            <p>Работаем во всех районах</p>
                        </div>
                    </div>
                </div>
                <div class="contacts-social">
                    <h3>Мы в соцсетях</h3>
                    <div class="social-links">
                        <?php $social_pages = [
                            ['social_whatsapp', 'WhatsApp', '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>'],
                            ['social_telegram', 'Telegram', '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>'],
                            ['social_instagram', 'Instagram', '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>'],
                            ['social_vk', 'VK', '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 1.1 3 1.5 3 1.5z"/></svg>'],
                            ['social_youtube', 'YouTube', '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58 2.78 2.78 0 0 0 1.94 2C5.12 20 12 20 12 20s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02"/></svg>'],
                            ['social_ok', 'OK', '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a5 5 0 1 0 0 10 5 5 0 0 0 0-10z"/><path d="M12 12c-4.5 0-8 2.5-8 5.5 0 1.5 1.5 2.5 4 3.5l-2 3h12l-2-3c2.5-1 4-2 4-3.5 0-3-3.5-5.5-8-5.5z"/></svg>'],
                            ['social_tiktok', 'TikTok', '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12a4 4 0 1 0 0 8 4 4 0 0 0 0-8z"/><path d="M15 8a4 4 0 0 0 4-4"/><path d="M13 12v-8a4 4 0 0 1 4 4"/></svg>'],
                        ] ?>
                        <?php foreach($social_pages as $sp): ?>
                            <?php $prefix = $sp[0]; $name = $sp[1]; $icon = $sp[2]; ?>
                            <?php if (($sc[$prefix . '_enabled'] ?? '1') === '1' && !empty($sc[$prefix . '_url'])): ?>
                            <a href="<?= e($sc[$prefix . '_url']) ?>" class="social-link" target="_blank" rel="noopener noreferrer" aria-label="<?= e($name) ?>"><?= $icon ?></a>
                            <?php endif ?>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
            <div class="contacts-form">
                <h2><?= $sc['contact_form_title'] ?? 'Оставить заявку' ?></h2>
                <p><?= e($sc['contact_form_desc'] ?? 'Заполните форму — мы свяжемся с вами в течение 15 минут.') ?></p>
                <form class="form" id="contactForm"
                    hx-post="<?= url_for('api.callback') ?>"
                    hx-target="#contactForm"
                    hx-swap="outerHTML"
                    hx-trigger="submit"
                >
                    <div class="form__group">
                        <label for="name">Ваше имя</label>
                        <input type="text" id="name" name="name" placeholder="Иван" required>
                    </div>
                    <div class="form__group">
                        <label for="phone">Телефон</label>
                        <input type="tel" id="phone" name="phone" placeholder="+7 (999) 999-99-99" required class="phone-mask">
                    </div>
                    <div class="form__group">
                        <label for="device_type">Тип устройства</label>
                        <select id="device_type" name="device_type">
                            <option value="">— Не выбрано —</option>
                            <option value="phone">Телефон</option>
                            <option value="tablet">Планшет</option>
                            <option value="laptop">Ноутбук</option>
                            <option value="pc">ПК / Моноблок</option>
                        </select>
                    </div>
                    <div class="form__group">
                        <label for="device_model">Модель устройства</label>
                        <input type="text" id="device_model" name="device_model" placeholder="iPhone 13, Galaxy S24, MacBook Air...">
                    </div>
                    <div class="form__group">
                        <label for="message">Описание проблемы</label>
                        <textarea id="message" name="message" placeholder="Опишите поломку..." rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn--primary btn--large btn--full">Отправить заявку</button>
                    <p class="form__note"><?= e($sc['consent_text'] ?? 'Нажимая кнопку, вы соглашаетесь на обработку персональных данных') ?></p>
                </form>
            </div>
        </div>
    </div>
</section>
<?php $content = ob_get_clean() ?>
<?php include __DIR__ . '/base.php' ?>
