CREATE TABLE IF NOT EXISTS content (
    `key` VARCHAR(100) PRIMARY KEY,
    `value` TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price VARCHAR(50),
    icon VARCHAR(100),
    category ENUM('phones', 'tablets', 'laptops', 'pc') NOT NULL,
    sort INT DEFAULT 0
);

CREATE TABLE IF NOT EXISTS prices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    device_type ENUM('phone', 'tablet', 'laptop', 'pc') NOT NULL,
    brand VARCHAR(100),
    model VARCHAR(100),
    service VARCHAR(255) NOT NULL,
    price_from DECIMAL(10,2),
    price_to DECIMAL(10,2),
    active TINYINT(1) DEFAULT 1
);

CREATE TABLE IF NOT EXISTS requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    device_type VARCHAR(50),
    device_model VARCHAR(100),
    message TEXT,
    status ENUM('new', 'in_progress', 'ready', 'archive') DEFAULT 'new',
    admin_note TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS workshops (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    address VARCHAR(500),
    lat DECIMAL(10,7),
    lng DECIMAL(10,7),
    phone VARCHAR(20),
    description TEXT,
    active TINYINT(1) DEFAULT 1
);

CREATE TABLE IF NOT EXISTS page_views (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    path VARCHAR(500),
    ip VARCHAR(45),
    user_agent TEXT,
    referer TEXT,
    session_id VARCHAR(100),
    language VARCHAR(20),
    screen VARCHAR(20),
    utm_source VARCHAR(100),
    utm_medium VARCHAR(100),
    utm_campaign VARCHAR(100),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS settings (
    `key` VARCHAR(100) PRIMARY KEY,
    `value` TEXT
);

CREATE TABLE IF NOT EXISTS mail_templates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    subject VARCHAR(255),
    body TEXT
);
