<section class="hero">
    <div class="container">
        <div class="hero__grid">
            <div class="hero__content">
                <div class="hero__badge">
                    <span class="hero__badge-dot"></span>
                    Работаем 8 лет · 5000+ клиентов
                </div>
                <h1>Ремонтируем технику с <span>выездом к вам</span> — бесплатно</h1>
                <p>Заберём устройство, починим в мастерской и вернём обратно. Диагностика — бесплатно, гарантия до 12 месяцев.</p>
                <div class="hero__actions">
                    <a href="#" class="btn btn--primary btn--lg" @click.prevent="$dispatch('open-modal')">Вызвать мастера</a>
                    <a href="/prices" class="btn btn--outline btn--lg">Смотреть цены</a>
                </div>
                <div class="hero__stats">
                    <div>
                        <div class="hero__stat-value"><span class="counter" data-target="8">0</span></div>
                        <div class="hero__stat-label">лет опыта</div>
                    </div>
                    <div>
                        <div class="hero__stat-value"><span class="counter" data-target="5000">0</span>+</div>
                        <div class="hero__stat-label">отремонтировано</div>
                    </div>
                    <div>
                        <div class="hero__stat-value"><span class="counter" data-target="98">0</span>%</div>
                        <div class="hero__stat-label">довольных клиентов</div>
                    </div>
                </div>
            </div>
            <div class="hero__visual">
                <div class="hero__card-mockup">
                    <h3>📱 Вызвать мастера</h3>
                    <form class="hero__form" hx-post="/contacts/send" hx-swap="innerHTML">
                        <input type="text" name="name" placeholder="Ваше имя" required>
                        <input type="tel" name="phone" placeholder="+7 (___) ___-__-__" required>
                        <select name="device">
                            <option value="">Тип устройства</option>
                            <option value="phone">📱 Телефон</option>
                            <option value="tablet">📟 Планшет</option>
                            <option value="laptop">💻 Ноутбук</option>
                            <option value="pc">🖥️ ПК</option>
                        </select>
                        <button type="submit" class="btn btn--primary">Заказать звонок</button>
                    </form>
                    <div class="hero__trust">
                        <div class="hero__trust-avatars">
                            <div style="width:28px;height:28px;border-radius:50%;background:#4f46e5;border:2px solid var(--primary);margin-right:-8px;display:flex;align-items:center;justify-content:center;font-size:.7rem;color:#fff;font-weight:600;">А</div>
                            <div style="width:28px;height:28px;border-radius:50%;background:#059669;border:2px solid var(--primary);margin-right:-8px;display:flex;align-items:center;justify-content:center;font-size:.7rem;color:#fff;font-weight:600;">М</div>
                            <div style="width:28px;height:28px;border-radius:50%;background:#dc2626;border:2px solid var(--primary);margin-right:-8px;display:flex;align-items:center;justify-content:center;font-size:.7rem;color:#fff;font-weight:600;">К</div>
                            <div style="width:28px;height:28px;border-radius:50%;background:#7c3aed;border:2px solid var(--primary);display:flex;align-items:center;justify-content:center;font-size:.7rem;color:#fff;font-weight:600;">Е</div>
                        </div>
                        <span class="hero__trust-text">128 человек оставили заявку сегодня</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="trust-bar">
    <div class="container">
        <div class="trust-bar__grid">
            <div class="trust-bar__item">
                <div class="trust-bar__icon trust-bar__icon--guarantee">🛡️</div>
                Гарантия до 12 месяцев
            </div>
            <div class="trust-bar__item">
                <div class="trust-bar__icon trust-bar__icon--diagnostics">🔍</div>
                Бесплатная диагностика
            </div>
            <div class="trust-bar__item">
                <div class="trust-bar__icon trust-bar__icon--speed">⚡</div>
                Забор за 1 час
            </div>
            <div class="trust-bar__item">
                <div class="trust-bar__icon trust-bar__icon--price">💰</div>
                Прозрачные цены
            </div>
        </div>
    </div>
