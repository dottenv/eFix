<div class="services-page">
    <h1 class="admin-title">Услуги</h1>
    <button class="btn btn--primary" onclick="toggleForm()">+ Добавить услугу</button>

    <form class="admin-form" id="serviceForm" method="POST" action="/admin/services/save" style="display:none">
        <h2 class="admin-subtitle">Новая услуга</h2>
        <div class="form-group">
            <label class="form-label">Название</label>
            <input class="form-input" type="text" name="title" required>
        </div>
        <div class="form-group">
            <label class="form-label">Slug</label>
            <input class="form-input" type="text" name="slug" required placeholder="phone-repair">
        </div>
        <div class="form-group">
            <label class="form-label">Описание</label>
            <textarea class="form-input form-textarea" name="description" rows="4"></textarea>
        </div>
        <div class="form-group">
            <label class="form-label">Иконка (emoji)</label>
            <input class="form-input" type="text" name="icon" placeholder="📱">
        </div>
        <div class="form-group">
            <label class="form-label">Порядок</label>
            <input class="form-input" type="number" name="sort_order" value="0">
        </div>
        <div class="form-group">
            <label class="form-label">
                <input type="checkbox" name="is_active" value="1" checked> Активна
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
                <th>Slug</th>
                <th>Порядок</th>
                <th>Активна</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($services as $service): ?>
            <tr>
                <td><?= $service['id'] ?></td>
                <td><?= $this->escape($service['title']) ?></td>
                <td><?= $this->escape($service['slug']) ?></td>
                <td><?= $service['sort_order'] ?></td>
                <td><?= $service['is_active'] ? 'Да' : 'Нет' ?></td>
                <td>
                    <a class="btn btn--danger btn--sm" href="/admin/services/delete/<?= $service['id'] ?>" onclick="return confirm('Удалить?')">Удалить</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
function toggleForm() {
    const form = document.getElementById('serviceForm');
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}
</script>
