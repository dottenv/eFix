<?php
class ContactRequest {
    const STATUSES = ['new' => 'Новая', 'in_progress' => 'В работе', 'completed' => 'Готова', 'archived' => 'Архив'];

    public static function getAll() {
        $db = Database::getInstance();
        return $db->fetchAll("SELECT * FROM contact_request ORDER BY created_at DESC");
    }

    public static function getNew() {
        $db = Database::getInstance();
        return $db->fetchAll("SELECT * FROM contact_request WHERE status = 'new' ORDER BY created_at DESC");
    }

    public static function getById($id) {
        $db = Database::getInstance();
        return $db->fetch("SELECT * FROM contact_request WHERE id = ?", [$id]);
    }

    public static function create($data) {
        $db = Database::getInstance();
        return $db->insert('contact_request', $data);
    }

    public static function update($id, $data) {
        $db = Database::getInstance();
        $db->update('contact_request', $data, 'id = ?', [$id]);
    }

    public static function countNew() {
        $db = Database::getInstance();
        return $db->fetchColumn("SELECT COUNT(*) FROM contact_request WHERE status = 'new'");
    }

    public static function countTotal() {
        $db = Database::getInstance();
        return $db->fetchColumn("SELECT COUNT(*) FROM contact_request");
    }
}
