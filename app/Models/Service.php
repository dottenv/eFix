<?php
class Service {
    public static function getAll() {
        $db = Database::getInstance();
        return $db->fetchAll("SELECT * FROM service ORDER BY sort_order");
    }

    public static function getById($id) {
        $db = Database::getInstance();
        return $db->fetch("SELECT * FROM service WHERE id = ?", [$id]);
    }

    public static function getByCategory($category) {
        $db = Database::getInstance();
        return $db->fetchAll("SELECT * FROM service WHERE category = ? ORDER BY sort_order", [$category]);
    }

    public static function getCategories() {
        $db = Database::getInstance();
        return $db->fetchAll("SELECT DISTINCT category FROM service ORDER BY category");
    }

    public static function create($data) {
        $db = Database::getInstance();
        return $db->insert('service', $data);
    }

    public static function update($id, $data) {
        $db = Database::getInstance();
        $db->update('service', $data, 'id = ?', [$id]);
    }

    public static function delete($id) {
        $db = Database::getInstance();
        $db->delete('service', 'id = ?', [$id]);
    }
}
