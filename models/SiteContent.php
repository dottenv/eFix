<?php
class SiteContent {
    public static function getAll() {
        $db = Database::getInstance();
        return $db->fetchAll("SELECT * FROM site_content ORDER BY id");
    }

    public static function get($key, $default = '') {
        $db = Database::getInstance();
        $row = $db->fetch("SELECT value FROM site_content WHERE key = ?", [$key]);
        return $row ? $row['value'] : $default;
    }

    public static function set($key, $value, $page = 'global') {
        $db = Database::getInstance();
        $row = $db->fetch("SELECT id FROM site_content WHERE key = ? AND page = ?", [$key, $page]);
        if ($row) {
            $db->update('site_content', ['value' => $value], 'id = ?', [$row['id']]);
        } else {
            $db->insert('site_content', ['key' => $key, 'value' => $value, 'page' => $page]);
        }
    }

    public static function delete($id) {
        $db = Database::getInstance();
        $db->delete('site_content', 'id = ?', [$id]);
    }
}
