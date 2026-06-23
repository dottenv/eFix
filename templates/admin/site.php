<?php
$title = 'Информация сайта — ' . ($site_name ?? 'eFix') . ' Admin';
$header = 'Редактирование информации сайта';
ob_start();
?>
<form method="POST" x-data="siteForm()">
    <input type="hidden" name="batch_save" value="1">

    <div class="card">
        <div class="card__header">
            <h2 class="card__title">Название сайта и SEO</h2>
            <span class="text-muted text-sm">Название бренда используется во всех заголовках страниц</span>
        </div>
        <div class="form-group">
            <label>Название сайта / Бренд</label>
            <input name="content_site_name" value="<?= e($contents['site_name'] ?? 'eFix') ?>" placeholder="eFix">
            <span class="text-muted text-sm mt-2">Меняется везде: в title, логотипе, копирайте и админке</span>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Meta Title (главная)</label>
                <input name="content_meta_title" value="<?= e($contents['meta_title'] ?? 'eFix — Выездной сервисный центр в Новосибирске') ?>" maxlength="120">
            </div>
            <div class="form-group">
                <label>Meta Description (главная)</label>
                <textarea name="content_meta_description" rows="2" maxlength="300"><?= e($contents['meta_description'] ?? 'eFix — ремонт цифровой техники в Новосибирске с выездом. Телефоны, планшеты, ноутбуки, ПК. Бесплатная диагностика, гарантия.') ?></textarea>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card__header">
            <h2 class="card__title">Главный экран (Hero)</h2>
            <span class="text-muted text-sm">Заголовок и подзаголовок на главной странице</span>
        </div>
        <div class="form-group">
            <label>Заголовок H1</label>
            <textarea name="content_hero_title" rows="2"><?= e($contents['hero_title'] ?? 'Ремонтируем цифровую технику —<br>заберём, починим, вернём') ?></textarea>
        </div>
        <div class="form-group">
            <label>Подзаголовок</label>
            <textarea name="content_hero_subtitle" rows="2"><?= e($contents['hero_subtitle'] ?? 'Телефоны, планшеты, ноутбуки, ПК. Бесплатная диагностика, гарантия до 1 года. Приедем, заберём устройство и вернём обратно после ремонта.') ?></textarea>
        </div>
        <div class="form-group">
            <label>Бейдж (плашка над заголовком)</label>
            <input name="content_hero_badge" value="<?= e($contents['hero_badge'] ?? 'Выездной сервисный центр') ?>">
        </div>
    </div>

    <div class="card">
        <div class="card__header">
            <h2 class="card__title">О компании</h2>
            <span class="text-muted text-sm">Текст на странице «О нас»</span>
        </div>
        <div class="form-group">
            <label>Текст «О компании»</label>
            <textarea name="content_about_text" rows="5"><?= e($contents['about_text'] ?? 'eFix — это команда профессиональных инженеров, которые объединились, чтобы сделать ремонт цифровой техники простым, быстрым и доступным. Мы работаем в Новосибирске с 2019 года. Наш принцип — забрать устройство у вас, отремонтировать в мастерской на профессиональном оборудовании и вернуть обратно. Никаких очередей и ожидания в сервисе.') ?></textarea>
        </div>
        <div class="form-group">
            <label>Заголовок страницы «О нас»</label>
            <input name="content_about_title" value="<?= e($contents['about_title'] ?? 'О компании eFix') ?>">
        </div>
        <div class="form-group">
            <label>Подзаголовок страницы «О нас»</label>
            <textarea name="content_about_subtitle" rows="2"><?= e($contents['about_subtitle'] ?? 'Выездной сервисный центр — ремонтируем технику там, где вам удобно.') ?></textarea>
        </div>
    </div>

    <div class="card">
        <div class="card__header">
            <h2 class="card__title">Блок статистики</h2>
            <span class="text-muted text-sm">Отображается на главной и странице «О нас»</span>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Статистика 1 — число</label>
                <input name="content_stat_years_num" placeholder="5+" value="<?= e($contents['stat_years_num'] ?? '5+') ?>">
            </div>
            <div class="form-group">
                <label>Статистика 1 — подпись</label>
                <input name="content_stat_years_label" placeholder="лет опыта" value="<?= e($contents['stat_years_label'] ?? 'лет опыта') ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Статистика 2 — число</label>
                <input name="content_stat_repaired_num" placeholder="1500+" value="<?= e($contents['stat_repaired_num'] ?? '1500+') ?>">
            </div>
            <div class="form-group">
                <label>Статистика 2 — подпись</label>
                <input name="content_stat_repaired_label" placeholder="отремонтировано" value="<?= e($contents['stat_repaired_label'] ?? 'отремонтировано') ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Статистика 3 — число</label>
                <input name="content_stat_satisfaction_num" placeholder="98%" value="<?= e($contents['stat_satisfaction_num'] ?? '98%') ?>">
            </div>
            <div class="form-group">
                <label>Статистика 3 — подпись</label>
                <input name="content_stat_satisfaction_label" placeholder="довольных клиентов" value="<?= e($contents['stat_satisfaction_label'] ?? 'довольных клиентов') ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Статистика 4 — число</label>
                <input name="content_stat_pickup_num" placeholder="1 час" value="<?= e($contents['stat_pickup_num'] ?? '1 час') ?>">
            </div>
            <div class="form-group">
                <label>Статистика 4 — подпись</label>
                <input name="content_stat_pickup_label" placeholder="среднее время забора" value="<?= e($contents['stat_pickup_label'] ?? 'среднее время забора') ?>">
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card__header">
            <h2 class="card__title">Контактная информация</h2>
            <span class="text-muted text-sm">Отображается в шапке, подвале и на странице контактов</span>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Телефон</label>
                <input name="content_phone" value="<?= e($contents['phone'] ?? '+7 (999) 999-99-99') ?>">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input name="content_email" placeholder="info@efix.ru" value="<?= e($contents['email'] ?? 'info@efix.ru') ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Город</label>
                <input name="content_city" value="<?= e($contents['city'] ?? 'Новосибирск') ?>">
            </div>
            <div class="form-group">
                <label>Адрес (кратко)</label>
                <input name="content_address_short" value="<?= e($contents['address_short'] ?? 'Новосибирск, выезд по городу') ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Часы работы</label>
                <input name="content_work_hours" value="<?= e($contents['work_hours'] ?? 'Ежедневно с 09:00 до 21:00') ?>">
            </div>
            <div class="form-group">
                <label>Часы работы (подробно)</label>
                <textarea name="content_work_hours_full" rows="2"><?= e($contents['work_hours_full'] ?? 'Пн — Вс: 10:00 — 20:00' . "\n" . 'ТРЦ: 10:00 — 22:00' . "\n" . 'Заявки через сайт: круглосуточно') ?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label>Координаты для карты (lat, lng)</label>
            <div class="form-row" style="gap:8px">
                <input name="content_map_lat" placeholder="55.0084" value="<?= e($contents['map_lat'] ?? '55.0084') ?>" style="width:200px">
                <input name="content_map_lng" placeholder="82.9357" value="<?= e($contents['map_lng'] ?? '82.9357') ?>" style="width:200px">
                <span class="text-muted text-sm" style="align-self:center">центр карты по умолчанию</span>
            </div>
        </div>
    </div>

    <?php $sn = $contents['site_name'] ?? 'eFix'; ?>
    <div class="card">
        <div class="card__header">
            <h2 class="card__title">Социальные сети</h2>
            <span class="text-muted text-sm">Ссылки на соцсети. Снимите галочку чтобы скрыть иконку</span>
        </div>

        <div class="social-admin-grid">
            <div class="social-admin-item">
                <div class="social-admin-item__header">
                    <label class="toggle-wrap">
                        <input type="hidden" name="content_social_whatsapp_enabled" value="0">
                        <input type="checkbox" name="content_social_whatsapp_enabled" value="1" <?= (($contents['social_whatsapp_enabled'] ?? '1') === '1') ? 'checked' : '' ?> @change="updateSocial">
                        <span class="toggle-slider"></span>
                    </label>
                    <span class="social-admin-item__name">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                        WhatsApp
                    </span>
                </div>
                <input name="content_social_whatsapp_url" placeholder="https://wa.me/79999999999" value="<?= e($contents['social_whatsapp_url'] ?? '') ?>">
            </div>

            <div class="social-admin-item">
                <div class="social-admin-item__header">
                    <label class="toggle-wrap">
                        <input type="hidden" name="content_social_telegram_enabled" value="0">
                        <input type="checkbox" name="content_social_telegram_enabled" value="1" <?= (($contents['social_telegram_enabled'] ?? '1') === '1') ? 'checked' : '' ?>>
                        <span class="toggle-slider"></span>
                    </label>
                    <span class="social-admin-item__name">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                        Telegram
                    </span>
                </div>
                <input name="content_social_telegram_url" placeholder="https://t.me/<?= e($sn) ?>" value="<?= e($contents['social_telegram_url'] ?? '') ?>">
            </div>

            <div class="social-admin-item">
                <div class="social-admin-item__header">
                    <label class="toggle-wrap">
                        <input type="hidden" name="content_social_vk_enabled" value="0">
                        <input type="checkbox" name="content_social_vk_enabled" value="1" <?= (($contents['social_vk_enabled'] ?? '1') === '1') ? 'checked' : '' ?>>
                        <span class="toggle-slider"></span>
                    </label>
                    <span class="social-admin-item__name">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 1.1 3 1.5 3 1.5z"/></svg>
                        ВКонтакте
                    </span>
                </div>
                <input name="content_social_vk_url" placeholder="https://vk.com/<?= e($sn) ?>" value="<?= e($contents['social_vk_url'] ?? '') ?>">
            </div>

            <div class="social-admin-item">
                <div class="social-admin-item__header">
                    <label class="toggle-wrap">
                        <input type="hidden" name="content_social_instagram_enabled" value="0">
                        <input type="checkbox" name="content_social_instagram_enabled" value="1" <?= (($contents['social_instagram_enabled'] ?? '1') === '1') ? 'checked' : '' ?>>
                        <span class="toggle-slider"></span>
                    </label>
                    <span class="social-admin-item__name">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                        Instagram
                    </span>
                </div>
                <input name="content_social_instagram_url" placeholder="https://instagram.com/<?= e($sn) ?>" value="<?= e($contents['social_instagram_url'] ?? '') ?>">
            </div>

            <div class="social-admin-item">
                <div class="social-admin-item__header">
                    <label class="toggle-wrap">
                        <input type="hidden" name="content_social_youtube_enabled" value="0">
                        <input type="checkbox" name="content_social_youtube_enabled" value="1" <?= (($contents['social_youtube_enabled'] ?? '1') === '1') ? 'checked' : '' ?>>
                        <span class="toggle-slider"></span>
                    </label>
                    <span class="social-admin-item__name">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58 2.78 2.78 0 0 0 1.94 2C5.12 20 12 20 12 20s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02"/></svg>
                        YouTube
                    </span>
                </div>
                <input name="content_social_youtube_url" placeholder="https://youtube.com/@<?= e($sn) ?>" value="<?= e($contents['social_youtube_url'] ?? '') ?>">
            </div>

            <div class="social-admin-item">
                <div class="social-admin-item__header">
                    <label class="toggle-wrap">
                        <input type="hidden" name="content_social_ok_enabled" value="0">
                        <input type="checkbox" name="content_social_ok_enabled" value="1" <?= (($contents['social_ok_enabled'] ?? '1') === '1') ? 'checked' : '' ?>>
                        <span class="toggle-slider"></span>
                    </label>
                    <span class="social-admin-item__name">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a5 5 0 1 0 0 10 5 5 0 0 0 0-10z"/><path d="M12 12c-4.5 0-8 2.5-8 5.5 0 1.5 1.5 2.5 4 3.5l-2 3h12l-2-3c2.5-1 4-2 4-3.5 0-3-3.5-5.5-8-5.5z"/></svg>
                        Одноклассники
                    </span>
                </div>
                <input name="content_social_ok_url" placeholder="https://ok.ru/<?= e($sn) ?>" value="<?= e($contents['social_ok_url'] ?? '') ?>">
            </div>

            <div class="social-admin-item">
                <div class="social-admin-item__header">
                    <label class="toggle-wrap">
                        <input type="hidden" name="content_social_tiktok_enabled" value="0">
                        <input type="checkbox" name="content_social_tiktok_enabled" value="1" <?= (($contents['social_tiktok_enabled'] ?? '1') === '1') ? 'checked' : '' ?>>
                        <span class="toggle-slider"></span>
                    </label>
                    <span class="social-admin-item__name">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12a4 4 0 1 0 0 8 4 4 0 0 0 0-8z"/><path d="M15 8a4 4 0 0 0 4-4"/><path d="M13 12v-8a4 4 0 0 1 4 4"/></svg>
                        TikTok
                    </span>
                </div>
                <input name="content_social_tiktok_url" placeholder="https://tiktok.com/@<?= e($sn) ?>" value="<?= e($contents['social_tiktok_url'] ?? '') ?>">
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card__header">
            <h2 class="card__title">Подвал сайта (Footer)</h2>
            <span class="text-muted text-sm">Текст внизу всех страниц</span>
        </div>
        <div class="form-group">
            <label>Описание компании (в подвале)</label>
            <textarea name="content_footer_description" rows="3"><?= e($contents['footer_description'] ?? 'Выездной сервисный центр в Новосибирске. Ремонтируем цифровую технику с заботой о вашем времени.') ?></textarea>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Текст копирайта</label>
                <input name="content_copyright" value="<?= e($contents['copyright'] ?? '© 2024 eFix. Все права защищены.') ?>">
            </div>
            <div class="form-group">
                <label>Год основания (в футере)</label>
                <input name="content_foundation_year" placeholder="2019" value="<?= e($contents['foundation_year'] ?? '2019') ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Название компании (в логотипе футера)</label>
                <input name="content_company_name" value="<?= e($contents['company_name'] ?? 'eFix') ?>">
            </div>
            <div class="form-group">
                <label>Слоган под логотипом</label>
                <input name="content_company_slogan" value="<?= e($contents['company_slogan'] ?? 'Выездной сервисный центр') ?>">
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card__header">
            <h2 class="card__title">CTA-секции (призывы к действию)</h2>
            <span class="text-muted text-sm">Текст на разных страницах</span>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Текст кнопки CTA (на главной)</label>
                <input name="content_cta_button_text" value="<?= e($contents['cta_button_text'] ?? 'Вызвать мастера') ?>">
            </div>
            <div class="form-group">
                <label>Текст кнопки «Цены»</label>
                <input name="content_prices_button_text" value="<?= e($contents['prices_button_text'] ?? 'Смотреть цены') ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>CTA заголовок (страница услуг)</label>
                <input name="content_cta_services_title" value="<?= e($contents['cta_services_title'] ?? 'Не нашли свою услугу?') ?>">
            </div>
            <div class="form-group">
                <label>CTA описание (страница услуг)</label>
                <textarea name="content_cta_services_desc" rows="2"><?= e($contents['cta_services_desc'] ?? 'Свяжитесь с нами — мы решим любую проблему с вашей техникой.') ?></textarea>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>CTA заголовок (главная, низ)</label>
                <input name="content_cta_home_title" value="<?= e($contents['cta_home_title'] ?? 'Нужен ремонт? Свяжитесь с нами!') ?>">
            </div>
            <div class="form-group">
                <label>CTA описание (главная, низ)</label>
                <textarea name="content_cta_home_desc" rows="2"><?= e($contents['cta_home_desc'] ?? 'Оставьте заявку и мастер свяжется с вами в течение 15 минут. Бесплатная диагностика и консультация.') ?></textarea>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>CTA заголовок (О нас)</label>
                <input name="content_cta_about_title" value="<?= e($contents['cta_about_title'] ?? 'Станьте нашим клиентом') ?>">
            </div>
            <div class="form-group">
                <label>CTA описание (О нас)</label>
                <textarea name="content_cta_about_desc" rows="2"><?= e($contents['cta_about_desc'] ?? 'Оставьте заявку прямо сейчас и получите скидку 10% на первый ремонт!') ?></textarea>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>CTA заголовок (Цены)</label>
                <input name="content_cta_prices_title" value="<?= e($contents['cta_prices_title'] ?? 'Узнайте точную стоимость') ?>">
            </div>
            <div class="form-group">
                <label>CTA описание (Цены)</label>
                <textarea name="content_cta_prices_desc" rows="2"><?= e($contents['cta_prices_desc'] ?? 'Оставьте заявку — мы перезвоним и назовём цену после бесплатной диагностики.') ?></textarea>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Подзаголовок страницы услуг</label>
                <textarea name="content_services_subtitle" rows="2"><?= e($contents['services_subtitle'] ?? 'Выберите категорию, чтобы увидеть полный список услуг и цены') ?></textarea>
            </div>
            <div class="form-group">
                <label>Подзаголовок страницы цен</label>
                <textarea name="content_prices_subtitle" rows="2"><?= e($contents['prices_subtitle'] ?? 'Актуальные цены на ремонт цифровой техники. Поиск по типу, бренду, модели и услуге.') ?></textarea>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>CTA заголовок (Контакты)</label>
                <input name="content_cta_contacts_title" value="<?= e($contents['cta_contacts_title'] ?? 'Свяжитесь с нами') ?>">
            </div>
            <div class="form-group">
                <label>CTA описание (Контакты)</label>
                <textarea name="content_cta_contacts_desc" rows="2"><?= e($contents['cta_contacts_desc'] ?? 'Заполните форму — мы свяжемся с вами в течение 15 минут.') ?></textarea>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Заголовок формы на контактах</label>
                <input name="content_contact_form_title" value="<?= e($contents['contact_form_title'] ?? 'Оставить заявку') ?>">
            </div>
            <div class="form-group">
                <label>Описание формы на контактах</label>
                <textarea name="content_contact_form_desc" rows="2"><?= e($contents['contact_form_desc'] ?? 'Заполните форму — мы свяжемся с вами в течение 15 минут.') ?></textarea>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card__header">
            <h2 class="card__title">Прочее</h2>
            <span class="text-muted text-sm">Дополнительные настройки</span>
        </div>
        <div class="form-group">
            <label>Нотация цен (текст под таблицей)</label>
            <textarea name="content_prices_notice" rows="2"><?= e($contents['prices_notice'] ?? '<strong>Диагностика — бесплатно!</strong> Точная стоимость определяется после осмотра устройства.') ?></textarea>
        </div>
        <div class="form-group">
            <label>Согласие на обработку данных (текст у формы)</label>
            <textarea name="content_consent_text" rows="2"><?= e($contents['consent_text'] ?? 'Нажимая кнопку, вы соглашаетесь на обработку персональных данных') ?></textarea>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn--primary">Сохранить изменения</button>
    </div>
