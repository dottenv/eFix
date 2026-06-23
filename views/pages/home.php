<section class="hero">
    <div class="container">
        <h1>Ремонтируем технику с выездом к вам</h1>
        <p>Заберём, починим и привезём обратно — бесплатно</p>
        <div class="hero__actions">
            <a href="#" class="btn btn--accent" @click.prevent="$dispatch('open-modal')">Вызвать мастера</a>
            <a href="/prices" class="btn btn--outline">Смотреть цены</a>
        </div>
        <div class="hero__stats">
            <div><span>8</span> лет опыта</div>
            <div><span>5000+</span> отремонтировано</div>
            <div><span>98%</span> довольных клиентов</div>
        </div>
        <div class="hero__form">
            <form hx-post="/contacts/send" hx-swap="innerHTML">
                <input type="text" name="name" placeholder="Ваше имя" required>
                <input type="tel" name="phone" placeholder="Ваш телефон" required>
                <button type="submit" class="btn btn--accent">Заказать звонок</button>
            </form>
        </div>
    </div>
</section>

<section class="services-grid" x-data>
    <div class="container">
        <h2>Наши услуги</h2>
        <div class="cards">
            <div class="card" @click="alert('Телефоны')"><h3>Телефоны</h3><p>от 500 ₽</p></div>
            <div class="card" @click="alert('Планшеты')"><h3>Планшеты</h3><p>от 800 ₽</p></div>
            <div class="card" @click="alert('Ноутбуки')"><h3>Ноутбуки</h3><p>от 1000 ₽</p></div>
            <div class="card" @click="alert('ПК')"><h3>ПК</h3><p>от 1000 ₽</p></div>
        </div>
    </div>
</section>

<section class="advantages">
    <div class="container">
        <h2>Почему выбирают нас</h2>
        <div class="advantages__grid">
            <div class="advantage">Забор за час</div>
            <div class="advantage">Бесплатная диагностика</div>
            <div class="advantage">Гарантия до года</div>
            <div class="advantage">Прозрачные цены</div>
            <div class="advantage">Сложность любая</div>
            <div class="advantage">Срочный ремонт</div>
        </div>
    </div>
</section>

<section class="process">
    <div class="container">
        <h2>Как мы работаем</h2>
        <div class="process__steps">
            <div class="step">Заявка</div>
            <div class="step">Забор</div>
            <div class="step">Диагностика</div>
            <div class="step">Ремонт</div>
            <div class="step">Доставка</div>
        </div>
    </div>
</section>

<section class="reviews">
    <div class="container">
        <h2>Отзывы</h2>
        <div class="reviews__grid">
            <div class="review">...</div>
            <div class="review">...</div>
            <div class="review">...</div>
        </div>
    </div>
</section>

<section class="cta">
    <div class="container">
        <h2>Оставьте заявку прямо сейчас</h2>
        <a href="#" class="btn btn--accent" @click.prevent="$dispatch('open-modal')">Заказать звонок</a>
    </div>
</section>
