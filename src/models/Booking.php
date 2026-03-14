<?php
// Model Booking đại diện cho thực thể đặt tour
class Booking
{
    // Làm sạch dữ liệu: chuyển chuỗi rỗng thành null
    private static function clean($value) {
        return ($value === '' || $value === null) ? null : $value;
    }
    // Lấy tất cả booking kèm thông tin tour và danh sách khách
    public static function getAll()
    {
        $db = getDB();
        $stmt = $db->query("
            SELECT b.*, t.name as tour_name, t.price, ts.name as status_name
            FROM bookings b
            LEFT JOIN tours t ON b.tour_id = t.id
            LEFT JOIN tour_statuses ts ON b.status = ts.id
            ORDER BY b.created_at DESC
        ");
        $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

         // Lấy danh sách khách hàng cho mỗi booking
        foreach ($bookings as &$booking) {
            $stmt = $db->prepare("SELECT name FROM customers WHERE booking_id = ?");
            $stmt->execute([$booking['id']]);
            $customers = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $booking['all_customers'] = implode(', ', $customers);
            $booking['customer_count'] = count($customers);
        }
       return $bookings;
    }
    // Lấy thông tin booking theo ID
    public static function getById($id)
    {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT b.*, t.name as tour_name, t.price
            FROM bookings b
            LEFT JOIN tours t ON b.tour_id = t.id
            WHERE b.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    // Tạo booking mới
    public static function create($data)
    {
        $db = getDB();
        $stmt = $db->prepare("
            INSERT INTO bookings (tour_id, customer_name, customer_phone, customer_email, 
            booking_type, num_people, start_date, end_date, 
            assigned_guide_id, special_requirements, status, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, NOW())
        ");
        $stmt->execute([
            $data['tour_id'],
            $data['customer_name'],
            self::clean($data['customer_phone'] ?? ''),
            self::clean($data['customer_email'] ?? ''),
            $data['booking_type'],
            $data['num_people'],
            $data['start_date'],
            self::clean($data['end_date'] ?? ''),
            self::clean($data['assigned_guide_id'] ?? ''),
            self::clean($data['special_requirements'] ?? '')
        ]);
        return $db->lastInsertId();
    }
    // Cập nhật trạng thái booking
    public static function updateStatus($id, $status)
    {
        $db = getDB();
        $stmt = $db->prepare("UPDATE bookings SET status = ?, updated_at = NOW() WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }
    // Xóa booking và các dữ liệu liên quan
    public static function delete($id)
    {
        $db = getDB();
        $db->prepare("DELETE FROM booking_status_logs WHERE booking_id = ?")->execute([$id]);
        $stmt = $db->prepare("DELETE FROM bookings WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>