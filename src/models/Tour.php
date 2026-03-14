<?php

// Model Tour đại diện cho thực thể tour du lịch
class Tour
{
    // Lấy tất cả các tour kèm thông tin danh mục
    public static function getAll()
    {
        $db = getDB();
        $stmt = $db->query("
            SELECT t.*, c.name as category_name
            FROM tours t
            LEFT JOIN categories c ON t.category_id = c.id
            ORDER BY t.id DESC
        ");
        return $stmt->fetchAll();
    }

    // Lấy thông tin tour theo ID
    public static function getById($id)
    {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM tours WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Tạo tour mới với dữ liệu được truyền vào
    public static function create($data)
    {
        $db = getDB();
        $stmt = $db->prepare("
            INSERT INTO tours 
            (name, description, category_id, schedule, images, prices, policies, suppliers, price, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['name'],
            $data['description'] ?? null,
            $data['category_id'],
            $data['schedule'] ?? null,
            $data['images'] ?? null,
            $data['prices'] ?? null,
            $data['policies'] ?? null,
            $data['suppliers'] ?? null,
            $data['price'] ?? 0,
            $data['status'] ?? 1
        ]);
    }

    // Cập nhật thông tin tour theo ID
    public static function update($id, $data)
    {
        $db = getDB();
        $stmt = $db->prepare("
            UPDATE tours 
            SET name = ?, description = ?, category_id = ?, schedule = ?, 
                images = ?, prices = ?, policies = ?, suppliers = ?, price = ?, status = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['name'],
            $data['description'] ?? null,
            $data['category_id'],
            $data['schedule'] ?? null,
            $data['images'] ?? null,
            $data['prices'] ?? null,
            $data['policies'] ?? null,
            $data['suppliers'] ?? null,
            $data['price'] ?? 0,
            $data['status'] ?? 1,
            $id
        ]);
    }

    // Xóa tour và tất cả dữ liệu liên quan (booking, customer)
    public static function delete($id)
    {
        $db = getDB();
        $db->prepare("DELETE FROM customers WHERE booking_id IN (SELECT id FROM bookings WHERE tour_id = ?)")->execute([$id]);
        $db->prepare("DELETE FROM booking_status_logs WHERE booking_id IN (SELECT id FROM bookings WHERE tour_id = ?)")->execute([$id]);
        $db->prepare("DELETE FROM bookings WHERE tour_id = ?")->execute([$id]);
        $stmt = $db->prepare("DELETE FROM tours WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
