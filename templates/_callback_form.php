<form class="hero-form" id="callbackForm"
    hx-post="<?= url_for('api.callback') ?>"
    hx-target="#callbackForm"
    hx-swap="outerHTML"
    hx-trigger="submit"
>
    <div class="hero-form__group">
        <input type="text" name="name" placeholder="Ваше имя" value="<?= e($name ?? '') ?>" required>
        <?php if(isset($errors) && isset($errors['name'])): ?><span class="hero-form__error"><?= e($errors['name']) ?></span><?php endif ?>
    </div>
    <div class="hero-form__group">
        <input type="tel" name="phone" placeholder="+7 (999) 999-99-99" value="<?= e($phone ?? '') ?>" required class="phone-mask">
        <?php if(isset($errors) && isset($errors['phone'])): ?><span class="hero-form__error"><?= e($errors['phone']) ?></span><?php endif ?>
    </div>
    <div class="hero-form__group">
        <select name="device_type">
            <option value="">Тип устройства</option>
            <option value="phone" <?= ($device_type ?? '') === 'phone' ? 'selected' : '' ?>>Телефон</option>
            <option value="tablet" <?= ($device_type ?? '') === 'tablet' ? 'selected' : '' ?>>Планшет</option>
            <option value="laptop" <?= ($device_type ?? '') === 'laptop' ? 'selected' : '' ?>>Ноутбук</option>
            <option value="pc" <?= ($device_type ?? '') === 'pc' ? 'selected' : '' ?>>ПК / Моноблок</option>
        </select>
    </div>
    <div class="hero-form__group">
        <input type="text" name="device_model" placeholder="Модель (iPhone 13, Galaxy S24...)" value="<?= e($device_model ?? '') ?>">
    </div>
    <div class="hero-form__group">
        <textarea name="message" placeholder="Опишите проблему..." rows="2"><?= e($message ?? '') ?></textarea>
    </div>
    <button type="submit" class="btn btn--large btn--primary hero-form__btn">
        Отправить заявку
    </button>
    <p class="hero-form__note">Перезвоним в течение 15 минут. Бесплатно.</p>
</form>
