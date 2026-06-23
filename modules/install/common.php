<?php
const GITHUB_ZIP = 'https://github.com/dottenv/eFix/archive/main.zip';

const PROJECT_FILES = [
    'install.php', 'update.php', 'index.php',
    'app/Config.php', 'app/Database.php', 'app/Helpers.php',
    'app/Hooks.php', 'app/Render.php', 'app/Router.php',
    'modules/install/common.php', 'modules/install/init.php',
    'modules/install/views/layout.php',
    'modules/install/actions/download.php', 'modules/install/actions/install.php',
    'app/Models/Admin.php', 'app/Models/SiteContent.php', 'app/Models/Service.php',
    'app/Models/PriceItem.php', 'app/Models/PartnerWorkshop.php', 'app/Models/ContactRequest.php',
    'app/Models/PageView.php', 'app/Models/SearchQuery.php', 'app/Models/IpLocation.php',
    'app/Models/FormInteraction.php', 'app/Models/MailConfig.php', 'app/Models/MailTemplate.php',
    'app/Models/AppSetting.php',
    'routes/main.php', 'routes/api.php', 'routes/admin.php',
    'templates/base.php', 'templates/404.php',
    'templates/index.php', 'templates/services.php', 'templates/prices.php',
    'templates/about.php', 'templates/contacts.php',
    'templates/_prices_table.php', 'templates/_modal_callback.php', 'templates/_callback_form.php', 'templates/_callback_ok.php',
    'templates/admin/base.php', 'templates/admin/login.php', 'templates/admin/dashboard.php',
    'templates/admin/site.php', 'templates/admin/services.php', 'templates/admin/prices.php',
    'templates/admin/workshops.php', 'templates/admin/requests.php',
    'templates/admin/_request_modal.php', 'templates/admin/_requests_table.php',
    'templates/admin/_workshops_container.php', 'templates/admin/_workshops_rows.php',
    'templates/admin/stats.php', 'templates/admin/mail_config.php',
    'templates/admin/mail_templates.php', 'templates/admin/mail_template_edit.php',
    'templates/admin/register.php', 'templates/admin/settings.php', 'templates/admin/env.php',
    'static/css/style.css', 'static/js/main.js',
];

function generate_secret() { return 'eFix-' . bin2hex(random_bytes(16)); }

function missing_files() {
    $missing = [];
    foreach (PROJECT_FILES as $f) {
        if (!file_exists(__DIR__ . '/../../' . $f)) $missing[] = $f;
    }
    return $missing;
}

function rrmdir($dir) {
    if (!is_dir($dir)) return;
    $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
    $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
    foreach ($files as $f) $f->isDir() ? @rmdir($f->getRealPath()) : @unlink($f->getRealPath());
    @rmdir($dir);
}

function check_mod_rewrite() {
    if (function_exists('apache_get_modules')) return in_array('mod_rewrite', apache_get_modules());
    if (function_exists('phpinfo')) {
        ob_start(); phpinfo(INFO_MODULES); $info = ob_get_clean();
        return strpos($info, 'mod_rewrite') !== false;
    }
    return null;
}
