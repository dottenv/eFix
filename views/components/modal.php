<div class="modal-overlay" x-data="{ open: false }"
     @open-modal.window="open = true"
     @keydown.escape="open = false"
     x-show="open"
     x-cloak
     x-transition>
    <div class="modal" @click.away="open = false">
        <button class="modal__close" @click="open = false">&times;</button>
        <h2>Заказать звонок</h2>
        <p style="color:var(--text-light);margin-bottom:1.25rem;font-size:.9rem">Оставьте заявку — мы перезвоним в течение 15 минут</p>
        <form hx-post="/contacts/send" hx-target="this" hx-swap="innerHTML">
            <input type="text" name="name" placeholder="Ваше имя" required>
            <input type="tel" name="phone" placeholder="+7 (___) ___-__-__" required>
            <select name="device">
                <option value="">Тип устройства</option>
                <option value="phone">📱 Телефон</option>
                <option value="tablet">📟 Планшет</option>
                <option value="laptop">💻 Ноутбук</option>
                <option value="pc">🖥️ ПК</option>
            </select>
            <textarea name="message" placeholder="Опишите проблему" rows="3"></textarea>
            <button type="submit" class="btn btn--primary" style="width:100%;justify-content:center">Отправить заявку</button>
        </form>
    </div>
</div>
