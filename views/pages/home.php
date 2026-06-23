<section class="hero">
    <div class="container">
        <div>
            <div class="hero__badge">8 лет опыта — 5000+ клиентов</div>
            <h1>Ремонтируем технику с <span>выездом к вам</span></h1>
            <p>Заберём устройство, починим и вернём обратно. Диагностика бесплатно, гарантия до 12 месяцев.</p>
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
        <div>
            <div class="hero__form-card">
                <h3>Оставить заявку</h3>
                <form class="hero__form" hx-post="/contacts/send" hx-swap="innerHTML">
                    <input type="text" name="name" placeholder="Ваше имя" required>
                    <input type="tel" name="phone" placeholder="Номер телефона" required>
                    <select name="device">
                        <option value="">Тип устройства</option>
                        <option value="phone">Телефон</option>
                        <option value="tablet">Планшет</option>
                        <option value="laptop">Ноутбук</option>
                        <option value="pc">ПК</option>
                    </select>
                    <button type="submit" class="btn btn--primary btn--block">Отправить заявку</button>
                </form>
                <div class="hero__trust">
                    <div class="hero__trust-avatars">
                        <div class="hero__trust-avatar" style="background:#4f46e5">АН</div>
                        <div class="hero__trust-avatar" style="background:#059669">МК</div>
                        <div class="hero__trust-avatar" style="background:#dc2626">ЕК</div>
                        <div class="hero__trust-avatar" style="background:#7c3aed">СВ</div>
                    </div>
                    <span>128 заявок сегодня</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="trust-bar">
    <div class="container">
        <div class="trust-bar__grid">
            <div class="trust-bar__item">
                <div class="trust-bar__icon"></div>
                Гарантия до 12 месяцев
            </div>
            <div class="trust-bar__item">
                <div class="trust-bar__icon"></div>
                Бесплатная диагностика
            </div>
            <div class="trust-bar__item">
                <div class="trust-bar__icon"></div>
                Забор за 1 час
            </div>
            <div class="trust-bar__item">
                <div class="trust-bar__icon"></div>
                Прозрачные цены
            </div>
        </div>
    </div>
</section>

<section class="section" id="services">
    <div class="container">
        <div class="section__inner reveal">
            <span class="section__label">Услуги</span>
            <h2 class="section__title">Что мы ремонтируем</h2>
            <p class="section__sub">Любая цифровая техника — от телефонов до ПК. Честная цена до начала работ, диагностика бесплатно.</p>
        </div>
        <div class="bento-grid">
            <div class="bento-card bento-card--3col reveal" @click="$dispatch('open-modal')">
                <div class="bento-card__accent" style="background:#2563eb"></div>
                <h3>Телефоны</h3>
                <p>Замена экрана, аккумулятора, разъёмов, кнопок. Восстановление после воды, BGA-пайка.</p>
                <div class="bento-card__price">от 500 ₽</div>
                <div class="bento-card__count">Средний срок: 1 день</div>
            </div>
            <div class="bento-card bento-card--3col reveal" @click="$dispatch('open-modal')">
                <div class="bento-card__accent" style="background:#db2777"></div>
                <h3>Планшеты</h3>
                <p>Ремонт дисплея, матрицы, корпуса. Замена деталей любой сложности.</p>
                <div class="bento-card__price">от 800 ₽</div>
                <div class="bento-card__count">Средний срок: 2 дня</div>
            </div>
            <div class="bento-card bento-card--3col reveal" @click="$dispatch('open-modal')">
                <div class="bento-card__accent" style="background:#0891b2"></div>
                <h3>Ноутбуки</h3>
                <p>Замена клавиатуры, матрицы, термопасты. Чистка, ремонт материнской платы.</p>
                <div class="bento-card__price">от 1 000 ₽</div>
                <div class="bento-card__count">Средний срок: 2-3 дня</div>
            </div>
            <div class="bento-card bento-card--3col reveal" @click="$dispatch('open-modal')">
                <div class="bento-card__accent" style="background:#7c3aed"></div>
                <h3>Персональные компьютеры</h3>
                <p>Диагностика, замена БП, видеокарты. Сборка, апгрейд, настройка ПО.</p>
                <div class="bento-card__price">от 1 000 ₽</div>
                <div class="bento-card__count">Средний срок: 1-2 дня</div>
            </div>
        </div>
    </div>
</section>

