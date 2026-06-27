<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Service;

class ServiceRepository
{
    public function create(array $data): int
    {
        return Database::instance()->insert('services', [
            'slug' => $data['slug'],
            'title' => $data['title'],
            'description' => $data['description'],
            'icon' => $data['icon'] ?? null,
            'meta_title' => $data['meta_title'] ?? '',
            'meta_description' => $data['meta_description'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => $data['is_active'] ?? true,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function findAllActive(): array
    {
        return Database::instance()->fetchAll(
            'SELECT * FROM services WHERE is_active = 1 ORDER BY sort_order ASC'
        );
    }

    public function findAll(): array
    {
        return Database::instance()->fetchAll(
            'SELECT * FROM services ORDER BY sort_order ASC'
        );
    }

    public function findBySlug(string $slug): ?array
    {
        return Database::instance()->fetchOne(
            'SELECT * FROM services WHERE slug = ?',
            [$slug]
        );
    }

    public function findById(int $id): ?array
    {
        return Database::instance()->fetchOne(
            'SELECT * FROM services WHERE id = ?',
            [$id]
        );
    }

    public function update(int $id, array $data): int
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return Database::instance()->update('services', $data, 'id = ?', [$id]);
    }

    public function delete(int $id): int
    {
        return Database::instance()->delete('services', 'id = ?', [$id]);
    }
}
