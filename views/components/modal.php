<div class="modal-overlay" x-data="{ open: false }"
     @open-modal.window="open = true"
     @keydown.escape="open = false"
     x-show="open"
     x-cloak
     x-transition>
    <div class="modal" @click.away="open = false">
        <button class="modal__close" @click="open = false">&times;</button>
        <h2>Оставить заявку</h2>
        <p class="modal__sub">Перезвоним в течение 15 минут</p>
        <form hx-post="/contacts/send" hx-target="this" hx-swap="innerHTML">
            <input type="text" name="name" placeholder="Ваше имя" required>
            <input type="tel" name="phone" placeholder="Номер телефона" required>
            <select name="device">
                <option value="">Тип устройства</option>
                <option value="phone">Телефон</option>
                <option value="tablet">Планшет</option>
                <option value="laptop">Ноутбук</option>
                <option value="pc">ПК</option>
            </select>
            <textarea name="message" placeholder="Опишите проблему" rows="3"></textarea>
            <button type="submit" class="btn btn--primary btn--block">Отправить</button>
        </form>
    </div>
</div>
