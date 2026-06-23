<?php
// ============================================================
// eFix — Standalone Installer Bootstrap
// Upload ONLY this file, open in browser → downloads project
// ============================================================

const GITHUB_ZIP = 'https://github.com/dottenv/eFix/archive/main.zip';

// If project already exists, go to the module installer
if (file_exists(__DIR__ . '/modules/install/init.php')) {
    header('Location: /install/');
    exit;
}

// ----- Download & extract -----
$error = '';
$done = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $zip_data = false;
    if (ini_get('allow_url_fopen')) $zip_data = @file_get_contents(GITHUB_ZIP);
    if ($zip_data === false && function_exists('curl_init')) {
        $ch = curl_init(GITHUB_ZIP);
        curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER=>true, CURLOPT_FOLLOWLOCATION=>true, CURLOPT_TIMEOUT=>120, CURLOPT_SSL_VERIFYPEER=>false]);
        $zip_data = curl_exec($ch);
        curl_close($ch);
    }
    if ($zip_data === false) {
        $error = 'Не удалось скачать архив с GitHub. Проверьте allow_url_fopen или cURL.';
    } else {
        $tmp_zip = __DIR__ . '/_efix_tmp.zip';
        $tmp_dir = __DIR__ . '/_efix_tmp';
        file_put_contents($tmp_zip, $zip_data);

        @mkdir($tmp_dir, 0755, true);
        $zip = new ZipArchive();
        if ($zip->open($tmp_zip) === true) {
            $zip->extractTo($tmp_dir);
            $zip->close();
            @unlink($tmp_zip);

            $entries = scandir($tmp_dir);
            $extracted = null;
            foreach ($entries as $e) {
                if ($e !== '.' && $e !== '..' && is_dir($tmp_dir . '/' . $e)) {
                    $extracted = $tmp_dir . '/' . $e; break;
                }
            }

            if ($extracted) {
                $copied = 0;
                $it = new RecursiveDirectoryIterator($extracted, RecursiveDirectoryIterator::SKIP_DOTS);
                $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::LEAVES_ONLY);
                foreach ($files as $f) {
                    $rel = substr($f->getPathname(), strlen($extracted) + 1);
                    if (str_starts_with($rel, '.git')) continue;
                    if (in_array($rel, ['deploy/docker-compose.yml','deploy/setup.sh','deploy/nginx.conf','deploy/nginx-ssl.conf'])) continue;
                    $target = __DIR__ . '/' . $rel;
                    @mkdir(dirname($target), 0755, true);
                    copy($f->getPathname(), $target); $copied++;
                }
                $done = true;
            }
            @rrmdir($tmp_dir);
        } else {
            $error = 'Не удалось открыть ZIP-архив';
        }
    }
}

function rrmdir($dir) {
    if (!is_dir($dir)) return;
    $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
    $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
    foreach ($files as $f) $f->isDir() ? @rmdir($f->getRealPath()) : @unlink($f->getRealPath());
    @rmdir($dir);
}
?><!DOCTYPE html>
<html lang="ru">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Установка eFix</title>
<style>body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;background:#F5F7FA;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;padding:20px}
.card{background:#fff;border-radius:12px;box-shadow:0 4px 24px rgba(0,0,0,.08);padding:40px;max-width:440px;width:100%;text-align:center}
h1{font-size:24px;color:#0B2447;margin:0 0 8px}h1 span{color:#FF6B35}
p{color:#6B7280;font-size:14px;margin:8px 0 20px}
.btn{display:inline-block;padding:12px 28px;font-size:15px;font-weight:700;border:none;border-radius:8px;cursor:pointer;background:#FF6B35;color:#fff;text-decoration:none}
.btn:hover{opacity:.9}.btn:disabled{opacity:.5}
.spinner{display:inline-block;width:18px;height:18px;border:3px solid #E5E7EB;border-top-color:#FF6B35;border-radius:50%;animation:spin .6s linear infinite;margin-right:8px;vertical-align:middle}
@keyframes spin{to{transform:rotate(360deg)}}
.alert{padding:12px;border-radius:8px;font-size:13px;margin-top:16px}
.alert-error{background:#FEF2F2;color:#EF4444;border:1px solid #FECACA}
.alert-success{background:#F0FDF4;color:#166534;border:1px solid #BBF7D0}
.footer{font-size:12px;color:#6B7280;margin-top:20px}</style>
</head>
<body>
<div class="card">
    <h1>e<span>Fix</span></h1>
    <?php if ($done): ?>
        <div class="alert alert-success">Проект успешно загружен!</div>
        <p>Перехожу к установке...</p>
        <meta http-equiv="refresh" content="1;url=/install/">
        <script>setTimeout(function(){window.location.href='/install/';},1000)</script>
    <?php elseif ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <p>Загрузите файлы через FTP или попробуйте снова.</p>
        <form method="post"><button class="btn" type="submit">Повторить</button></form>
    <?php else: ?>
        <p>Нажмите кнопку, чтобы скачать проект с GitHub и начать установку.</p>
        <form method="post" onsubmit="this.querySelector('.btn').disabled=true;this.querySelector('.btn').innerHTML='<span class=spinner></span>Скачиваю...'">
            <button class="btn" type="submit">Скачать проект</button>
        </form>
    <?php endif ?>
    <div class="footer">eFix — выездной сервисный центр</div>
</div>
</body>
</html>
