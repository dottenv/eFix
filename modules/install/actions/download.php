<?php
header('Content-Type: application/json; charset=utf-8');
$max = 6; $step = 0;
try {
    $step++; echo json_encode(['p' => round($step/$max*100), 'm' => 'Скачиваю архив с GitHub...']); ob_flush(); flush();

    $zip_data = false;
    if (ini_get('allow_url_fopen')) $zip_data = @file_get_contents(GITHUB_ZIP);
    if ($zip_data === false && function_exists('curl_init')) {
        $ch = curl_init(GITHUB_ZIP);
        curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER=>true, CURLOPT_FOLLOWLOCATION=>true, CURLOPT_TIMEOUT=>120, CURLOPT_SSL_VERIFYPEER=>false]);
        $zip_data = curl_exec($ch); $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($http_code !== 200) $zip_data = false;
    }
    if ($zip_data === false) throw new Exception('Не удалось скачать архив');

    $step++; echo json_encode(['p' => round($step/$max*100), 'm' => 'Распаковываю...']); ob_flush(); flush();

    $tmp_zip = __DIR__ . '/../../_efix_tmp.zip';
    $tmp_dir = __DIR__ . '/../../_efix_tmp';
    file_put_contents($tmp_zip, $zip_data);
    @rrmdir($tmp_dir); @mkdir($tmp_dir, 0755, true);
    $zip = new ZipArchive();
    if ($zip->open($tmp_zip) !== true) throw new Exception('Не удалось открыть ZIP');
    $zip->extractTo($tmp_dir); $zip->close(); @unlink($tmp_zip);

    $step++; echo json_encode(['p' => round($step/$max*100), 'm' => 'Копирую файлы...']); ob_flush(); flush();

    $entries = scandir($tmp_dir);
    $extracted = null;
    foreach ($entries as $e) {
        if ($e !== '.' && $e !== '..' && is_dir($tmp_dir . '/' . $e)) { $extracted = $tmp_dir . '/' . $e; break; }
    }
    if (!$extracted) throw new Exception('Не найдена папка с файлами');

    $copied = 0;
    $it = new RecursiveDirectoryIterator($extracted, RecursiveDirectoryIterator::SKIP_DOTS);
    $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::LEAVES_ONLY);
    foreach ($files as $f) {
        $rel = substr($f->getPathname(), strlen($extracted) + 1);
        if (str_starts_with($rel, '.git')) continue;
        if (in_array($rel, ['install.php','update.php','deploy/docker-compose.yml','deploy/setup.sh','deploy/nginx.conf','deploy/nginx-ssl.conf'])) continue;
        $target = __DIR__ . '/../../' . $rel;
        @mkdir(dirname($target), 0755, true);
        copy($f->getPathname(), $target); $copied++;
    }
    @rrmdir($tmp_dir);

    $step++; echo json_encode(['p' => round($step/$max*100), 'm' => "Скопировано $copied файлов"]); ob_flush(); flush();
    $step++; echo json_encode(['p' => round($step/$max*100), 'm' => 'Проверяю...']); ob_flush(); flush();
    $still = missing_files();
    if (empty($still)) {
        $step++; echo json_encode(['p' => 100, 'm' => 'Готово!', 'done' => true]);
    } else {
        throw new Exception('Некоторые файлы не скопировались: ' . implode(', ', $still));
    }
} catch (Exception $e) {
    echo json_encode(['p' => 100, 'm' => 'Ошибка: ' . $e->getMessage(), 'error' => true]);
}
