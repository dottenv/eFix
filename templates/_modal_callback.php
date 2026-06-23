<div class="modal-overlay" x-data x-show="$store.modalCallback.open" x-cloak
     @keydown.escape.window="$store.modalCallback.open = false">
    <div class="modal-panel" @click.away="$store.modalCallback.open = false" x-show="$store.modalCallback.open" x-transition>
        <button class="modal-panel__close" @click="$store.modalCallback.open = false" aria-label="Закрыть">&times;</button>
        <div class="modal-panel__body">
            <div class="modal-panel__icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            </div>
            <h3 class="modal-panel__title">Заказать ремонт</h3>
            <p class="modal-panel__desc">Оставьте заявку — мастер перезвонит через 15 минут</p>
            <form id="modalForm"
                hx-post="<?= url_for('api.callback') ?>"
                hx-target="#modalForm"
                hx-swap="outerHTML"
                hx-trigger="submit"
            >
                <div class="modal-form__group">
                    <input type="text" name="name" placeholder="Ваше имя" required>
                </div>
                <div class="modal-form__group">
                    <input type="tel" name="phone" placeholder="+7 (999) 999-99-99" required class="phone-mask">
                </div>
                <div class="modal-form__group">
                    <select name="device_type">
                        <option value="">Тип устройства</option>
                        <option value="phone">Телефон</option>
                        <option value="tablet">Планшет</option>
                        <option value="laptop">Ноутбук</option>
                        <option value="pc">ПК / Моноблок</option>
                    </select>
                </div>
                <div class="modal-form__group">
                    <input type="text" name="device_model" placeholder="Модель (iPhone 13, Galaxy S24...)">
                </div>
                <div class="modal-form__group">
                    <textarea name="message" placeholder="Опишите проблему..." rows="2"></textarea>
                </div>
                <button type="submit" class="btn btn--primary btn--large btn--full">Отправить заявку</button>
                <p class="modal-form__note">Бесплатная диагностика. Гарантия до 1 года.</p>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.store('modalCallback', { open: false });
    document.addEventListener('openModalCallback', () => {
        Alpine.store('modalCallback').open = true;
    });
});
</script>
