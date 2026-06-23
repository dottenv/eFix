<?php

namespace App\Models;

use App\Core\Database;

class Service
{
    public static function getAll(): array
    {
        $stmt = Database::db()->query('SELECT * FROM services ORDER BY sort ASC');
        return $stmt->fetchAll();
    }

    public static function getByCategory(string $category): array
    {
        $stmt = Database::db()->prepare('SELECT * FROM services WHERE category = ? ORDER BY sort ASC');
        $stmt->execute([$category]);
        return $stmt->fetchAll();
    }

    public static function create(array $data): void
    {
        $stmt = Database::db()->prepare(
            'INSERT INTO services (name, description, price, icon, category, sort) VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $data['name'],
            $data['description'],
            $data['price'],
            $data['icon'],
            $data['category'],
            $data['sort'] ?? 0,
        ]);
    }

    public static function update(int $id, array $data): void
    {
        $stmt = Database::db()->prepare(
            'UPDATE services SET name=?, description=?, price=?, icon=?, category=?, sort=? WHERE id=?'
        );
        $stmt->execute([
            $data['name'],
            $data['description'],
            $data['price'],
            $data['icon'],
            $data['category'],
            $data['sort'] ?? 0,
            $id,
        ]);
    }

    public static function delete(int $id): void
    {
        $stmt = Database::db()->prepare('DELETE FROM services WHERE id = ?');
        $stmt->execute([$id]);
    }
}
