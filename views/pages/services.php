<section class="page-header">
    <div class="container">
        <h1>Услуги</h1>
        <p>Ремонтируем любую цифровую технику</p>
    </div>
</section>

<section class="services-categories" x-data="{ category: null }">
    <div class="container">
        <div class="cards">
            <div class="card" @click="category = 'phones'"><h3>Телефоны</h3></div>
            <div class="card" @click="category = 'tablets'"><h3>Планшеты</h3></div>
            <div class="card" @click="category = 'laptops'"><h3>Ноутбуки</h3></div>
            <div class="card" @click="category = 'pc'"><h3>ПК</h3></div>
        </div>

        <div class="modal-overlay" x-show="category" x-cloak @keydown.escape="category = null">
            <div class="modal" @click.away="category = null">
                <button class="modal__close" @click="category = null">&times;</button>
                <h2 x-text="category"></h2>
                <ul>
                    <li>Замена экрана — от 1000 ₽</li>
                    <li>Замена аккумулятора — от 500 ₽</li>
                </ul>
                <a href="#" class="btn btn--accent" @click.prevent="$dispatch('open-modal')">Оставить заявку</a>
            </div>
        </div>
    </div>
</section>

<section class="cta">
    <div class="container">
        <h2>Не нашли свою услугу?</h2>
        <a href="#" class="btn btn--accent" @click.prevent="$dispatch('open-modal')">Свяжитесь с нами</a>
    </div>
</section>
