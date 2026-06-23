<?php
class MailConfig {
    public static function get() {
        $db = Database::getInstance();
        $row = $db->fetch("SELECT * FROM mail_config WHERE id = 1");
        return $row ?: [
            'smtp_host' => '', 'smtp_port' => 587, 'smtp_user' => '', 'smtp_pass' => '',
            'smtp_use_tls' => 1, 'from_email' => '', 'from_name' => '',
            'notify_on_new_request' => 0, 'notify_email' => '',
        ];
    }

    public static function save($data) {
        $db = Database::getInstance();
        $existing = $db->fetch("SELECT id FROM mail_config WHERE id = 1");
        $data['updated_at'] = date('Y-m-d H:i:s');
        if ($existing) {
            $db->update('mail_config', $data, 'id = 1');
        } else {
            $db->insert('mail_config', $data);
        }
    }
}
