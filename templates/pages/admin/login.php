<div class="login-page">
    <form class="login-form" id="loginForm" method="POST" action="/admin/login">
        <h1 class="login-form__title">Вход в админ-панель</h1>
        <div class="form-group">
            <label class="form-label" for="username">Логин</label>
            <input class="form-input" type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label class="form-label" for="password">Пароль</label>
            <input class="form-input" type="password" id="password" name="password" required>
        </div>
        <button class="btn btn--primary btn--full" type="submit">Войти</button>
        <div class="form-message" id="loginError"></div>
    </form>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = e.target;
    const error = document.getElementById('loginError');
    const btn = form.querySelector('button');
    btn.disabled = true;
    btn.textContent = 'Вход...';
    try {
        const res = await fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await res.json();
        if (data.success) {
            window.location.href = data.redirect;
        } else {
            error.className = 'form-message form-message--error';
            error.textContent = data.error || 'Ошибка входа';
        }
    } catch (err) {
        error.className = 'form-message form-message--error';
        error.textContent = 'Ошибка сервера';
    } finally {
        btn.disabled = false;
        btn.textContent = 'Войти';
    }
});
</script>
