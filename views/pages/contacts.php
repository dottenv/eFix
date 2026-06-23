<section class="page-header">
    <div class="container">
        <h1>Контакты</h1>
        <p>Свяжитесь с нами любым удобным способом</p>
    </div>
</section>

<section class="contacts">
    <div class="container">
        <div class="contacts__grid">
            <div class="contacts__info">
                <p>📞 +7 (999) 999-99-99</p>
                <p>✉️ info@efix.ru</p>
                <p>📍 Москва, ул. Примерная, д. 1</p>
                <div class="contacts__social">
                    <a href="#">WhatsApp</a>
                    <a href="#">Telegram</a>
                    <a href="#">VK</a>
                </div>
            </div>
            <div class="contacts__form">
                <form hx-post="/contacts/send" hx-target="this" hx-swap="outerHTML">
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
    </div>
</section>
