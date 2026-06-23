<?php
class PartnerWorkshop {
    public static function getAll() {
        $db = Database::getInstance();
        return $db->fetchAll("SELECT * FROM partner_workshop ORDER BY id");
    }

    public static function getActive() {
        $db = Database::getInstance();
        return $db->fetchAll("SELECT * FROM partner_workshop WHERE is_active = 1 ORDER BY id");
    }

    public static function getById($id) {
        $db = Database::getInstance();
        return $db->fetch("SELECT * FROM partner_workshop WHERE id = ?", [$id]);
    }

    public static function create($data) {
        $db = Database::getInstance();
        return $db->insert('partner_workshop', $data);
    }

    public static function update($id, $data) {
        $db = Database::getInstance();
        $db->update('partner_workshop', $data, 'id = ?', [$id]);
    }

    public static function delete($id) {
        $db = Database::getInstance();
        $db->delete('partner_workshop', 'id = ?', [$id]);
    }
}
