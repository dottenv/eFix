<?php

namespace App\Models;

use App\Core\Database;

class Workshop
{
    public static function getAll(): array
    {
        $stmt = Database::db()->query('SELECT * FROM workshops WHERE active = 1 ORDER BY name');
        return $stmt->fetchAll();
    }

    public static function create(array $data): void
    {
        $stmt = Database::db()->prepare(
            'INSERT INTO workshops (name, address, lat, lng, phone, description, active)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $data['name'], $data['address'], $data['lat'], $data['lng'],
            $data['phone'], $data['description'], $data['active'] ?? 1,
        ]);
    }

    public static function update(int $id, array $data): void
    {
        $stmt = Database::db()->prepare(
            'UPDATE workshops SET name=?, address=?, lat=?, lng=?, phone=?, description=?, active=? WHERE id=?'
        );
        $stmt->execute([
            $data['name'], $data['address'], $data['lat'], $data['lng'],
            $data['phone'], $data['description'], $data['active'] ?? 1, $id,
        ]);
    }

    public static function delete(int $id): void
    {
        $stmt = Database::db()->prepare('DELETE FROM workshops WHERE id = ?');
        $stmt->execute([$id]);
    }
}
