<div class="modal-overlay" x-data="{ open: false }"
     @open-modal.window="open = true"
     @keydown.escape="open = false"
     x-show="open"
     x-cloak>
    <div class="modal" @click.away="open = false">
        <button class="modal__close" @click="open = false">&times;</button>
        <h2>Заказать звонок</h2>
        <form hx-post="/contacts/send" hx-swap="innerHTML" @submit="open = false">
            <input type="text" name="name" placeholder="Ваше имя" required>
            <input type="tel" name="phone" placeholder="Ваш телефон" required>
            <select name="device">
                <option value="">Тип устройства</option>
                <option value="phone">Телефон</option>
                <option value="tablet">Планшет</option>
                <option value="laptop">Ноутбук</option>
                <option value="pc">ПК</option>
            </select>
            <input type="text" name="model" placeholder="Модель">
            <textarea name="message" placeholder="Опишите проблему"></textarea>
            <button type="submit" class="btn btn--accent">Отправить</button>
        </form>
    </div>
</div>
