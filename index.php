<?php
session_start();

if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
    header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit;
}

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/hooks.php';
require_once __DIR__ . '/render.php';
require_once __DIR__ . '/app/Router.php';
require_once __DIR__ . '/models/Admin.php';
require_once __DIR__ . '/models/SiteContent.php';
require_once __DIR__ . '/models/Service.php';
require_once __DIR__ . '/models/PriceItem.php';
require_once __DIR__ . '/models/PartnerWorkshop.php';
require_once __DIR__ . '/models/ContactRequest.php';
require_once __DIR__ . '/models/PageView.php';
require_once __DIR__ . '/models/SearchQuery.php';
require_once __DIR__ . '/models/IpLocation.php';
require_once __DIR__ . '/models/FormInteraction.php';
require_once __DIR__ . '/models/MailConfig.php';
require_once __DIR__ . '/models/MailTemplate.php';
require_once __DIR__ . '/models/AppSetting.php';

$db = Database::getInstance();
$db->initSchema();

$router = new Router();
$router->dispatch();