</section>

<section class="section" id="services">
    <div class="container">
        <div style="text-align:center;margin-bottom:3rem" class="reveal">
            <span class="section__label">Услуги</span>
            <h2 class="section__title">Что мы ремонтируем</h2>
            <p class="section__sub" style="margin:0 auto">Любая цифровая техника — от телефонов до ПК. Честная цена до начала работ.</p>
        </div>
        <div class="services-grid">
            <div class="service-card reveal" @click="alert('Телефоны')">
                <div class="service-card__icon service-card__icon--phone">📱</div>
                <h3>Телефоны</h3>
                <p>Замена экрана, аккумулятора, разъёмов, кнопок, восстановление после воды</p>
                <div class="service-card__price">от 500 ₽</div>
            </div>
            <div class="service-card reveal" @click="alert('Планшеты')">
                <div class="service-card__icon service-card__icon--tablet">📟</div>
                <h3>Планшеты</h3>
                <p>Ремонт дисплея, матрицы, корпуса, замена деталей любой сложности</p>
                <div class="service-card__price">от 800 ₽</div>
            </div>
            <div class="service-card reveal" @click="alert('Ноутбуки')">
                <div class="service-card__icon service-card__icon--laptop">💻</div>
                <h3>Ноутбуки</h3>
                <p>Замена клавиатуры, матрицы, термопасты, чистка, ремонт материнской платы</p>
                <div class="service-card__price">от 1 000 ₽</div>
            </div>
            <div class="service-card reveal" @click="alert('ПК')">
                <div class="service-card__icon service-card__icon--pc">🖥️</div>
                <h3>ПК</h3>
                <p>Диагностика, замена БП, видеокарты, сборка, апгрейд, настройка</p>
                <div class="service-card__price">от 1 000 ₽</div>
            </div>
        </div>
    </div>
</section>

