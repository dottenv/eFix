<section class="page-header">
    <div class="container">
        <h1>Цены</h1>
        <p>Прозрачные цены без скрытых платежей</p>
        <p class="note">Диагностика — бесплатно!</p>
    </div>
</section>

<section class="prices" x-data="{ type: 'all', brand: '', search: '' }">
    <div class="container">
        <div class="filters">
            <select x-model="type">
                <option value="all">Все типы</option>
                <option value="phone">Телефоны</option>
                <option value="tablet">Планшеты</option>
                <option value="laptop">Ноутбуки</option>
                <option value="pc">ПК</option>
            </select>
            <input type="text" x-model="brand" placeholder="Бренд">
            <input type="text" x-model="search" placeholder="Поиск...">
        </div>

        <table class="price-table">
            <thead><tr><th>Тип</th><th>Бренд</th><th>Модель</th><th>Услуга</th><th>Цена</th></tr></thead>
            <tbody>
                <tr><td colspan="5">Загрузка...</td></tr>
            </tbody>
        </table>
    </div>
</section>

<section class="cta">
    <div class="container">
        <h2>Нужна консультация?</h2>
        <a href="#" class="btn btn--accent" @click.prevent="$dispatch('open-modal')">Заказать звонок</a>
    </div>
</section>
