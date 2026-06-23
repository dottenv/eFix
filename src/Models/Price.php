<?php

namespace App\Models;

use App\Core\Database;

class Price
{
    public static function getAll(array $filters = []): array
    {
        $sql = 'SELECT * FROM prices WHERE 1=1';
        $params = [];

        if (!empty($filters['type'])) {
            $sql .= ' AND device_type = ?';
            $params[] = $filters['type'];
        }
        if (!empty($filters['brand'])) {
            $sql .= ' AND brand = ?';
            $params[] = $filters['brand'];
        }
        if (!empty($filters['model'])) {
            $sql .= ' AND model = ?';
            $params[] = $filters['model'];
        }
        if (!empty($filters['search'])) {
            $sql .= ' AND (service LIKE ? OR brand LIKE ? OR model LIKE ?)';
            $search = '%' . $filters['search'] . '%';
            $params = array_merge($params, [$search, $search, $search]);
        }

        $sql .= ' ORDER BY device_type, brand, model';

        $stmt = Database::db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
