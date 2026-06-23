<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход — <?= e($site_name ?? 'eFix') ?> Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        body{
            font-family:'Inter',sans-serif;background:#0B2447;
            display:flex;align-items:center;justify-content:center;
            min-height:100vh;padding:24px
        }
        .auth-card{
            background:#fff;border-radius:16px;padding:40px;
            width:100%;max-width:400px;box-shadow:0 12px 40px rgba(0,0,0,.2)
        }
        .auth-card__logo{font-size:24px;font-weight:800;color:#0B2447;text-align:center;margin-bottom:4px}
        .auth-card__logo span{color:#FF6B35}
        .auth-card__sub{text-align:center;color:#6B7280;font-size:14px;margin-bottom:32px}
        .form-group{margin-bottom:20px}
        .form-group label{display:block;font-size:13px;font-weight:600;color:#6B7280;margin-bottom:6px}
        .form-group input{
            width:100%;padding:12px 16px;border:2px solid #E5E7EB;border-radius:8px;
            font-family:'Inter',sans-serif;font-size:15px;transition:all .2s
        }
        .form-group input:focus{outline:none;border-color:#FF6B35;box-shadow:0 0 0 3px rgba(255,107,53,.1)}
        .btn{
            width:100%;padding:14px;border-radius:8px;border:none;
            background:#FF6B35;color:#fff;font-weight:600;font-size:15px;
            cursor:pointer;transition:all .2s
        }
        .btn:hover{background:#E85D2C}
        .error{background:#FEE2E2;color:#DC2626;padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:16px;text-align:center}
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-card__logo"><?= e($site_name ?? 'eFix') ?> / Admin</div>
        <p class="auth-card__sub">Вход в панель управления</p>
        <?php if(!empty($error)): ?>
            <div class="error"><?= e($error) ?></div>
        <?php endif ?>
        <form method="POST">
            <div class="form-group">
                <label>Имя пользователя</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Пароль</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn">Войти</button>
        </form>
    </div>
</body>
</html>
