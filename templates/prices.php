<?php $title = 'Цены — ' . e($sc['site_name'] ?? 'eFix') . '. Стоимость ремонта техники в Новосибирске' ?>
<?php $extra_head = '<style>[x-cloak] { display: none !important; }</style>' ?>
<?php ob_start() ?>
<section class="page-hero">
    <div class="container">
        <h1 class="page-hero__title">Прайс-лист</h1>
        <p class="page-hero__desc"><?= e($sc['prices_subtitle'] ?? 'Актуальные цены на ремонт цифровой техники. Поиск по типу, бренду, модели и услуге.') ?></p>
    </div>
</section>

<section class="section">
    <div class="container">
        <form class="price-filters" id="price-filters"
            x-data="priceFilters()"
            x-init="init()"
            hx-get="/api/prices-table"
            hx-target="#prices-table-wrap"
            hx-trigger="change from:#filter-type, change from:#filter-brand, change from:#filter-model, keyup changed delay:300ms from:#filter-search, search from:#filter-search"
        >
            <div class="price-filters__row">
                <div class="price-filters__group">
                    <label>Тип устройства</label>
                    <select name="type" id="filter-type"
                        @change="onTypeChange($event.target.value)"
                    >
                        <option value="">Все типы</option>
                        <option value="phone">Телефоны</option>
                        <option value="tablet">Планшеты</option>
                        <option value="laptop">Ноутбуки</option>
                        <option value="pc">ПК и моноблоки</option>
                    </select>
                </div>
                <div class="price-filters__group">
                    <label>Бренд</label>
                    <select name="brand" id="filter-brand"
                        @change="onBrandChange($event.target.value)"
                        x-html="brandOptions"
                    >
                        <option value="">Загрузка...</option>
                    </select>
                </div>
                <div class="price-filters__group">
                    <label>Модель</label>
                    <select name="model" id="filter-model" x-html="modelOptions">
                        <option value="">Загрузка...</option>
                    </select>
                </div>
                <div class="price-filters__group price-filters__group--wide">
                    <label>Поиск</label>
                    <input type="text" name="q" id="filter-search" placeholder="Поиск по услуге, бренду, модели...">
                </div>
            </div>
        </form>

        <div class="table-area" x-data>
            <div class="table-spinner" x-show="$store.loading.active" x-cloak>
                <div class="spinner"></div>
                <span>Загрузка...</span>
            </div>
            <div id="prices-table-wrap"
                hx-get="/api/prices-table"
                hx-trigger="load"
                hx-target="#prices-table-wrap"
                hx-swap="innerHTML"
            >
                <div class="prices-table__loading">Загрузка...</div>
            </div>
        </div>

        <div class="prices-note">
            <p><?= $sc['prices_notice'] ?? '<strong>Диагностика — бесплатно!</strong> Точная стоимость определяется после осмотра устройства.' ?></p>
        </div>
    </div>
</section>

<section class="section cta-section">
    <div class="container">
        <div class="cta-card">
            <div class="cta-card__content">
                <h2 class="cta-card__title"><?= $sc['cta_prices_title'] ?? 'Узнайте точную стоимость' ?></h2>
                <p class="cta-card__desc"><?= e($sc['cta_prices_desc'] ?? 'Оставьте заявку — мы перезвоним и назовём цену после бесплатной диагностики.') ?></p>
                <a href="<?= url_for('main.contacts') ?>" class="btn btn--large btn--primary">Оставить заявку</a>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.store('loading', { active: false });
});
</script>
<?php $content = ob_get_clean() ?>
<?php include __DIR__ . '/base.php' ?>