<section class="section section--alt">
    <div class="container">
        <div style="text-align:center;margin-bottom:3rem" class="reveal">
            <span class="section__label">Преимущества</span>
            <h2 class="section__title">Почему выбирают eFix</h2>
            <p class="section__sub" style="margin:0 auto">Мы делаем ремонт удобным и прозрачным. Никаких скрытых платежей и пустых обещаний.</p>
        </div>
        <div class="advantages-grid">
            <div class="advantage-card reveal">
                <div class="advantage-card__num">01</div>
                <div>
                    <h4>🚗 Забор за час</h4>
                    <p>Курьер приедет к вам в удобное время и заберёт технику. Бесплатно в пределах города.</p>
                </div>
            </div>
            <div class="advantage-card reveal">
                <div class="advantage-card__num">02</div>
                <div>
                    <h4>🔍 Бесплатная диагностика</h4>
                    <p>Выясним причину неисправности бесплатно. Сообщим точную цену до начала работ.</p>
                </div>
            </div>
            <div class="advantage-card reveal">
                <div class="advantage-card__num">03</div>
                <div>
                    <h4>🛡️ Гарантия до года</h4>
                    <p>На все работы даём гарантию до 12 месяцев. Если что-то сломается — починим бесплатно.</p>
                </div>
            </div>
            <div class="advantage-card reveal">
                <div class="advantage-card__num">04</div>
                <div>
                    <h4>💰 Прозрачные цены</h4>
                    <p>Вы знаете стоимость до начала работ. Никаких доплат в процессе. Цена фиксируется в квитанции.</p>
                </div>
            </div>
            <div class="advantage-card reveal">
                <div class="advantage-card__num">05</div>
                <div>
                    <h4>🔧 Любая сложность</h4>
                    <p>От замены стекла до восстановления после воды и BGA-пайки. Справимся с любой поломкой.</p>
                </div>
            </div>
            <div class="advantage-card reveal">
                <div class="advantage-card__num">06</div>
                <div>
                    <h4>⚡ Срочный ремонт</h4>
                    <p>Нужно срочно? Починим за 24 часа. Срочный выезд и приоритетное обслуживание.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section" id="process">
    <div class="container">
        <div style="text-align:center;margin-bottom:3rem" class="reveal">
            <span class="section__label">Как мы работаем</span>
            <h2 class="section__title">5 шагов к исправной технике</h2>
            <p class="section__sub" style="margin:0 auto">Всё просто: от заявки до возврата устройства. Вы ничего не делаете — просто пользуетесь.</p>
        </div>
        <div class="process-steps">
            <div class="step reveal active">
                <div class="step__circle">1</div>
                <div>
                    <h4>Заявка</h4>
                    <p>Оставляете заявку на сайте или по телефону</p>
                </div>
            </div>
            <div class="step reveal">
                <div class="step__circle">2</div>
                <div>
                    <h4>Забор</h4>
                    <p>Курьер забирает технику в удобное для вас время</p>
                </div>
            </div>
            <div class="step reveal">
                <div class="step__circle">3</div>
                <div>
                    <h4>Диагностика</h4>
                    <p>Бесплатно выявляем проблему и согласуем цену</p>
                </div>
            </div>
            <div class="step reveal">
                <div class="step__circle">4</div>
                <div>
                    <h4>Ремонт</h4>
                    <p>Чиним с использованием оригинальных запчастей</p>
                </div>
            </div>
            <div class="step reveal">
                <div class="step__circle">5</div>
                <div>
                    <h4>Доставка</h4>
                    <p>Бесплатно возвращаем устройство обратно</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section section--alt" id="testimonials">
    <div class="container">
        <div style="text-align:center;margin-bottom:3rem" class="reveal">
            <span class="section__label">Отзывы</span>
            <h2 class="section__title">Нам доверяют</h2>
            <p class="section__sub" style="margin:0 auto">Более 5000 клиентов остались довольны. Вот что говорят некоторые из них.</p>
        </div>
        <div class="testimonials-grid">
            <div class="testimonial-card reveal">
                <div class="testimonial-card__stars">★★★★★</div>
                <blockquote>«Отличный сервис! Приехали через 40 минут, забрали ноутбук. Через 2 дня вернули как новый. Цена порадовала — намного дешевле, чем в сервисе рядом с домом.»</blockquote>
                <div class="testimonial-card__author">
                    <div class="testimonial-card__avatar">АН</div>
                    <div>
                        <div class="testimonial-card__name">Алексей Н.</div>
                        <div class="testimonial-card__role">Ремонт ноутбука</div>
                    </div>
                </div>
            </div>
            <div class="testimonial-card reveal">
                <div class="testimonial-card__stars">★★★★★</div>
                <blockquote>«Уронила телефон в воду, думала всё — конец. Ребята восстановили за 3 дня. До сих пор работает идеально. Огромное спасибо! Буду рекомендовать всем.»</blockquote>
                <div class="testimonial-card__author">
                    <div class="testimonial-card__avatar">ЕК</div>
                    <div>
                        <div class="testimonial-card__name">Елена К.</div>
                        <div class="testimonial-card__role">Ремонт телефона</div>
                    </div>
                </div>
            </div>
            <div class="testimonial-card reveal">
                <div class="testimonial-card__stars">★★★★★</div>
                <blockquote>«Починили планшет дочке — разбила экран. Выехали в тот же день, забрали и привезли обратно с новым дисплеем. Ребёнок счастлив, родители спокойны.»</blockquote>
                <div class="testimonial-card__author">
                    <div class="testimonial-card__avatar">МК</div>
                    <div>
                        <div class="testimonial-card__name">Михаил К.</div>
                        <div class="testimonial-card__role">Ремонт планшета</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container">
        <h2 class="reveal">Готовы починить технику?</h2>
        <p class="reveal">Оставьте заявку — и мы свяжемся с вами в течение 15 минут.</p>
        <div class="reveal">
            <a href="#" class="btn btn--white btn--lg" @click.prevent="$dispatch('open-modal')">Вызвать мастера бесплатно</a>
        </div>
    </div>
</section>
