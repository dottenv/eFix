<section class="hero" id="hero">
    <div class="container hero__inner">
        <h1 class="hero__title"><?= $this->escape($hero['title'] ?? 'Ремонт цифровой техники в Минске') ?></h1>
        <p class="hero__subtitle"><?= $this->escape($hero['subtitle'] ?? 'Бесплатная диагностика. Гарантия до 1 года. Ремонт за 1 день.') ?></p>
        <a href="#order" class="btn btn--primary btn--lg">Оставить заявку</a>
    </div>
</section>

<section class="services" id="services">
    <div class="container">
        <h2 class="section-title">Наши услуги</h2>
        <div class="services__grid">
            <?php foreach ($services as $service): ?>
            <div class="service-card">
                <div class="service-card__icon"><?= $this->escape($service['icon'] ?? '🔧') ?></div>
                <h3 class="service-card__title"><?= $this->escape($service['title']) ?></h3>
                <p class="service-card__description"><?= nl2br($this->escape($service['description'])) ?></p>
                <a href="/service/<?= $this->escape($service['slug']) ?>" class="service-card__link">Подробнее</a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="advantages" id="advantages">
    <div class="container">
        <h2 class="section-title">Почему выбирают нас</h2>
        <div class="advantages__grid">
            <?php foreach ($advantages as $item): ?>
            <div class="advantage-card">
                <h3 class="advantage-card__title"><?= $this->escape($item['title']) ?></h3>
                <p class="advantage-card__text"><?= nl2br($this->escape($item['content'])) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="order" id="order">
    <div class="container">
        <h2 class="section-title">Оставить заявку</h2>
        <form class="order-form" id="orderForm" method="POST" action="/lead">
            <div style="position:absolute;left:-9999px">
                <input type="text" name="website" tabindex="-1" autocomplete="off">
            </div>
            <div class="form-group">
                <label class="form-label" for="name">Имя *</label>
                <input class="form-input" type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="phone">Телефон *</label>
                <input class="form-input" type="tel" id="phone" name="phone" required placeholder="+375 (29) 123-45-67">
            </div>
            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input class="form-input" type="email" id="email" name="email">
            </div>
            <div class="form-group">
                <label class="form-label" for="service_type">Тип услуги *</label>
                <select class="form-input" id="service_type" name="service_type" required>
                    <option value="">— Выберите —</option>
                    <option value="phone">Ремонт телефонов</option>
                    <option value="tablet">Ремонт планшетов</option>
                    <option value="laptop">Ремонт ноутбуков</option>
                    <option value="pc">Ремонт компьютеров</option>
                    <option value="other">Другое</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label" for="device_brand">Бренд устройства</label>
                <input class="form-input" type="text" id="device_brand" name="device_brand" placeholder="Например: Apple, Samsung">
            </div>
            <div class="form-group">
                <label class="form-label" for="device_model">Модель устройства</label>
                <input class="form-input" type="text" id="device_model" name="device_model" placeholder="Например: iPhone 14">
            </div>
            <div class="form-group">
                <label class="form-label" for="message">Описание проблемы</label>
                <textarea class="form-input form-textarea" id="message" name="message" rows="4"></textarea>
            </div>
            <button class="btn btn--primary btn--lg" type="submit">Отправить</button>
            <div class="form-message" id="formMessage"></div>
        </form>
    </div>
</section>

<script>
document.getElementById('orderForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = e.target;
    const msg = document.getElementById('formMessage');
    const btn = form.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.textContent = 'Отправка...';
    try {
        const res = await fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await res.json();
        if (data.success) {
            msg.className = 'form-message form-message--success';
            msg.textContent = data.message;
            form.reset();
        } else {
            msg.className = 'form-message form-message--error';
            msg.textContent = data.errors ? data.errors.join(', ') : (data.error || 'Ошибка');
        }
    } catch (err) {
        msg.className = 'form-message form-message--error';
        msg.textContent = 'Ошибка отправки. Попробуйте позже.';
    } finally {
        btn.disabled = false;
        btn.textContent = 'Отправить';
    }
});
</script>
