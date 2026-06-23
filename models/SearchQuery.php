<?php
class SearchQuery {
    public static function create($data) {
        $db = Database::getInstance();
        return $db->insert('search_query', $data);
    }
}
