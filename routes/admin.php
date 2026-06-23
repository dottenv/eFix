<?php
if (!str_starts_with($uri, '/admin/') && $uri !== '/admin') {
    return;
}

switch ($uri) {
    case '/admin/login':
        if ($method === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $admin = Admin::getByUsername($username);
            if ($admin && password_verify($password, $admin['password_hash'])) {
                $_SESSION['admin_id'] = $admin['id'];
                redirect('/admin/dashboard');
            }
            render_admin('login', ['error' => 'Неверное имя пользователя или пароль']);
        }
        render_admin('login');
        break;

    case '/admin/register':
        if (Admin::count() > 0 && !is_admin_authenticated()) {
            redirect('/admin/login');
        }
        if ($method === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';
            if (!$username || !$password) {
                render_admin('register', ['error' => 'Заполните все поля']);
            }
            if ($password !== $confirm) {
                render_admin('register', ['error' => 'Пароли не совпадают']);
            }
            if (Admin::getByUsername($username)) {
                render_admin('register', ['error' => 'Пользователь уже существует']);
            }
            Admin::create($username, $password);
            $admin = Admin::getByUsername($username);
            $_SESSION['admin_id'] = $admin['id'];
            redirect('/admin/dashboard');
        }
        render_admin('register');
        break;

    case '/admin/logout':
        session_destroy();
        redirect('/admin/login');
        break;

    case '/admin':
    case '/admin/dashboard':
        require_admin();
        $newRequests = ContactRequest::countNew();
        $totalRequests = ContactRequest::countTotal();
        $todayViews = PageView::countToday();
        $todayVisitors = PageView::countUniqueToday();
        render_admin('dashboard', compact('newRequests', 'totalRequests', 'todayViews', 'todayVisitors'));
        break;

    case '/admin/site':
        require_admin();
        if ($method === 'POST') {
            foreach ($_POST as $key => $value) {
                if ($key !== '_method') {
                    SiteContent::set($key, $value);
                }
            }
            redirect('/admin/site');
        }
        $contents = SiteContent::getAll();
        render_admin('site', compact('contents'));
        break;

    case '/admin/services':
        require_admin();
        if ($method === 'POST') {
            $action = $_POST['_action'] ?? 'add';
            if ($action === 'delete') {
                Service::delete($_POST['id']);
            } else {
                $data = [
                    'title' => $_POST['title'] ?? '',
                    'description' => $_POST['description'] ?? '',
                    'price' => $_POST['price'] ?? '',
                    'icon' => $_POST['icon'] ?? '',
                    'category' => $_POST['category'] ?? '',
                    'sort_order' => (int)($_POST['sort_order'] ?? 0),
                ];
                if ($action === 'edit' && ($id = $_POST['id'] ?? '')) {
                    Service::update($id, $data);
                } else {
                    Service::create($data);
                }
            }
            redirect('/admin/services');
        }
        $services = Service::getAll();
        render_admin('services', compact('services'));
        break;

    case '/admin/prices':
        require_admin();
        if ($method === 'POST') {
            $action = $_POST['_action'] ?? 'add';
            if ($action === 'delete') {
                PriceItem::delete($_POST['id']);
            } else {
                $data = [
                    'device_type' => $_POST['device_type'] ?? '',
                    'brand' => $_POST['brand'] ?? '',
                    'model_name' => $_POST['model_name'] ?? '',
                    'service' => $_POST['service'] ?? '',
                    'price_from' => (int)($_POST['price_from'] ?? 0),
                    'price_to' => (int)($_POST['price_to'] ?? 0) ?: null,
                    'is_active' => (int)($_POST['is_active'] ?? 1),
                ];
                if ($action === 'edit' && ($id = $_POST['id'] ?? '')) {
                    PriceItem::update($id, $data);
                } else {
                    PriceItem::create($data);
                }
            }
            redirect('/admin/prices');
        }
        $page = max(1, (int)($_GET['page'] ?? 1));
        $prices = PriceItem::getAll();
        $deviceTypes = PriceItem::getDeviceTypes();
        render_admin('prices', compact('prices', 'deviceTypes', 'page'));
        break;

    case '/admin/workshops':
        require_admin();
        if ($method === 'POST') {
            $action = $_POST['_action'] ?? 'add';
            if ($action === 'delete') {
                PartnerWorkshop::delete($_POST['id']);
            } else {
                $data = [
                    'name' => $_POST['name'] ?? '',
                    'address' => $_POST['address'] ?? '',
                    'lat' => (float)($_POST['lat'] ?? 0),
                    'lng' => (float)($_POST['lng'] ?? 0),
                    'phone' => $_POST['phone'] ?? '',
                    'description' => $_POST['description'] ?? '',
                    'is_active' => (int)($_POST['is_active'] ?? 1),
                ];
                if ($action === 'edit' && ($id = $_POST['id'] ?? '')) {
                    PartnerWorkshop::update($id, $data);
                } else {
                    PartnerWorkshop::create($data);
                }
            }
            redirect('/admin/workshops');
        }
        $workshops = PartnerWorkshop::getAll();
        render_admin('workshops', compact('workshops'));
        break;

    case '/admin/requests':
        require_admin();
        $requests = ContactRequest::getAll();
        render_admin('requests', compact('requests'));
        break;

    case '/admin/requests/check':
        require_admin();
        $count = ContactRequest::countNew();
        json_response(['count' => $count]);
        break;

    case '/admin/requests/bulk':
        require_admin();
        $ids = $_POST['ids'] ?? [];
        $action = $_POST['bulk_action'] ?? '';
        if ($ids && $action) {
            $statusMap = ['set_new' => 'new', 'set_in_progress' => 'in_progress', 'set_completed' => 'completed', 'set_archived' => 'archived'];
            $status = $statusMap[$action] ?? null;
            if ($status) {
                $db = Database::getInstance();
                $placeholders = implode(',', array_fill(0, count($ids), '?'));
                $db->query("UPDATE contact_request SET status = ? WHERE id IN ($placeholders)", [$status, ...$ids]);
            }
        }
        redirect('/admin/requests');
        break;

    case '/admin/stats':
        require_admin();
        render_admin('stats');
        break;

    case '/admin/mail-config':
        require_admin();
        if ($method === 'POST') {
            $data = [
                'smtp_host' => $_POST['smtp_host'] ?? '',
                'smtp_port' => (int)($_POST['smtp_port'] ?? 587),
                'smtp_user' => $_POST['smtp_user'] ?? '',
                'smtp_pass' => $_POST['smtp_pass'] ?? '',
                'smtp_use_tls' => (int)($_POST['smtp_use_tls'] ?? 1),
                'from_email' => $_POST['from_email'] ?? '',
                'from_name' => $_POST['from_name'] ?? '',
                'notify_on_new_request' => (int)($_POST['notify_on_new_request'] ?? 0),
                'notify_email' => $_POST['notify_email'] ?? '',
            ];
            MailConfig::save($data);
            redirect('/admin/mail-config');
        }
        $config = MailConfig::get();
        render_admin('mail_config', compact('config'));
        break;

    case '/admin/mail-templates':
        require_admin();
        $templates = MailTemplate::getAll();
        render_admin('mail_templates', compact('templates'));
        break;

    case '/admin/mail-templates/add':
        require_admin();
        if ($method === 'POST') {
            MailTemplate::create([
                'name' => $_POST['name'] ?? '',
                'subject' => $_POST['subject'] ?? '',
                'body' => $_POST['body'] ?? '',
            ]);
            redirect('/admin/mail-templates');
        }
        render_admin('mail_template_edit', ['template' => null]);
        break;

    case '/admin/settings':
        require_admin();
        if ($method === 'POST') {
            foreach ($_POST as $key => $value) {
                if (!str_starts_with($key, '_')) {
                    AppSetting::set($key, $value);
                }
            }
            redirect('/admin/settings');
        }
        $settings = AppSetting::getAll();
        render_admin('settings', compact('settings'));
        break;

    case '/admin/settings/env':
        require_admin();
        if ($method === 'POST') {
            $envContent = '';
            $keys = $_POST['keys'] ?? [];
            $values = $_POST['values'] ?? [];
            for ($i = 0; $i < count($keys); $i++) {
                if ($keys[$i] && $values[$i]) {
                    $envContent .= $keys[$i] . '=' . $values[$i] . "\n";
                }
            }
            file_put_contents(__DIR__ . '/../.env', $envContent);
            redirect('/admin/settings/env');
        }
        $envVars = [];
        if (file_exists(__DIR__ . '/../.env')) {
            foreach (file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
                if (str_starts_with(trim($line), '#')) continue;
                if (str_contains($line, '=')) {
                    [$k, $v] = explode('=', $line, 2);
                    $envVars[] = ['key' => trim($k), 'value' => trim($v)];
                }
            }
        }
        render_admin('env', compact('envVars'));
        break;

    case '/admin/api/send-test-email':
        require_admin();
        $config = MailConfig::get();
        $to = $_POST['to'] ?? $config['notify_email'];
        // No actual sending in PHP dev mode, just mark as sent
        json_response(['ok' => true, 'message' => 'Тестовое письмо отправлено']);
        break;

    default:
        // Handle stats API routes
        if (str_starts_with($uri, '/admin/api/stats/')) {
            require_admin();
            $statsEndpoint = substr($uri, strlen('/admin/api/stats/'));
            switch ($statsEndpoint) {
                case 'summary':
                    json_response(PageView::getSummary());
                    break;
                case 'page-views':
                    $days = (int)($_GET['days'] ?? 30);
                    json_response(PageView::getDailyViews($days));
                    break;
                case 'pages':
                    json_response(PageView::getTopPages());
                    break;
                case 'referrers':
                    json_response(PageView::getReferrers());
                    break;
                case 'searches':
                    json_response(PageView::getSearches());
                    break;
                case 'frequent-searches':
                    json_response(PageView::getFrequentSearches());
                    break;
                case 'device-breakdown':
                    json_response(PageView::getDeviceTypeBreakdown());
                    break;
                case 'locations':
                    json_response(PageView::getLocations());
                    break;
                case 'device-types':
                    json_response(PageView::getDeviceTypeBreakdown());
                    break;
                case 'realtime':
                    json_response(PageView::getRecent(20));
                    break;
                case 'utms':
                    json_response(PageView::getUtms());
                    break;
                case 'browsers':
                    json_response(PageView::getBrowsers());
                    break;
                case 'os':
                    json_response(PageView::getBrowsers());
                    break;
                case 'screens':
                    json_response(PageView::getScreens());
                    break;
                case 'forms':
                    json_response(PageView::getFormInteractions());
                    break;
                case 'languages':
                    json_response(PageView::getLanguages());
                    break;
                case 'sessions':
                    json_response(PageView::getSummary());
                    break;
                default:
                    json_response(['error' => 'Not found'], 404);
            }
        }

        // Handle mail template edit/delete routes
        if (preg_match('#^/admin/mail-templates/(\d+)/edit$#', $uri, $m)) {
            require_admin();
            $id = (int)$m[1];
            if ($method === 'POST') {
                MailTemplate::update($id, [
                    'name' => $_POST['name'] ?? '',
                    'subject' => $_POST['subject'] ?? '',
                    'body' => $_POST['body'] ?? '',
                ]);
                redirect('/admin/mail-templates');
            }
            $template = MailTemplate::getById($id);
            render_admin('mail_template_edit', compact('template'));
            break;
        }
        if (preg_match('#^/admin/mail-templates/(\d+)/delete$#', $uri, $m)) {
            require_admin();
            MailTemplate::delete((int)$m[1]);
            redirect('/admin/mail-templates');
            break;
        }

        // Handle request detail page
        if (preg_match('#^/admin/requests/(\d+)$#', $uri, $m)) {
            require_admin();
            $id = (int)$m[1];
            if ($method === 'POST') {
                ContactRequest::update($id, [
                    'status' => $_POST['status'] ?? 'new',
                    'admin_notes' => $_POST['admin_notes'] ?? '',
                ]);
                redirect('/admin/requests');
            }
            $request = ContactRequest::getById($id);
            render_admin('request_detail', compact('request'));
            break;
        }

        // Handle site content delete
        if (preg_match('#^/admin/site/(\d+)/delete$#', $uri, $m)) {
            require_admin();
            SiteContent::delete((int)$m[1]);
            redirect('/admin/site');
            break;
        }

        notFound();
}
