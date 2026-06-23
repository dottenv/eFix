<?php
class PriceItem {
    public static function getDeviceTypes() {
        $db = Database::getInstance();
        return $db->fetchAll("SELECT DISTINCT device_type FROM price_item ORDER BY device_type");
    }

    public static function getBrands($deviceType = null) {
        $db = Database::getInstance();
        if ($deviceType) {
            return $db->fetchAll("SELECT DISTINCT brand FROM price_item WHERE device_type = ? AND brand != '—' ORDER BY brand", [$deviceType]);
        }
        return $db->fetchAll("SELECT DISTINCT brand FROM price_item WHERE brand != '—' ORDER BY brand");
    }

    public static function getModels($deviceType, $brand) {
        $db = Database::getInstance();
        if ($brand && $brand !== '—') {
            return $db->fetchAll("SELECT DISTINCT model_name FROM price_item WHERE device_type = ? AND brand = ? ORDER BY model_name", [$deviceType, $brand]);
        }
        return $db->fetchAll("SELECT DISTINCT model_name FROM price_item WHERE device_type = ? ORDER BY model_name", [$deviceType]);
    }

    public static function search($deviceType, $brand, $model, $query, $page = 1, $perPage = 10) {
        $db = Database::getInstance();
        $where = ["is_active = 1"];
        $params = [];

        if ($deviceType) {
            $where[] = "device_type = ?";
            $params[] = $deviceType;
        }
        if ($brand) {
            $where[] = "brand = ?";
            $params[] = $brand;
        }
        if ($model) {
            $where[] = "model_name = ?";
            $params[] = $model;
        }
        if ($query) {
            $where[] = "(service LIKE ? OR brand LIKE ? OR model_name LIKE ?)";
            $q = "%$query%";
            $params[] = $q; $params[] = $q; $params[] = $q;
        }

        $whereSql = implode(' AND ', $where) ?: '1=1';
        $total = $db->fetchColumn("SELECT COUNT(*) FROM price_item WHERE $whereSql", $params);
        $offset = ($page - 1) * $perPage;
        $items = $db->fetchAll("SELECT * FROM price_item WHERE $whereSql ORDER BY device_type, brand, model_name, service LIMIT ? OFFSET ?", [...$params, $perPage, $offset]);

        return ['items' => $items, 'total' => $total, 'page' => $page, 'perPage' => $perPage];
    }

    public static function getById($id) {
        $db = Database::getInstance();
        return $db->fetch("SELECT * FROM price_item WHERE id = ?", [$id]);
    }

    public static function create($data) {
        $db = Database::getInstance();
        return $db->insert('price_item', $data);
    }

    public static function update($id, $data) {
        $db = Database::getInstance();
        $db->update('price_item', $data, 'id = ?', [$id]);
    }

    public static function delete($id) {
        $db = Database::getInstance();
        $db->delete('price_item', 'id = ?', [$id]);
    }

    public static function getAll() {
        $db = Database::getInstance();
        return $db->fetchAll("SELECT * FROM price_item ORDER BY device_type, brand, model_name");
    }
}
