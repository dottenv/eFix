<?php $title = 'Услуги — ' . e($sc['site_name'] ?? 'eFix') . '. Ремонт цифровой техники в Новосибирске' ?>
<?php $extra_head = '<style>[x-cloak] { display: none !important; }</style>' ?>
<?php ob_start() ?>
<section class="page-hero">
    <div class="container">
        <h1 class="page-hero__title">Наши услуги</h1>
        <p class="page-hero__desc"><?= e($sc['services_subtitle'] ?? 'Выберите категорию, чтобы увидеть полный список услуг и цены') ?></p>
    </div>
</section>

<section class="section"
    x-data="servicesModal()"
>
    <div class="container">
        <div class="services-grid">
            <template x-for="cat in categories" :key="cat.id">
                <button class="service-tile" @click="open(cat.id)">
                    <div class="service-tile__icon" x-html="cat.icon"></div>
                    <h3 class="service-tile__title" x-text="cat.title"></h3>
                    <p class="service-tile__desc" x-text="cat.desc"></p>
                    <span class="service-tile__action">
                        Смотреть цены
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </span>
                </button>
            </template>
        </div>
    </div>

    <div class="modal" x-show="openModal" x-cloak
        @keydown.escape.window="close"
        @click.self="close"
        x-transition:enter.opacity.duration.200
        x-transition:leave.opacity.duration.200
    >
        <div class="modal__panel"
            x-show="openModal"
            x-transition:enter="modal-enter"
            x-transition:enter-start="modal-enter-start"
            x-transition:enter-end="modal-enter-end"
            x-transition:leave="modal-leave"
            x-transition:leave-start="modal-leave-start"
            x-transition:leave-end="modal-leave-end"
        >
            <div class="modal__header">
                <div class="modal__title">
                    <span class="modal__icon" x-html="current.icon"></span>
                    <h2 x-text="current.title"></h2>
                </div>
                <button class="modal__close" @click="close" aria-label="Закрыть">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <p class="modal__desc" x-text="current.desc"></p>
            <div class="modal__services">
                <template x-for="(item, i) in current.services" :key="i">
                    <div class="modal__row">
                        <span class="modal__row-name" x-text="item.name"></span>
                        <span class="modal__row-price" x-text="item.price"></span>
                    </div>
                </template>
            </div>
            <div class="modal__footer">
                <button @click="$store.modalCallback.open = true" class="btn btn--primary btn--large btn--full">
                    Оставить заявку на ремонт
                </button>
            </div>
        </div>
    </div>
</section>

<section class="section cta-section">
    <div class="container">
        <div class="cta-card">
            <div class="cta-card__content">
                <h2 class="cta-card__title"><?= $sc['cta_services_title'] ?? 'Не нашли свою услугу?' ?></h2>
                <p class="cta-card__desc"><?= e($sc['cta_services_desc'] ?? 'Свяжитесь с нами — мы решим любую проблему с вашей техникой.') ?></p>
                <a href="<?= url_for('main.contacts') ?>" class="btn btn--large btn--primary">Связаться с нами</a>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('servicesModal', () => ({
        openModal: false,
        current: {},
        categories: <?= json_encode($categories ?? [], JSON_UNESCAPED_UNICODE) ?>,
        servicesByCat: <?= json_encode($services_by_cat ?? [], JSON_UNESCAPED_UNICODE) ?>,
        open(id) {
            const cat = this.categories.find(c => c.id === id);
            if (!cat) return;
            this.current = {
                ...cat,
                services: this.servicesByCat[id] || [],
            };
            this.openModal = true;
        },
        close() {
            this.openModal = false;
        },
    }));
});
</script>
<?php $content = ob_get_clean() ?>
<?php include __DIR__ . '/base.php' ?>