</form>

<style>
.social-admin-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}
@media (max-width: 700px) {
    .social-admin-grid {
        grid-template-columns: 1fr;
    }
}
.social-admin-item {
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 14px 16px;
}
.social-admin-item__header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
}
.social-admin-item__name {
    display: flex;
    align-items: center;
    gap: 6px;
    font-weight: 600;
    font-size: 14px;
    color: var(--text);
}
.social-admin-item input[type="text"],
.social-admin-item input[type="url"] {
    width: 100%;
    padding: 8px 12px;
    border: 2px solid var(--border);
    border-radius: 6px;
    font-family: var(--font);
    font-size: 13px;
    color: var(--text);
    background: var(--surface);
    transition: border-color .2s;
}
.social-admin-item input:focus {
    outline: none;
    border-color: var(--accent);
}
.toggle-wrap {
    position: relative;
    display: inline-flex;
    align-items: center;
    cursor: pointer;
    flex-shrink: 0;
}
.toggle-wrap input {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}
.toggle-slider {
    width: 36px;
    height: 20px;
    background: var(--text-light);
    border-radius: 10px;
    transition: background .2s;
    position: relative;
}
.toggle-slider::after {
    content: '';
    position: absolute;
    top: 2px;
    left: 2px;
    width: 16px;
    height: 16px;
    background: #fff;
    border-radius: 50%;
    transition: transform .2s;
}
.toggle-wrap input:checked + .toggle-slider {
    background: var(--success);
}
.toggle-wrap input:checked + .toggle-slider::after {
    transform: translateX(16px);
}
</style>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('siteForm', () => ({
        updateSocial() {}
    }));
});
</script>
<?php
$content = ob_get_clean();
include __DIR__ . '/base.php';
?>
