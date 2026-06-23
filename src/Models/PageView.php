<?php

namespace App\Models;

use App\Core\Database;

class PageView
{
    public static function track(string $path, string $ip, string $ua, string $referer): void
    {
        $stmt = Database::db()->prepare(
            'INSERT INTO page_views (path, ip, user_agent, referer, session_id, created_at)
             VALUES (?, ?, ?, ?, ?, NOW())'
        );
        $stmt->execute([$path, $ip, $ua, $referer, session_id()]);
    }

    public static function today(): int
    {
        $stmt = Database::db()->query("SELECT COUNT(*) FROM page_views WHERE DATE(created_at) = CURDATE()");
        return (int)$stmt->fetchColumn();
    }

    public static function uniqueToday(): int
    {
        $stmt = Database::db()->query("SELECT COUNT(DISTINCT ip) FROM page_views WHERE DATE(created_at) = CURDATE()");
        return (int)$stmt->fetchColumn();
    }

    public static function lastDays(int $days): array
    {
        $stmt = Database::db()->prepare(
            "SELECT DATE(created_at) as date, COUNT(*) as count
             FROM page_views
             WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
             GROUP BY DATE(created_at)
             ORDER BY date"
        );
        $stmt->execute([$days]);
        return $stmt->fetchAll();
    }
}
