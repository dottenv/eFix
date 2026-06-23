<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Переменные окружения — <?= e($site_name ?? 'eFix') ?> Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Inter',sans-serif;background:#F5F7FA;color:#1A1A2E;padding:32px;max-width:720px;margin:0 auto}
        h1{font-size:20px;margin-bottom:24px;color:#0B2447}
        .card{background:#fff;border:1px solid #E5E7EB;border-radius:12px;padding:24px;margin-bottom:24px}
        .form-row{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px}
        input{width:100%;padding:10px 14px;border:2px solid #E5E7EB;border-radius:8px;font-family:'Inter',sans-serif;font-size:14px;transition:border-color .2s}
        input:focus{outline:none;border-color:#FF6B35;box-shadow:0 0 0 3px rgba(255,107,53,.1)}
        .btn{display:inline-flex;align-items:center;padding:10px 20px;border-radius:8px;border:none;font-weight:600;font-size:14px;cursor:pointer;transition:all .2s}
        .btn--primary{background:#FF6B35;color:#fff}
        .btn--primary:hover{background:#E85D2C}
        .btn--outline{background:transparent;color:#1A1A2E;border:1px solid #E5E7EB}
        .btn--outline:hover{border-color:#FF6B35;color:#FF6B35}
        .form-actions{display:flex;gap:8px;justify-content:flex-end;padding-top:16px;border-top:1px solid #E5E7EB;margin-top:16px}
        .text-muted{color:#6B7280;font-size:13px;margin-bottom:12px}
        code{background:#F5F7FA;padding:2px 6px;border-radius:4px;font-size:12px}
    </style>
</head>
<body>
    <h1>Переменные окружения (.env)</h1>
    <div class="card">
        <p class="text-muted">Редактирование переменных окружения. Изменения применяются после перезапуска.</p>
        <form method="POST">
            <div id="env-rows">
                <?php if(!empty($envVars)): ?>
                    <?php foreach($envVars as $env): ?>
                    <div class="form-row">
                        <input type="text" name="keys[]" value="<?= e($env['key']) ?>" placeholder="KEY">
                        <input type="text" name="values[]" value="<?= e($env['value']) ?>" placeholder="value">
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="form-row">
                        <input type="text" name="keys[]" placeholder="SECRET_KEY" value="eFix-secret-key-2024">
                        <input type="text" name="values[]" placeholder="value" value="eFix-secret-key-2024">
                    </div>
                    <div class="form-row">
                        <input type="text" name="keys[]" placeholder="DATABASE_URL" value="sqlite:../efix.db">
                        <input type="text" name="values[]" placeholder="value" value="sqlite:../efix.db">
                    </div>
                <?php endif; ?>
            </div>
            <div style="margin-bottom:16px">
                <button type="button" class="btn btn--outline btn--sm" onclick="addRow()">+ Добавить строку</button>
            </div>
            <div class="form-actions">
                <a href="/admin/dashboard" class="btn btn--outline">Назад</a>
                <button type="submit" class="btn btn--primary">Сохранить</button>
            </div>
        </form>
    </div>
    <script>
    function addRow() {
        const container = document.getElementById('env-rows');
        const row = document.createElement('div');
        row.className = 'form-row';
        row.innerHTML = '<input type="text" name="keys[]" placeholder="KEY"><input type="text" name="values[]" placeholder="value">';
        container.appendChild(row);
    }
    </script>
</body>
</html>
