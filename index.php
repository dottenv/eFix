<?php
session_start();

if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
    header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit;
}

require_once __DIR__ . '/app/Config.php';
require_once __DIR__ . '/app/Database.php';
require_once __DIR__ . '/app/Helpers.php';
require_once __DIR__ . '/app/Hooks.php';
require_once __DIR__ . '/app/Render.php';
require_once __DIR__ . '/app/Router.php';
require_once __DIR__ . '/app/Models/Admin.php';
require_once __DIR__ . '/app/Models/SiteContent.php';
require_once __DIR__ . '/app/Models/Service.php';
require_once __DIR__ . '/app/Models/PriceItem.php';
require_once __DIR__ . '/app/Models/PartnerWorkshop.php';
require_once __DIR__ . '/app/Models/ContactRequest.php';
require_once __DIR__ . '/app/Models/PageView.php';
require_once __DIR__ . '/app/Models/SearchQuery.php';
require_once __DIR__ . '/app/Models/IpLocation.php';
require_once __DIR__ . '/app/Models/FormInteraction.php';
require_once __DIR__ . '/app/Models/MailConfig.php';
require_once __DIR__ . '/app/Models/MailTemplate.php';
require_once __DIR__ . '/app/Models/AppSetting.php';

$db = Database::getInstance();
$db->initSchema();

$router = new Router();
$router->dispatch();
