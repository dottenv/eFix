<?php
switch ($uri) {
    case '/':
        trackPageView('/');
        render('index', ['active' => 'home']);
        break;

    case '/services':
        $categories = Service::getCategories();
        $services_by_cat = [];
        foreach ($categories as $cat) {
            $services_by_cat[$cat['category']] = Service::getByCategory($cat['category']);
        }
        render('services', ['active' => 'services', 'categories' => $categories, 'services_by_cat' => $services_by_cat]);
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

    default:
        if (str_starts_with($uri, '/admin/') || str_starts_with($uri, '/api/')) {
            break;
        }
        if ($uri !== '/' && !str_starts_with($uri, '/static/')) {
            notFound();
        }
}
