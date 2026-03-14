<?php

// Model Report xử lý các báo cáo thống kê
class Report
{
    // Lấy tổng quan doanh thu, chi phí và lợi nhuận
    public static function getSummary()
    {
        $db = getDB();
        $stmt = $db->query("
            SELECT 
                COUNT(DISTINCT b.id) as total_bookings,
                SUM(b.num_people * t.price) as total_revenue
            FROM bookings b
            LEFT JOIN tours t ON b.tour_id = t.id
            WHERE b.status IN (2, 3)
        ");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $result['total_expense'] = 0;
        $result['total_profit'] = $result['total_revenue'] - $result['total_expense'];
        return $result;
    }

    // Lấy báo cáo chi tiết theo từng tour
    public static function getByTour()
    {
        $db = getDB();
        $stmt = $db->query("
            SELECT 
                t.id,
                t.name as tour_name,
                COUNT(DISTINCT b.id) as booking_count,
                SUM(b.num_people) as customer_count,
                SUM(b.num_people * t.price) as revenue,
                0 as expense,
                SUM(b.num_people * t.price) as profit
            FROM tours t
            LEFT JOIN bookings b ON t.id = b.tour_id AND b.status IN (2, 3)
            GROUP BY t.id, t.name
            HAVING booking_count > 0
            ORDER BY profit DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy top 5 tour bán chạy nhất
    public static function getTopSelling()
    {
        $db = getDB();
        $stmt = $db->query("
            SELECT 
                t.name as tour_name,
                COUNT(b.id) as booking_count
            FROM tours t
            LEFT JOIN bookings b ON t.id = b.tour_id AND b.status IN (2, 3)
            GROUP BY t.id, t.name
            HAVING booking_count > 0
            ORDER BY booking_count DESC
            LIMIT 5
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy top 5 tour có lợi nhuận cao nhất
    public static function getTopProfit()
    {
        $db = getDB();
        $stmt = $db->query("
            SELECT 
                t.name as tour_name,
                SUM(b.num_people * t.price) as profit
            FROM tours t
            LEFT JOIN bookings b ON t.id = b.tour_id AND b.status IN (2, 3)
            GROUP BY t.id, t.name
            HAVING profit > 0
            ORDER BY profit DESC
            LIMIT 5
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
