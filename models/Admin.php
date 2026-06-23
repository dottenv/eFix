<?php
class Admin {
    public static function getByUsername($username) {
        $db = Database::getInstance();
        return $db->fetch("SELECT * FROM admin WHERE username = ?", [$username]);
    }

    public static function getById($id) {
        $db = Database::getInstance();
        return $db->fetch("SELECT * FROM admin WHERE id = ?", [$id]);
    }

    public static function create($username, $password) {
        $db = Database::getInstance();
        $hash = password_hash($password, PASSWORD_DEFAULT);
        return $db->insert('admin', ['username' => $username, 'password_hash' => $hash]);
    }

    public static function count() {
        $db = Database::getInstance();
        return $db->fetchColumn("SELECT COUNT(*) FROM admin");
    }
}
