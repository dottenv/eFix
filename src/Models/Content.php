<?php

namespace App\Models;

use App\Core\Database;

class Content
{
    public static function getAll(): array
    {
        $stmt = Database::db()->query('SELECT * FROM content ORDER BY `key`');
        return $stmt->fetchAll();
    }

    public static function get(string $key, string $default = ''): string
    {
        $stmt = Database::db()->prepare('SELECT `value` FROM content WHERE `key` = ?');
        $stmt->execute([$key]);
        $row = $stmt->fetch();
        return $row ? $row['value'] : $default;
    }

    public static function set(string $key, string $value): void
    {
        $stmt = Database::db()->prepare(
            'INSERT INTO content (`key`, `value`) VALUES (?, ?)
             ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)'
        );
        $stmt->execute([$key, $value]);
    }

    public static function delete(string $key): void
    {
        $stmt = Database::db()->prepare('DELETE FROM content WHERE `key` = ?');
        $stmt->execute([$key]);
    }
}
