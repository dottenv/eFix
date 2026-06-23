<?php
class FormInteraction {
    public static function create($data) {
        $db = Database::getInstance();
        return $db->insert('form_interaction', $data);
    }
}
