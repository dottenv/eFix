<?php
class MailTemplate {
    public static function getAll() {
        $db = Database::getInstance();
        return $db->fetchAll("SELECT * FROM mail_template ORDER BY id");
    }

    public static function getById($id) {
        $db = Database::getInstance();
        return $db->fetch("SELECT * FROM mail_template WHERE id = ?", [$id]);
    }

    public static function getByName($name) {
        $db = Database::getInstance();
        return $db->fetch("SELECT * FROM mail_template WHERE name = ?", [$name]);
    }

    public static function create($data) {
        $db = Database::getInstance();
        return $db->insert('mail_template', $data);
    }

    public static function update($id, $data) {
        $db = Database::getInstance();
        $db->update('mail_template', $data, 'id = ?', [$id]);
    }

    public static function delete($id) {
        $db = Database::getInstance();
        $db->delete('mail_template', 'id = ?', [$id]);
    }
}
