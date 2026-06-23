<?php
class IpLocation {
    public static function getByIp($ip) {
        $db = Database::getInstance();
        return $db->fetch("SELECT * FROM ip_location WHERE ip = ?", [$ip]);
    }

    public static function upsert($data) {
        $db = Database::getInstance();
        $existing = self::getByIp($data['ip']);
        if ($existing) {
            $db->update('ip_location', $data, 'id = ?', [$existing['id']]);
        } else {
            $db->insert('ip_location', $data);
        }
    }
}
