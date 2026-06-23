<?php
class PageView {
    public static function countToday() {
        $db = Database::getInstance();
        return $db->fetchColumn("SELECT COUNT(*) FROM page_view WHERE date(created_at) = " . $db->dateNow());
    }

    public static function countUniqueToday() {
        $db = Database::getInstance();
        return $db->fetchColumn("SELECT COUNT(DISTINCT session_id) FROM page_view WHERE date(created_at) = " . $db->dateNow());
    }

    public static function countTotal() {
        $db = Database::getInstance();
        return $db->fetchColumn("SELECT COUNT(*) FROM page_view");
    }

    public static function getSummary() {
        $db = Database::getInstance();
        $today = $db->fetch("SELECT COUNT(*) as views, COUNT(DISTINCT session_id) as visitors FROM page_view WHERE date(created_at) = " . $db->dateNow());
        $week = $db->fetch("SELECT COUNT(*) as views, COUNT(DISTINCT session_id) as visitors FROM page_view WHERE created_at >= " . $db->dateSub(7));
        $month = $db->fetch("SELECT COUNT(*) as views, COUNT(DISTINCT session_id) as visitors FROM page_view WHERE created_at >= " . $db->dateSub(30));
        $total = $db->fetch("SELECT COUNT(*) as views, COUNT(DISTINCT session_id) as visitors FROM page_view");
        return ['today' => $today, 'week' => $week, 'month' => $month, 'total' => $total];
    }

    public static function getDailyViews($days = 30) {
        $db = Database::getInstance();
        $ds = $db->dateSub($days);
        $dn = $db->dateNow();
        $dateCol = $db->getDriver() === 'mysql' ? "DATE(created_at)" : "date(created_at)";
        return $db->fetchAll("SELECT $dateCol as date, COUNT(*) as views, COUNT(DISTINCT session_id) as visitors FROM page_view WHERE created_at >= $ds GROUP BY $dateCol ORDER BY $dateCol");
    }

    public static function getTopPages($limit = 10) {
        $db = Database::getInstance();
        return $db->fetchAll("SELECT path, COUNT(*) as views FROM page_view GROUP BY path ORDER BY views DESC LIMIT ?", [$limit]);
    }

    public static function getReferrers($limit = 10) {
        $db = Database::getInstance();
        return $db->fetchAll("SELECT referrer, COUNT(*) as count FROM page_view WHERE referrer IS NOT NULL AND referrer != '' GROUP BY referrer ORDER BY count DESC LIMIT ?", [$limit]);
    }

    public static function getSearches($limit = 50) {
        $db = Database::getInstance();
        return $db->fetchAll("SELECT * FROM search_query ORDER BY created_at DESC LIMIT ?", [$limit]);
    }

    public static function getFrequentSearches() {
        $db = Database::getInstance();
        return $db->fetchAll("SELECT query, COUNT(*) as count FROM search_query WHERE query IS NOT NULL AND query != '' GROUP BY query ORDER BY count DESC LIMIT 20");
    }

    public static function getDeviceTypeBreakdown() {
        $db = Database::getInstance();
        return $db->fetchAll("SELECT device_type, COUNT(*) as count FROM search_query WHERE device_type IS NOT NULL GROUP BY device_type ORDER BY count DESC");
    }

    public static function getFormInteractions() {
        $db = Database::getInstance();
        return $db->fetchAll("SELECT form_name, action, COUNT(*) as count FROM form_interaction GROUP BY form_name, action ORDER BY count DESC");
    }

    public static function getLocations() {
        $db = Database::getInstance();
        return $db->fetchAll("SELECT country, city, lat, lng, COUNT(*) as count FROM page_view WHERE country IS NOT NULL AND lat IS NOT NULL GROUP BY country, city, lat, lng ORDER BY count DESC");
    }

    public static function getRecent($limit = 20) {
        $db = Database::getInstance();
        return $db->fetchAll("SELECT * FROM page_view ORDER BY created_at DESC LIMIT ?", [$limit]);
    }

    public static function getUtms() {
        $db = Database::getInstance();
        return $db->fetchAll("SELECT utm_source, utm_medium, utm_campaign, COUNT(*) as count FROM page_view WHERE utm_source IS NOT NULL GROUP BY utm_source, utm_medium, utm_campaign ORDER BY count DESC LIMIT 20");
    }

    public static function getBrowsers() {
        $db = Database::getInstance();
        return $db->fetchAll("SELECT user_agent, COUNT(*) as count FROM page_view WHERE user_agent IS NOT NULL GROUP BY user_agent ORDER BY count DESC LIMIT 20");
    }

    public static function getOs() {
        $db = Database::getInstance();
        return $db->fetchAll("SELECT user_agent, COUNT(*) as count FROM page_view WHERE user_agent IS NOT NULL GROUP BY user_agent ORDER BY count DESC LIMIT 20");
    }

    public static function getScreens() {
        $db = Database::getInstance();
        return $db->fetchAll("SELECT screen, COUNT(*) as count FROM page_view WHERE screen IS NOT NULL AND screen != '' GROUP BY screen ORDER BY count DESC LIMIT 10");
    }

    public static function getLanguages() {
        $db = Database::getInstance();
        return $db->fetchAll("SELECT language, COUNT(*) as count FROM page_view WHERE language IS NOT NULL GROUP BY language ORDER BY count DESC LIMIT 10");
    }
}
