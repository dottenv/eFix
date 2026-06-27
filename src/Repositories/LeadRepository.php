<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Lead;

class LeadRepository
{
    public function create(array $data): int
    {
        return Database::instance()->insert('leads', [
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'] ?? null,
            'service_type' => $data['service_type'],
            'device_model' => $data['device_model'] ?? null,
            'device_brand' => $data['device_brand'] ?? null,
            'message' => $data['message'] ?? null,
            'status' => 'new',
            'source' => $data['source'] ?? 'site',
            'ip' => $data['ip'] ?? '127.0.0.1',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function findById(int $id): ?array
    {
        return Database::instance()->fetchOne(
            'SELECT * FROM leads WHERE id = ?',
            [$id]
        );
    }

    public function findAll(string $orderBy = 'created_at DESC', ?string $status = null): array
    {
        $sql = 'SELECT * FROM leads';
        $params = [];

        if ($status) {
            $sql .= ' WHERE status = ?';
            $params[] = $status;
        }

        $sql .= " ORDER BY {$orderBy}";

        return Database::instance()->fetchAll($sql, $params);
    }

    public function updateStatus(int $id, string $status): int
    {
        return Database::instance()->update(
            'leads',
            ['status' => $status, 'updated_at' => date('Y-m-d H:i:s')],
            'id = ?',
            [$id]
        );
    }

    public function delete(int $id): int
    {
        return Database::instance()->delete('leads', 'id = ?', [$id]);
    }

    public function countByStatus(?string $status = null): int
    {
        $sql = 'SELECT COUNT(*) as cnt FROM leads';
        $params = [];

        if ($status) {
            $sql .= ' WHERE status = ?';
            $params[] = $status;
        }

        $result = Database::instance()->fetchOne($sql, $params);
        return (int) ($result['cnt'] ?? 0);
    }
}
