<?php
class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $dsn = DATABASE_URL;
        if (str_starts_with($dsn, 'sqlite:')) {
            $path = substr($dsn, 7);
            $this->pdo = new PDO('sqlite:' . $path);
        } else {
            $this->pdo = new PDO($dsn);
        }
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->pdo->exec('PRAGMA journal_mode=WAL');
        $this->pdo->exec('PRAGMA foreign_keys=ON');
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getPdo() {
        return $this->pdo;
    }

    public function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function fetch($sql, $params = []) {
        return $this->query($sql, $params)->fetch();
    }

    public function fetchAll($sql, $params = []) {
        return $this->query($sql, $params)->fetchAll();
    }

    public function fetchColumn($sql, $params = []) {
        return $this->query($sql, $params)->fetchColumn();
    }

    public function insert($table, $data) {
        $cols = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $this->query("INSERT INTO $table ($cols) VALUES ($placeholders)", $data);
        return $this->pdo->lastInsertId();
    }

    public function update($table, $data, $where, $whereParams = []) {
        $sets = implode(', ', array_map(fn($k) => "$k = :$k", array_keys($data)));
        return $this->query("UPDATE $table SET $sets WHERE $where", array_merge($data, $whereParams))->rowCount();
    }

    public function delete($table, $where, $params = []) {
        return $this->query("DELETE FROM $table WHERE $where", $params)->rowCount();
    }

    public function initSchema() {
        $tables = $this->pdo->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll(PDO::FETCH_COLUMN);

        if (!in_array('admin', $tables)) {
            $this->pdo->exec("CREATE TABLE admin (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT UNIQUE NOT NULL,
                password_hash TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )");
        }

        if (!in_array('site_content', $tables)) {
            $this->pdo->exec("CREATE TABLE site_content (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                page TEXT NOT NULL DEFAULT 'global',
                key TEXT NOT NULL,
                value TEXT,
                UNIQUE(page, key)
            )");
        }

        if (!in_array('service', $tables)) {
            $this->pdo->exec("CREATE TABLE service (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT NOT NULL,
                description TEXT,
                price TEXT,
                icon TEXT,
                category TEXT,
                sort_order INTEGER DEFAULT 0,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )");
        }

        if (!in_array('price_item', $tables)) {
            $this->pdo->exec("CREATE TABLE price_item (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                device_type TEXT NOT NULL,
                brand TEXT NOT NULL,
                model_name TEXT NOT NULL,
                service TEXT NOT NULL,
                price_from INTEGER NOT NULL,
                price_to INTEGER,
                is_active INTEGER DEFAULT 1
            )");
        }

        if (!in_array('partner_workshop', $tables)) {
            $this->pdo->exec("CREATE TABLE partner_workshop (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                address TEXT NOT NULL,
                lat REAL NOT NULL,
                lng REAL NOT NULL,
                phone TEXT,
                description TEXT,
                is_active INTEGER DEFAULT 1,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )");
        }

        if (!in_array('contact_request', $tables)) {
            $this->pdo->exec("CREATE TABLE contact_request (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                phone TEXT NOT NULL,
                device_type TEXT,
                device_model TEXT,
                message TEXT,
                status TEXT DEFAULT 'new',
                admin_notes TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )");
        }

        if (!in_array('page_view', $tables)) {
            $this->pdo->exec("CREATE TABLE page_view (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                path TEXT NOT NULL,
                ip TEXT,
                user_agent TEXT,
                referrer TEXT,
                country TEXT,
                city TEXT,
                lat REAL,
                lng REAL,
                screen TEXT,
                language TEXT,
                utm_source TEXT,
                utm_medium TEXT,
                utm_campaign TEXT,
                session_id TEXT,
                is_new_visitor INTEGER DEFAULT 1,
                load_time INTEGER,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )");
        }

        if (!in_array('search_query', $tables)) {
            $this->pdo->exec("CREATE TABLE search_query (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                query TEXT,
                device_type TEXT,
                brand TEXT,
                model_name TEXT,
                ip TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )");
        }

        if (!in_array('form_interaction', $tables)) {
            $this->pdo->exec("CREATE TABLE form_interaction (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                form_name TEXT NOT NULL,
                action TEXT NOT NULL,
                ip TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )");
        }

        if (!in_array('ip_location', $tables)) {
            $this->pdo->exec("CREATE TABLE ip_location (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                ip TEXT UNIQUE NOT NULL,
                country TEXT,
                region TEXT,
                city TEXT,
                lat REAL,
                lng REAL,
                isp TEXT,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )");
        }

        if (!in_array('mail_config', $tables)) {
            $this->pdo->exec("CREATE TABLE mail_config (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                smtp_host TEXT DEFAULT '',
                smtp_port INTEGER DEFAULT 587,
                smtp_user TEXT DEFAULT '',
                smtp_pass TEXT DEFAULT '',
                smtp_use_tls INTEGER DEFAULT 1,
                from_email TEXT DEFAULT '',
                from_name TEXT DEFAULT '',
                notify_on_new_request INTEGER DEFAULT 0,
                notify_email TEXT DEFAULT '',
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )");
        }

        if (!in_array('mail_template', $tables)) {
            $this->pdo->exec("CREATE TABLE mail_template (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                subject TEXT NOT NULL,
                body TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )");
        }

        if (!in_array('app_setting', $tables)) {
            $this->pdo->exec("CREATE TABLE app_setting (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                key TEXT UNIQUE NOT NULL,
                value TEXT,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )");
        }

        $this->seedData();
    }

    private function seedData() {
        // Seed services
        $count = $this->fetchColumn("SELECT COUNT(*) FROM service");
        if ($count == 0) {
            $services = [
                ['phones', 'Ремонтируем iPhone, Samsung, Xiaomi, Honor, Huawei и любые другие смартфоны. В наличии запчасти для большинства моделей.', json_encode([
                    ['Замена стекла / дисплея', 'от 1 500 ₽', 1], ['Замена аккумулятора', 'от 800 ₽', 2],
                    ['Замена разъёма зарядки', 'от 700 ₽', 3], ['Ремонт после воды', 'от 1 000 ₽', 4],
                    ['Замена задней крышки', 'от 1 200 ₽', 5], ['Ремонт кнопок / динамиков', 'от 500 ₽', 6],
                    ['Восстановление данных', 'от 2 000 ₽', 7], ['Прошивка / обновление ПО', 'от 500 ₽', 8],
                ])],
                ['tablets', 'iPad, Samsung Galaxy Tab, Huawei и другие. Сложный ремонт любой сложности.', json_encode([
                    ['Замена стекла / дисплея', 'от 2 000 ₽', 1], ['Замена аккумулятора', 'от 1 200 ₽', 2],
                    ['Замена разъёма зарядки', 'от 800 ₽', 3], ['Ремонт после воды', 'от 1 500 ₽', 4],
                    ['Восстановление после удара', 'от 1 500 ₽', 5],
                ])],
                ['laptops', 'MacBook, Dell, HP, Lenovo, Asus, Acer, MSI. Любые модели и поломки.', json_encode([
                    ['Замена матрицы / экрана', 'от 2 500 ₽', 1], ['Чистка от пыли / замена термопасты', 'от 1 000 ₽', 2],
                    ['Замена клавиатуры', 'от 1 200 ₽', 3], ['Установка SSD / RAM', 'от 500 ₽', 4],
                    ['Ремонт после воды', 'от 1 500 ₽', 5], ['Замена разъёма питания', 'от 800 ₽', 6],
                    ['Настройка ПО / установка Windows', 'от 500 ₽', 7],
                ])],
                ['pc', 'Сборка, апгрейд, ремонт, настройка. Поможем с любой задачей.', json_encode([
                    ['Сборка ПК под заказ', 'от 2 000 ₽', 1], ['Замена блока питания', 'от 500 ₽', 2],
                    ['Замена видеокарты / CPU', 'от 500 ₽', 3], ['Чистка / обслуживание', 'от 800 ₽', 4],
                    ['Настройка сети / Wi-Fi', 'от 500 ₽', 5], ['Удаление вирусов', 'от 500 ₽', 6],
                ])],
            ];
            foreach ($services as $cat) {
                $items = json_decode($cat[2], true);
                $desc = $cat[1];
                foreach ($items as $item) {
                    $this->insert('service', [
                        'category' => $cat[0], 'title' => $item[0], 'price' => $item[1],
                        'sort_order' => $item[2], 'description' => $desc,
                    ]);
                }
            }
        }

        // Seed price items
        $count = $this->fetchColumn("SELECT COUNT(*) FROM price_item");
        if ($count == 0) {
            $prices = [
                ...$this->makePrices('phone', 'Apple', 'iPhone 14 Pro', [['Замена дисплея', 4000, 8000], ['Замена аккумулятора', 2500, 4000], ['Замена задней крышки', 3000, 5000], ['Замена разъёма зарядки', 1500, 2500]]),
                ...$this->makePrices('phone', 'Apple', 'iPhone 14', [['Замена дисплея', 3500, 7000], ['Замена аккумулятора', 2000, 3500], ['Замена задней крышки', 2500, 4000]]),
                ...$this->makePrices('phone', 'Apple', 'iPhone 13', [['Замена дисплея', 3000, 6000], ['Замена аккумулятора', 1800, 3000], ['Замена разъёма зарядки', 1200, 2000]]),
                ...$this->makePrices('phone', 'Apple', 'iPhone 12', [['Замена дисплея', 2500, 5000], ['Замена аккумулятора', 1500, 2500]]),
                ...$this->makePrices('phone', 'Samsung', 'Galaxy S24', [['Замена дисплея', 3500, 7000], ['Замена аккумулятора', 1500, 2500], ['Замена разъёма зарядки', 1000, 1500]]),
                ...$this->makePrices('phone', 'Samsung', 'Galaxy S23', [['Замена дисплея', 3000, 6000], ['Замена аккумулятора', 1200, 2000]]),
                ...$this->makePrices('phone', 'Samsung', 'Galaxy A55', [['Замена дисплея', 2000, 4000], ['Замена аккумулятора', 1000, 1500]]),
                ...$this->makePrices('phone', 'Xiaomi', '13T Pro', [['Замена дисплея', 3000, 5500], ['Замена аккумулятора', 1200, 2000]]),
                ...$this->makePrices('phone', 'Xiaomi', 'Redmi Note 13', [['Замена дисплея', 1800, 3500], ['Замена аккумулятора', 800, 1500]]),
                ...$this->makePrices('phone', 'Huawei', 'P60 Pro', [['Замена дисплея', 3500, 6500], ['Замена аккумулятора', 1500, 2500]]),
                ...$this->makePrices('phone', 'Huawei', 'Nova 12', [['Замена дисплея', 2000, 4000], ['Замена аккумулятора', 1000, 1800]]),
                ...$this->makePrices('tablet', 'Apple', 'iPad Pro 12.9"', [['Замена дисплея', 8000, 15000], ['Замена аккумулятора', 3000, 5000]]),
                ...$this->makePrices('tablet', 'Apple', 'iPad Air M2', [['Замена дисплея', 5000, 10000], ['Замена аккумулятора', 2500, 4000]]),
                ...$this->makePrices('tablet', 'Apple', 'iPad 10 gen', [['Замена дисплея', 4000, 8000], ['Замена аккумулятора', 2000, 3500]]),
                ...$this->makePrices('tablet', 'Samsung', 'Galaxy Tab S9', [['Замена дисплея', 5000, 10000], ['Замена аккумулятора', 2000, 3500]]),
                ...$this->makePrices('tablet', 'Samsung', 'Galaxy Tab A9+', [['Замена дисплея', 2500, 5000], ['Замена аккумулятора', 1200, 2000]]),
                ...$this->makePrices('laptop', 'Apple', 'MacBook Pro 14" M3', [['Замена матрицы', 15000, 25000], ['Замена аккумулятора', 5000, 8000], ['Чистка от пыли', 2000, 3000]]),
                ...$this->makePrices('laptop', 'Apple', 'MacBook Air M2', [['Замена матрицы', 12000, 20000], ['Замена аккумулятора', 4000, 7000]]),
                ...$this->makePrices('laptop', 'Dell', 'XPS 15', [['Замена матрицы', 6000, 12000], ['Чистка от пыли', 1000, 2000], ['Замена клавиатуры', 2500, 4500]]),
                ...$this->makePrices('laptop', 'Dell', 'Inspiron 16', [['Замена матрицы', 4000, 8000], ['Чистка от пыли', 1000, 1500]]),
                ...$this->makePrices('laptop', 'Lenovo', 'ThinkPad X1 Carbon', [['Замена матрицы', 5000, 10000], ['Замена клавиатуры', 2000, 3500]]),
                ...$this->makePrices('laptop', 'Lenovo', 'IdeaPad 5', [['Замена матрицы', 3500, 7000], ['Чистка от пыли', 800, 1500]]),
                ...$this->makePrices('laptop', 'HP', 'Pavilion 15', [['Замена матрицы', 3500, 7000], ['Замена клавиатуры', 1500, 3000]]),
                ...$this->makePrices('laptop', 'Asus', 'ROG Strix G16', [['Замена матрицы', 5000, 10000], ['Чистка от пыли', 1500, 2500]]),
                ...$this->makePrices('laptop', 'Asus', 'Vivobook 15', [['Замена матрицы', 3000, 6000], ['Чистка от пыли', 800, 1500]]),
                ...$this->makePrices('pc', '—', 'Сборка ПК под заказ', [['Сборка ПК под заказ', 2000, 5000]]),
                ...$this->makePrices('pc', '—', 'Моноблок любая модель', [['Замена матрицы', 3000, 7000], ['Чистка от пыли', 1000, 2000]]),
                ...$this->makePrices('pc', '—', 'Системный блок', [['Замена блока питания', 500, 1500], ['Замена видеокарты', 500, 1500], ['Замена CPU', 500, 1500], ['Чистка / обслуживание', 800, 2000]]),
                ...$this->makePrices('pc', '—', 'Любой ПК', [['Настройка сети / Wi-Fi', 500, 1500], ['Удаление вирусов', 500, 1500], ['Установка SSD / RAM', 500, 1000]]),
                ['phone', '—', 'Любая модель', 'Ремонт после воды', 1000, 4000],
                ['phone', '—', 'Любая модель', 'Восстановление данных', 2000, 5000],
                ['phone', '—', 'Любая модель', 'Прошивка / обновление ПО', 500, 1500],
                ['laptop', '—', 'Любой ноутбук', 'Ремонт после воды', 1500, 5000],
                ['laptop', '—', 'Любой ноутбук', 'Установка SSD / RAM', 500, 1000],
                ['laptop', '—', 'Любой ноутбук', 'Настройка ПО / установка Windows', 500, 1500],
            ];
            foreach ($prices as $p) {
                $this->insert('price_item', [
                    'device_type' => $p[0], 'brand' => $p[1], 'model_name' => $p[2],
                    'service' => $p[3], 'price_from' => $p[4], 'price_to' => $p[5] ?? null,
                ]);
            }
        }

        // Seed workshops
        $count = $this->fetchColumn("SELECT COUNT(*) FROM partner_workshop");
        if ($count == 0) {
            $workshops = [
                ['','',55.029166,82.936306,'','Телефоны, планшеты, ноутбуки'],
                ['','',55.037448,82.960897,'','Телефоны, планшеты, ноутбуки'],
                ['','',54.966197,82.853484,'','Ноутбуки и ПК'],
                ['','',54.989865,82.903842,'','Телефоны и планшеты'],
                ['','',55.04349,82.953921,'','Apple-техника и планшеты'],
                ['','',55.027007,82.920679,'','Телефоны, смарт-часы, наушники'],
                ['','',55.043294,82.917403,'','Все виды цифровой техники'],
                ['','',55.035801,82.900042,'','Телефоны, ноутбуки, наушники'],
                ['','',55.035402,82.975433,'','Телефоны и планшеты'],
                ['','',54.981934,82.874256,'','Телефоны и ноутбуки'],
                ['','',54.9824,82.8984,'','Все виды техники'],
                ['','',55.013281,82.953101,'','Телефоны и ноутбуки'],
                ['','',55.066563,82.934551,'','Телефоны, планшеты, ноутбуки'],
                ['','',55.057835,82.90904,'','Ноутбуки и моноблоки'],
            ];
            foreach ($workshops as $w) {
                $this->insert('partner_workshop', [
                    'name' => $w[0] ?: 'Партнёрский сервис', 'address' => $w[0] ?: 'Новосибирск',
                    'lat' => $w[2], 'lng' => $w[3], 'phone' => $w[4], 'description' => $w[5],
                ]);
            }
        }
    }

    private function makePrices($type, $brand, $model, $services) {
        $result = [];
        foreach ($services as $s) {
            $result[] = [$type, $brand, $model, $s[0], $s[1], $s[2]];
        }
        return $result;
    }
}
