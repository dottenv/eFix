<?php

namespace App\Repositories;

use App\Core\Database;

class PageRepository
{
    public function findBySection(string $section): array
    {
        return Database::instance()->fetchAll(
            'SELECT * FROM pages WHERE section = ? AND is_active = 1 ORDER BY sort_order ASC',
            [$section]
        );
    }

    public function findBySlug(string $slug): ?array
    {
        return Database::instance()->fetchOne(
            'SELECT * FROM pages WHERE slug = ?',
            [$slug]
        );
    }

    public function findAll(): array
    {
        return Database::instance()->fetchAll(
            'SELECT * FROM pages ORDER BY section ASC, sort_order ASC'
        );
    }

    public function create(array $data): int
    {
        return Database::instance()->insert('pages', [
            'slug' => $data['slug'],
            'title' => $data['title'],
            'subtitle' => $data['subtitle'] ?? null,
            'content' => $data['content'] ?? null,
            'meta_title' => $data['meta_title'] ?? '',
            'meta_description' => $data['meta_description'] ?? null,
            'section' => $data['section'],
            'is_active' => $data['is_active'] ?? true,
            'sort_order' => $data['sort_order'] ?? 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function update(int $id, array $data): int
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return Database::instance()->update('pages', $data, 'id = ?', [$id]);
    }

    public function delete(int $id): int
    {
        return Database::instance()->delete('pages', 'id = ?', [$id]);
    }
}
