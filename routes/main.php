<?php
switch ($uri) {
    case '/':
        trackPageView('/');
        render('index', ['active' => 'home']);
        break;

    case '/services':
        $categories = Service::getCategories();
        $services_by_cat = [];
        $category_list = [];
        $labels = ['phones' => 'Телефоны', 'tablets' => 'Планшеты', 'laptops' => 'Ноутбуки', 'pc' => 'ПК и моноблоки'];
        $icons = [
            'phones' => '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>',
            'tablets' => '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="4" y="2" width="16" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>',
            'laptops' => '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="2" y1="20" x2="22" y2="20"/></svg>',
            'pc' => '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="4" y="4" width="16" height="12" rx="2"/><line x1="9" y1="20" x2="15" y2="20"/><line x1="12" y1="16" x2="12" y2="20"/></svg>',
        ];
        foreach ($categories as $cat) {
            $slug = $cat['category'];
            $services = Service::getByCategory($slug);
            $services_by_cat[$slug] = array_map(fn($s) => $s + ['name' => $s['title']], $services);
            $desc = !empty($services) ? $services[0]['description'] : '';
            $category_list[] = [
                'id' => $slug,
                'title' => $labels[$slug] ?? $slug,
                'icon' => $icons[$slug] ?? '',
                'desc' => $desc,
            ];
        }
        render('services', ['active' => 'services', 'categories' => $category_list, 'services_by_cat' => $services_by_cat]);
        break;

    case '/prices':
        $deviceTypes = PriceItem::getDeviceTypes();
        $brands = PriceItem::getBrands();
        $device_type = $_GET['device_type'] ?? '';
        $brand = $_GET['brand'] ?? '';
        $model = $_GET['model'] ?? '';
        $query = $_GET['q'] ?? '';
        $page = max(1, (int)($_GET['page'] ?? 1));
        $result = PriceItem::search($device_type, $brand, $model, $query, $page, 20);
        render('prices', [
            'active' => 'prices', 'deviceTypes' => $deviceTypes, 'brands' => $brands,
            'device_type' => $device_type, 'brand' => $brand, 'model' => $model, 'query' => $query,
            'items' => $result['items'], 'total' => $result['total'],
            'page' => $result['page'], 'perPage' => $result['perPage'],
        ]);
        break;

    case '/about':
        render('about', ['active' => 'about']);
        break;

    case '/contacts':
        $workshops = PartnerWorkshop::getActive();
        render('contacts', ['active' => 'contacts', 'workshops' => $workshops]);
        break;

    case '/track':
        $path = $_GET['path'] ?? '/';
        trackPageView($path);
        json_response(['ok' => true]);
        break;

    case '/index.php':
        // Direct access to index.php — show homepage
        trackPageView('/');
        render('index', ['active' => 'home']);
        break;

    default:
        if (str_starts_with($uri, '/admin/') || str_starts_with($uri, '/api/')) {
            break;
        }
        if ($uri !== '/' && !str_starts_with($uri, '/static/')) {
            notFound();
        }
}