<section class="section section--alt">
    <div class="container">
        <div class="section__inner reveal">
            <span class="section__label">Преимущества</span>
            <h2 class="section__title">Почему выбирают eFix</h2>
            <p class="section__sub">Делаем ремонт удобным и прозрачным. Никаких скрытых платежей и пустых обещаний.</p>
        </div>
        <div class="grid-3col">
            <div class="adv-card reveal">
                <div class="adv-card__num">01</div>
                <h4>Забор за час</h4>
                <p>Курьер приедет к вам в удобное время и заберёт технику. Бесплатно в пределах города.</p>
            </div>
            <div class="adv-card reveal">
                <div class="adv-card__num">02</div>
                <h4>Бесплатная диагностика</h4>
                <p>Выясним причину неисправности бесплатно. Сообщим точную цену до начала работ.</p>
            </div>
            <div class="adv-card reveal">
                <div class="adv-card__num">03</div>
                <h4>Гарантия до года</h4>
                <p>На все работы даём гарантию до 12 месяцев. Если что-то сломается — починим бесплатно.</p>
            </div>
            <div class="adv-card reveal">
                <div class="adv-card__num">04</div>
                <h4>Прозрачные цены</h4>
                <p>Вы знаете стоимость до начала работ. Никаких доплат в процессе. Цена фиксируется в квитанции.</p>
            </div>
            <div class="adv-card reveal">
                <div class="adv-card__num">05</div>
                <h4>Любая сложность</h4>
                <p>От замены стекла до восстановления после воды и BGA-пайки. Справимся с любой поломкой.</p>
            </div>
            <div class="adv-card reveal">
                <div class="adv-card__num">06</div>
                <h4>Срочный ремонт</h4>
                <p>Нужно срочно? Починим за 24 часа. Срочный выезд и приоритетное обслуживание.</p>
            </div>
        </div>
    </div>
</section>

<section class="section" id="process">
    <div class="container">
        <div class="section__inner reveal">
            <span class="section__label">Процесс</span>
            <h2 class="section__title">Как мы работаем</h2>
            <p class="section__sub">Пять шагов от заявки до возврата устройства. Вы ничего не делаете — просто пользуетесь техникой.</p>
        </div>
        <div class="process-grid">
            <div class="process-step reveal">
                <div class="process-step__circle">1</div>
                <h4>Заявка</h4>
                <p>Оставляете заявку на сайте или по телефону</p>
            </div>
            <div class="process-step reveal">
                <div class="process-step__circle">2</div>
                <h4>Забор</h4>
                <p>Курьер забирает технику в удобное время</p>
            </div>
            <div class="process-step reveal">
                <div class="process-step__circle">3</div>
                <h4>Диагностика</h4>
                <p>Бесплатно выявляем проблему и согласуем цену</p>
            </div>
            <div class="process-step reveal">
                <div class="process-step__circle">4</div>
                <h4>Ремонт</h4>
                <p>Чиним с использованием оригинальных запчастей</p>
            </div>
            <div class="process-step reveal">
                <div class="process-step__circle">5</div>
                <h4>Доставка</h4>
                <p>Бесплатно возвращаем устройство обратно</p>
            </div>
        </div>
    </div>
</section>

<section class="section section--alt" id="testimonials">
    <div class="container">
        <div class="section__inner reveal">
            <span class="section__label">Отзывы</span>
            <h2 class="section__title">Нам доверяют клиенты</h2>
            <p class="section__sub">Более 5000 клиентов остались довольны. Вот что говорят некоторые из них.</p>
        </div>
        <div class="grid-3col--wide">
            <div class="testimonial-card reveal">
                <div class="testimonial-card__stars"><span class="filled">5.0</span></div>
                <blockquote>Отличный сервис. Приехали через 40 минут, забрали ноутбук. Через 2 дня вернули как новый. Цена порадовала — намного дешевле, чем в сервисе рядом с домом.</blockquote>
                <div class="testimonial-card__author">
                    <div class="testimonial-card__avatar" style="background:#4f46e5">АН</div>
                    <div>
                        <div class="testimonial-card__name">Алексей Н.</div>
                        <div class="testimonial-card__role">Ремонт ноутбука</div>
                    </div>
                </div>
            </div>
            <div class="testimonial-card reveal">
                <div class="testimonial-card__stars"><span class="filled">5.0</span></div>
                <blockquote>Уронила телефон в воду, думала всё. Ребята восстановили за 3 дня. До сих пор работает идеально. Огромное спасибо, буду рекомендовать всем.</blockquote>
                <div class="testimonial-card__author">
                    <div class="testimonial-card__avatar" style="background:#dc2626">ЕК</div>
                    <div>
                        <div class="testimonial-card__name">Елена К.</div>
                        <div class="testimonial-card__role">Ремонт телефона</div>
                    </div>
                </div>
            </div>
            <div class="testimonial-card reveal">
                <div class="testimonial-card__stars"><span class="filled">5.0</span></div>
                <blockquote>Починили планшет — разбил экран. Выехали в тот же день, забрали и привезли обратно с новым дисплеем. Ребёнок счастлив, родители спокойны.</blockquote>
                <div class="testimonial-card__author">
                    <div class="testimonial-card__avatar" style="background:#059669">МК</div>
                    <div>
                        <div class="testimonial-card__name">Михаил К.</div>
                        <div class="testimonial-card__role">Ремонт планшета</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cta-block">
    <div class="container reveal">
        <h2>Готовы починить технику?</h2>
        <p>Оставьте заявку — свяжемся в течение 15 минут.</p>
        <a href="#" class="btn btn--white btn--lg" @click.prevent="$dispatch('open-modal')">Вызвать мастера</a>
    </div>
</section>
