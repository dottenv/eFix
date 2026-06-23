<?php
class AppSetting {
    public static function get($key, $default = '') {
        $db = Database::getInstance();
        $row = $db->fetch("SELECT value FROM app_setting WHERE key = ?", [$key]);
        return $row ? $row['value'] : $default;
    }

    public static function set($key, $value) {
        $db = Database::getInstance();
        $existing = $db->fetch("SELECT id FROM app_setting WHERE key = ?", [$key]);
        $data = ['value' => $value, 'updated_at' => date('Y-m-d H:i:s')];
        if ($existing) {
            $db->update('app_setting', $data, 'id = ?', [$existing['id']]);
        } else {
            $data['key'] = $key;
            $db->insert('app_setting', $data);
        }
    }

    public static function getAll() {
        $db = Database::getInstance();
        return $db->fetchAll("SELECT * FROM app_setting ORDER BY key");
    }
}
