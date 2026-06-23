<?php

namespace App\Models;

use App\Core\Database;

class ClientRequest
{
    public static function create(array $data): void
    {
        $stmt = Database::db()->prepare(
            'INSERT INTO requests (name, phone, device_type, device_model, message, status) VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $data['name'],
            $data['phone'],
            $data['device_type'] ?? '',
            $data['device_model'] ?? '',
            $data['message'] ?? '',
            'new',
        ]);
    }

    public static function getAll(): array
    {
        $stmt = Database::db()->query('SELECT * FROM requests ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }

    public static function countNew(): int
    {
        $stmt = Database::db()->query("SELECT COUNT(*) FROM requests WHERE status = 'new'");
        return (int)$stmt->fetchColumn();
    }
}
