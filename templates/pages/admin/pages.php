<div class="pages-page">
    <h1 class="admin-title">Управление страницами</h1>
    <button class="btn btn--primary" onclick="toggleForm()">+ Добавить блок</button>

    <form class="admin-form" id="pageForm" method="POST" action="/admin/pages/save" style="display:none">
        <h2 class="admin-subtitle">Новый блок</h2>
        <div class="form-group">
            <label class="form-label">Название</label>
            <input class="form-input" type="text" name="title" required>
        </div>
        <div class="form-group">
            <label class="form-label">Slug</label>
            <input class="form-input" type="text" name="slug" required placeholder="about-us">
        </div>
        <div class="form-group">
            <label class="form-label">Подзаголовок</label>
            <input class="form-input" type="text" name="subtitle">
        </div>
        <div class="form-group">
            <label class="form-label">Содержимое</label>
            <textarea class="form-input form-textarea" name="content" rows="6"></textarea>
        </div>
        <div class="form-group">
            <label class="form-label">Секция</label>
            <select class="form-input" name="section">
                <option value="hero">Hero</option>
                <option value="advantages">Преимущества</option>
                <option value="reviews">Отзывы</option>
                <option value="contacts">Контакты</option>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Порядок</label>
            <input class="form-input" type="number" name="sort_order" value="0">
        </div>
        <div class="form-group">
            <label class="form-label">
                <input type="checkbox" name="is_active" value="1" checked> Активен
            </label>
        </div>
        <button class="btn btn--primary" type="submit">Сохранить</button>
        <button class="btn" type="button" onclick="toggleForm()">Отмена</button>
    </form>

    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Секция</th>
                <th>Порядок</th>
                <th>Активен</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pages as $page): ?>
            <tr>
                <td><?= $page['id'] ?></td>
                <td><?= $this->escape($page['title']) ?></td>
                <td><?= $this->escape($page['section']) ?></td>
                <td><?= $page['sort_order'] ?></td>
                <td><?= $page['is_active'] ? 'Да' : 'Нет' ?></td>
                <td>
                    <a class="btn btn--danger btn--sm" href="/admin/pages/delete/<?= $page['id'] ?>" onclick="return confirm('Удалить?')">Удалить</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
function toggleForm() {
    const form = document.getElementById('pageForm');
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}
</script>
