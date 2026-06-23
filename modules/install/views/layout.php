<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Установка eFix</title>
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{--primary:#0B2447;--accent:#FF6B35;--bg:#F5F7FA;--surface:#FFF;--text:#1A1A2E;--text-muted:#6B7280;--border:#E5E7EB;--success:#10B981;--danger:#EF4444;--radius:12px;--step-size:36px}
body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',system-ui,sans-serif;background:var(--bg);color:var(--text);line-height:1.6;padding:32px 16px;min-height:100vh}
.container{max-width:680px;margin:0 auto}
.card{background:var(--surface);border-radius:var(--radius);box-shadow:0 4px 24px rgba(0,0,0,.08);padding:32px;margin-bottom:20px}
h1{font-size:26px;font-weight:800;color:var(--primary)}h1 span{color:var(--accent)}
h2{font-size:16px;font-weight:700;color:var(--primary);margin-bottom:16px;padding-bottom:6px;border-bottom:2px solid var(--border)}
p.subtitle{color:var(--text-muted);margin-bottom:20px;font-size:14px}
.steps{display:flex;gap:4px;margin-bottom:28px;counter-reset:step}
.step{flex:1;text-align:center;font-size:12px;font-weight:600;color:var(--text-muted);position:relative;padding-top:calc(var(--step-size) + 8px)}
.step::before{counter-increment:step;content:counter(step);position:absolute;top:0;left:50%;transform:translateX(-50%);width:var(--step-size);height:var(--step-size);border-radius:50%;background:var(--border);color:var(--text-muted);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;transition:.3s}
.step::after{content:'';position:absolute;top:calc(var(--step-size)/2);right:50%;width:100%;height:2px;background:var(--border);z-index:0;transition:.3s}
.step:last-child::after{display:none}
.step--active{color:var(--primary)}.step--active::before{background:var(--accent);color:#fff}
.step--done{color:var(--success)}.step--done::before{background:var(--success);color:#fff;content:'✓'}
.step--done::after{background:var(--success)}
.panel{display:none;animation:fadeIn .3s ease}
.panel--active{display:block}
@keyframes fadeIn{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}
.cl{list-style:none}.cl li{display:flex;align-items:center;gap:10px;padding:7px 0;font-size:14px;border-bottom:1px solid #f0f0f0}
.cl li:last-child{border-bottom:none}
.cl__icon{width:20px;text-align:center;flex-shrink:0;font-size:16px}
.cl__label{flex:1;font-weight:500}
.cl__detail{font-size:12px;color:var(--text-muted)}
.cl__spinner{display:inline-block;width:16px;height:16px;border:2px solid var(--border);border-top-color:var(--accent);border-radius:50%;animation:spin .6s linear infinite}
@keyframes spin{to{transform:rotate(360deg)}}
.progress{height:6px;background:var(--border);border-radius:3px;overflow:hidden;margin:12px 0}
.progress__bar{height:100%;background:linear-gradient(90deg,var(--accent),#E85D2C);border-radius:3px;transition:width .4s ease;width:0%}
.progress__text{font-size:13px;color:var(--text-muted);text-align:center;margin-top:8px;min-height:20px}
.form-group{margin-bottom:18px}
.form-group label{display:block;font-weight:600;font-size:13px;margin-bottom:4px;color:var(--primary)}
.form-group .hint{font-size:12px;color:var(--text-muted);margin-top:4px}
.form-group .field-error{font-size:12px;color:var(--danger);margin-top:4px;display:none}
.form-group.invalid input,.form-group.invalid select{border-color:var(--danger)}
.form-group.invalid .field-error{display:block}
.form-group.valid input,.form-group.valid select{border-color:var(--success)}
input,select,textarea{width:100%;padding:10px 14px;border:2px solid var(--border);border-radius:8px;font-size:14px;font-family:inherit;transition:border-color .2s;background:var(--surface);color:var(--text)}
input:focus,select:focus,textarea:focus{outline:none;border-color:var(--accent);box-shadow:0 0 0 3px rgba(255,107,53,.1)}
.row{display:flex;gap:12px}.row>*{flex:1}@media(max-width:600px){.row{flex-direction:column}}
.password-wrap{position:relative}.password-wrap input{padding-right:40px}
.toggle-pass{position:absolute;right:6px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;padding:6px;color:var(--text-muted);font-size:16px;line-height:1}
.toggle-pass:hover{color:var(--text)}
.radio-cards{display:flex;gap:12px}
.radio-card{flex:1;border:2px solid var(--border);border-radius:8px;padding:14px;cursor:pointer;text-align:center;transition:.2s;font-weight:600;font-size:13px;background:var(--surface)}
.radio-card:hover{border-color:var(--accent)}
.radio-card--selected{border-color:var(--accent);background:rgba(255,107,53,.05);box-shadow:0 0 0 3px rgba(255,107,53,.1)}
.radio-card input{display:none}
.radio-card__sub{font-weight:400;font-size:11px;color:var(--text-muted);margin-top:4px}
.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:12px 28px;font-size:15px;font-weight:700;border:none;border-radius:8px;cursor:pointer;transition:.2s;text-decoration:none}
.btn--primary{background:var(--accent);color:#fff}.btn--primary:hover{opacity:.9;transform:translateY(-1px)}
.btn--secondary{background:var(--primary);color:#fff}.btn--secondary:hover{opacity:.9}
.btn--outline{background:transparent;color:var(--primary);border:2px solid var(--primary)}
.btn--outline:hover{background:var(--primary);color:#fff}
.btn--full{width:100%}.btn:disabled{opacity:.5;cursor:not-allowed;transform:none!important}
.alert{padding:14px 18px;border-radius:8px;font-size:13px;font-weight:600;margin-bottom:16px}
.alert-error{background:#FEF2F2;color:var(--danger);border:1px solid #FECACA}
.alert-success{background:#F0FDF4;color:#166534;border:1px solid #BBF7D0}
.alert-info{background:#EFF6FF;color:#1E40AF;border:1px solid #BFDBFE}
.alert-warning{background:#FFFBEB;color:#92400E;border:1px solid #FDE68A}
.actions{display:flex;gap:12px;margin-top:20px;justify-content:space-between;align-items:center}
.success-links{list-style:none;margin:16px 0}
.success-links li{padding:6px 0}.success-links a{color:var(--accent);font-weight:600;text-decoration:none}
.success-links a:hover{text-decoration:underline}
.footer-note{text-align:center;color:var(--text-muted);font-size:12px;margin-top:24px}
code{background:#F3F4F6;padding:1px 5px;border-radius:4px;font-size:12px}
.db-section{display:none}
</style>
</head>
<body>
<div class="container">
    <div class="card" style="text-align:center;padding:20px">
        <h1>e<span>Fix</span></h1>
        <p class="subtitle" style="margin-bottom:0">Установка серверного центра</p>
    </div>

    <div class="steps" id="stepIndicator">
        <div class="step step--done" data-step="1">Проверка системы</div>
        <div class="step" data-step="2">Файлы проекта</div>
        <div class="step" data-step="3">Настройка</div>
        <div class="step" data-step="4">Установка</div>
    </div>

    <div class="panel panel--active" id="panel1">
        <div class="card">
            <h2>Проверка системы</h2>
            <ul class="cl" id="checkList">
                <?php foreach ($checks as $c): ?>
                <li id="chk-<?= $c['id'] ?>">
                    <span class="cl__icon" id="icon-<?= $c['id'] ?>">
                        <?php if ($c['ok']): ?><span style="color:var(--success)">✓</span>
                        <?php elseif ($c['warn'] ?? false): ?><span style="color:var(--text-muted)">?</span>
                        <?php else: ?><span style="color:var(--danger)">✗</span><?php endif ?>
                    </span>
                    <span class="cl__label"><?= htmlspecialchars($c['label']) ?></span>
                    <?php if (isset($c['detail'])): ?>
                    <span class="cl__detail"><?= htmlspecialchars($c['detail']) ?></span>
                    <?php endif ?>
                </li>
                <?php endforeach ?>
            </ul>
            <?php if (!$all_ok): ?>
            <div class="alert alert-warning">Некоторые проверки не пройдены. Установка может работать нестабильно.</div>
            <?php endif ?>
        </div>
        <div class="actions">
            <span></span>
            <button class="btn btn--primary" onclick="goStep(2)">Продолжить →</button>
        </div>
    </div>

    <div class="panel" id="panel2">
        <div class="card">
            <h2>Проверка файлов проекта</h2>
            <?php if ($files_ok): ?>
                <div class="alert alert-success">Все <?= count(\PROJECT_FILES) ?> файлов проекта на месте.</div>
            <?php else: ?>
                <div class="alert alert-warning">Отсутствует <?= count($missing) ?> файлов. Нужно скачать с GitHub.</div>
                <ul class="cl" style="max-height:200px;overflow-y:auto;margin-bottom:12px">
                    <?php foreach ($missing as $mf): ?>
                    <li style="font-size:12px;padding:4px 0"><code><?= htmlspecialchars($mf) ?></code></li>
                    <?php endforeach ?>
                </ul>
                <?php if (extension_loaded('zip')): ?>
                <button class="btn btn--primary btn--full" id="downloadBtn" onclick="startDownload()">
                    <span id="dlIcon" class="cl__spinner" style="display:none"></span>
                    <span id="dlText">Скачать проект с GitHub</span>
                </button>
                <div class="progress" id="dlProgressWrap" style="display:none">
                    <div class="progress__bar" id="dlProgressBar"></div>
                </div>
                <div class="progress__text" id="dlStatus"></div>
                <?php else: ?>
                <div class="alert alert-error">Расширение PHP Zip не найдено. Загрузите файлы через FTP вручную.</div>
                <?php endif ?>
            <?php endif ?>
        </div>
        <div class="actions">
            <button class="btn btn--outline" onclick="goStep(1)">← Назад</button>
            <button class="btn btn--primary" id="step2next" <?= $files_ok ? '' : 'disabled' ?> onclick="goStep(3)">Продолжить →</button>
        </div>
    </div>

    <div class="panel" id="panel3">
        <div class="card">
            <h2>Сайт</h2>
            <form id="installForm">
                <div class="row">
                    <div class="form-group">
                        <label for="site_name">Название сайта</label>
                        <input type="text" id="site_name" name="site_name" value="eFix" placeholder="eFix">
                    </div>
                    <div class="form-group">
                        <label for="phone">Телефон</label>
                        <input type="text" id="phone" name="phone" value="+7 (383) 000-00-00" placeholder="+7 (383) 000-00-00">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="info@efix.ru" placeholder="info@efix.ru">
                    </div>
                    <div class="form-group">
                        <label for="address">Адрес (короткий)</label>
                        <input type="text" id="address" name="address" value="Новосибирск, выезд по городу">
                    </div>
                </div>

                <h2 style="margin-top:20px">База данных</h2>
                <div class="form-group">
                    <div class="radio-cards" id="dbTypeCards">
                        <label class="radio-card radio-card--selected" data-value="sqlite" onclick="selectDbType('sqlite')">
                            <input type="radio" name="db_type" value="sqlite" checked>
                            <div>SQLite</div>
                            <div class="radio-card__sub">Встроенная, без настроек</div>
                        </label>
                        <label class="radio-card" data-value="mysql" onclick="selectDbType('mysql')">
                            <input type="radio" name="db_type" value="mysql">
                            <div>MySQL / MariaDB</div>
                            <div class="radio-card__sub">Удалённый сервер</div>
                        </label>
                    </div>
                </div>

                <div class="db-section" id="dbSection">
                    <div class="row">
                        <div class="form-group" style="flex:2">
                            <label for="db_host">Хост</label>
                            <input type="text" id="db_host" name="db_host" value="localhost" placeholder="localhost">
                        </div>
                        <div class="form-group" style="flex:1">
                            <label for="db_port">Порт</label>
                            <input type="text" id="db_port" name="db_port" value="3306" placeholder="3306">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="db_name">Имя базы данных</label>
                        <input type="text" id="db_name" name="db_name" placeholder="efix_db">
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label for="db_user">Пользователь</label>
                            <input type="text" id="db_user" name="db_user" placeholder="root">
                        </div>
                        <div class="form-group">
                            <label for="db_pass">Пароль</label>
                            <div class="password-wrap">
                                <input type="password" id="db_pass" name="db_pass" placeholder="пароль">
                                <button type="button" class="toggle-pass" data-toggle="db_pass" aria-label="Показать" tabindex="-1">👁</button>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-info" style="margin-top:8px;font-size:12px;padding:10px 14px">
                        База данных должна существовать. Установщик создаст таблицы.
                    </div>
                </div>

                <h2 style="margin-top:20px">Администратор</h2>
                <div class="form-group">
                    <label for="admin_user">Имя пользователя *</label>
                    <input type="text" id="admin_user" name="admin_user" required placeholder="admin">
                    <div class="field-error">Обязательное поле</div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <label for="admin_pass">Пароль *</label>
                        <div class="password-wrap">
                            <input type="password" id="admin_pass" name="admin_pass" required minlength="4" placeholder="минимум 4 символа">
                            <button type="button" class="toggle-pass" data-toggle="admin_pass" aria-label="Показать" tabindex="-1">👁</button>
                        </div>
                        <div class="field-error">Минимум 4 символа</div>
                    </div>
                    <div class="form-group">
                        <label for="admin_pass2">Повторите пароль *</label>
                        <div class="password-wrap">
                            <input type="password" id="admin_pass2" name="admin_pass2" required minlength="4" placeholder="подтверждение">
                            <button type="button" class="toggle-pass" data-toggle="admin_pass2" aria-label="Показать" tabindex="-1">👁</button>
                        </div>
                        <div class="field-error">Пароли не совпадают</div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="secret">Секретный ключ</label>
                    <input type="text" id="secret" name="secret" placeholder="оставьте пустым — автогенерация">
                    <div class="hint">Для шифрования сессий. Пусто = автогенерация.</div>
                </div>
            </form>
        </div>
        <div class="actions">
            <button class="btn btn--outline" onclick="goStep(2)">← Назад</button>
            <button class="btn btn--primary" onclick="startInstall()">Установить →</button>
        </div>
    </div>

    <div class="panel" id="panel4">
        <div class="card" id="installProgress">
            <h2>Установка...</h2>
            <div class="progress"><div class="progress__bar" id="installBar"></div></div>
            <div class="progress__text" id="installStatus">Подготовка...</div>
        </div>
        <div class="card" id="installResult" style="display:none;text-align:center">
            <h1 style="font-size:22px;margin-bottom:8px" id="resultTitle">Готово!</h1>
            <p id="resultText" style="color:var(--text-muted);margin-bottom:8px"></p>
            <ul class="success-links">
                <li><a href="../../index.php">Открыть сайт</a></li>
                <li><a href="../../admin/login">Войти в админ-панель</a></li>
            </ul>
            <div class="alert alert-warning" style="font-size:12px">
                <strong>ВАЖНО:</strong> Удалите файл <code>install.php</code> с сервера!
            </div>
        </div>
        <div class="actions" id="step4actions">
            <button class="btn btn--outline" onclick="goStep(3)">← Назад</button>
            <span></span>
        </div>
    </div>

    <div class="footer-note">
        eFix — выездной сервисный центр &bull;
        <a href="https://github.com/dottenv/eFix" target="_blank" style="color:var(--accent)">GitHub</a>
    </div>
</div>

<script>
var currentStep = 1;
function goStep(n) {
    if (n < 1 || n > 4) return;
    document.querySelectorAll('.panel').forEach((p, i) => p.classList.toggle('panel--active', i === n-1));
    document.querySelectorAll('.step').forEach((s, i) => {
        s.classList.toggle('step--done', i+1 < n);
        s.classList.toggle('step--active', i+1 === n);
    });
    for (let i = 0; i < n-1; i++) {
        document.querySelectorAll('.step')[i]?.classList.add('step--done');
    }
    currentStep = n;
    window.scrollTo({top: 0, behavior: 'smooth'});
}

function startDownload() {
    var btn = document.getElementById('downloadBtn');
    var icon = document.getElementById('dlIcon');
    var text = document.getElementById('dlText');
    var prog = document.getElementById('dlProgressWrap');
    var bar = document.getElementById('dlProgressBar');
    var status = document.getElementById('dlStatus');
    var nextBtn = document.getElementById('step2next');

    btn.disabled = true;
    text.textContent = 'Скачиваю...';
    icon.style.display = 'inline-block';
    prog.style.display = 'block';

    fetch('?action=download')
        .then(function(r) { return r.json(); })
        .then(function(data) {
            bar.style.width = (data.p || 0) + '%';
            status.textContent = data.m || '';
            if (data.done) {
                icon.style.display = 'none';
                text.textContent = 'Готово!';
                btn.className = 'btn btn--secondary btn--full';
                nextBtn.disabled = false;
                setTimeout(function() { goStep(3); }, 800);
            } else if (data.error) {
                icon.style.display = 'none';
                text.textContent = 'Ошибка';
                status.innerHTML = '<span style="color:var(--danger)">' + data.m + '</span>';
                btn.disabled = false;
                btn.className = 'btn btn--primary btn--full';
                text.textContent = 'Повторить';
            }
        })
        .catch(function(err) {
            icon.style.display = 'none';
            text.textContent = 'Ошибка соединения';
            status.innerHTML = '<span style="color:var(--danger)">' + err + '</span>';
            btn.disabled = false;
            btn.className = 'btn btn--primary btn--full';
            text.textContent = 'Повторить';
        });
}

function selectDbType(type) {
    document.querySelectorAll('.radio-card').forEach(function(c) {
        var inp = c.querySelector('input');
        if (inp && inp.value === type) {
            inp.checked = true;
            c.classList.add('radio-card--selected');
        } else {
            if (inp) inp.checked = false;
            c.classList.remove('radio-card--selected');
        }
    });
    document.getElementById('dbSection').style.display = type === 'mysql' ? 'block' : 'none';
}

document.querySelectorAll('.toggle-pass').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var inp = document.getElementById(this.getAttribute('data-toggle'));
        if (inp) inp.type = inp.type === 'password' ? 'text' : 'password';
    });
});

function startInstall() {
    var form = document.getElementById('installForm');
    var data = new FormData(form);

    var user = data.get('admin_user');
    var pass = data.get('admin_pass');
    var pass2 = data.get('admin_pass2');
    if (!user || !pass) { alert('Заполните имя и пароль администратора'); return; }
    if (pass !== pass2) { alert('Пароли не совпадают'); return; }
    if (pass.length < 4) { alert('Пароль минимум 4 символа'); return; }

    goStep(4);
    var bar = document.getElementById('installBar');
    var status = document.getElementById('installStatus');
    var progress = document.getElementById('installProgress');
    var result = document.getElementById('installResult');
    var actions = document.getElementById('step4actions');

    bar.style.width = '30%';
    status.textContent = 'Устанавливаю...';
    result.style.display = 'none';

    fetch('?action=install', { method: 'POST', body: data })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            bar.style.width = '100%';
            if (res.ok) {
                status.textContent = 'Готово!';
                progress.style.display = 'none';
                result.style.display = 'block';
                document.getElementById('resultTitle').textContent = 'Установка завершена!';
                document.getElementById('resultText').innerHTML = 'Администратор <strong>' + res.admin_user + '</strong> создан.';
                actions.innerHTML = '<span></span><span></span>';
            } else {
                status.innerHTML = '<span style="color:var(--danger)">' + (res.error || 'Ошибка') + '</span>';
                actions.innerHTML = '<button class="btn btn--outline" onclick="goStep(3)">← Вернуться</button><span></span>';
            }
        })
        .catch(function(err) {
            bar.style.width = '100%';
            status.innerHTML = '<span style="color:var(--danger)">' + err + '</span>';
            actions.innerHTML = '<button class="btn btn--outline" onclick="goStep(3)">← Вернуться</button><span></span>';
        });
}
</script>
</body>
</html>
