<?php
if (str_starts_with($uri, '/api/')) {
    switch ($uri) {
        case '/api/callback':
            if ($method !== 'POST') {
                json_response(['error' => 'Method not allowed'], 405);
            }
            $name = $_POST['name'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $device_type = $_POST['device_type'] ?? '';
            $device_model = $_POST['device_model'] ?? '';
            $message = $_POST['message'] ?? '';

            if (!$name || !$phone) {
                json_response(['error' => 'Имя и телефон обязательны'], 400);
            }

            ContactRequest::create([
                'name' => $name,
                'phone' => $phone,
                'device_type' => $device_type ?: null,
                'device_model' => $device_model ?: null,
                'message' => $message ?: null,
            ]);

            FormInteraction::create([
                'form_name' => 'callback',
                'action' => 'submit',
                'ip' => getClientIp(),
            ]);

            json_response(['ok' => true, 'message' => 'Спасибо! Мы свяжемся с вами в ближайшее время.']);
            break;

        case '/api/categories':
            $categories = Service::getCategories();
            json_response($categories);
            break;

        case '/api/brands':
            $deviceType = $_GET['device_type'] ?? '';
            $brands = PriceItem::getBrands($deviceType ?: null);
            json_response($brands);
            break;

        case '/api/models':
            $deviceType = $_GET['device_type'] ?? '';
            $brand = $_GET['brand'] ?? '';
            $models = PriceItem::getModels($deviceType, $brand);
            json_response($models);
            break;

        case '/api/prices-table':
            $deviceType = $_GET['device_type'] ?? '';
            $brand = $_GET['brand'] ?? '';
            $model = $_GET['model'] ?? '';
            $query = $_GET['q'] ?? '';
            $page = max(1, (int)($_GET['page'] ?? 1));
            $result = PriceItem::search($deviceType, $brand, $model, $query, $page, 20);

            SearchQuery::create([
                'query' => $query ?: null,
                'device_type' => $deviceType ?: null,
                'brand' => $brand ?: null,
                'model_name' => $model ?: null,
                'ip' => getClientIp(),
            ]);

            ob_start();
            $items = $result['items'];
            $total = $result['total'];
            $page = $result['page'];
            $perPage = $result['perPage'];
            $totalPages = max(1, (int)ceil($total / $perPage));
            include __DIR__ . '/../templates/_prices_table.php';
            $html = ob_get_clean();

            json_response(['html' => $html, 'total' => $total, 'page' => $page, 'totalPages' => $totalPages]);
            break;

        case '/api/workshops':
            $workshops = PartnerWorkshop::getActive();
            json_response($workshops);
            break;

        default:
            json_response(['error' => 'Not found'], 404);
    }
}
