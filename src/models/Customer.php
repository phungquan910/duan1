<?php
// Model Customer đại diện cho thực thể khách hàng
class Customer
{
    // Làm sạch dữ liệu: chuyển chuỗi rỗng thành null
    private static function clean($value) {
        return ($value === '' || $value === null) ? null : $value;
    }
    // Lấy tất cả khách hàng kèm thông tin booking và tour
    public static function getAll()
    {
        $db = getDB();
        $stmt = $db->query("
            SELECT c.*, b.id as booking_id, t.name as tour_name, b.start_date
            FROM customers c
            LEFT JOIN bookings b ON c.booking_id = b.id
            LEFT JOIN tours t ON b.tour_id = t.id
            ORDER BY c.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Lấy danh sách khách hàng theo booking ID
    public static function getByBookingId($bookingId)
    {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM customers WHERE booking_id = ?");
        $stmt->execute([$bookingId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Tạo khách hàng mới
    public static function create($data)
    {
        $db = getDB();
        $stmt = $db->prepare("
            INSERT INTO customers (booking_id, name, phone, email, gender, birth_year, 
            id_number, address, payment_status, special_requirements, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([
            self::clean($data['booking_id'] ?? ''),
            $data['name'],
            self::clean($data['phone'] ?? ''),
            self::clean($data['email'] ?? ''),
            self::clean($data['gender'] ?? ''),
            self::clean($data['birth_year'] ?? ''),
            self::clean($data['id_number'] ?? ''),
            self::clean($data['address'] ?? ''),
            $data['payment_status'] ?? 'Chưa thanh toán',
            self::clean($data['special_requirements'] ?? '')
        ]);
        return $db->lastInsertId();
    }
    // Cập nhật thông tin khách hàng
    public static function update($id, $data)
    {
        $db = getDB();
        $stmt = $db->prepare("
            UPDATE customers 
            SET booking_id = ?, name = ?, phone = ?, email = ?, gender = ?, birth_year = ?, 
                id_number = ?, address = ?, payment_status = ?, special_requirements = ?, updated_at = NOW()
            WHERE id = ?
        ");
        return $stmt->execute([
            self::clean($data['booking_id'] ?? ''),
            $data['name'],
            self::clean($data['phone'] ?? ''),
            self::clean($data['email'] ?? ''),
            self::clean($data['gender'] ?? ''),
            self::clean($data['birth_year'] ?? ''),
            self::clean($data['id_number'] ?? ''),
            self::clean($data['address'] ?? ''),
            $data['payment_status'] ?? 'Chưa thanh toán',
            self::clean($data['special_requirements'] ?? ''),
            $id
        ]);
    }
    // Cập nhật trạng thái check-in của khách
    public static function updateCheckIn($id, $status)
    {
        $db = getDB();
        $stmt = $db->prepare("UPDATE customers SET check_in_status = ?, updated_at = NOW() WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }
    // Cập nhật số phòng cho khách hàng
    public static function updateRoom($id, $roomNumber)
    {
        $db = getDB();
        $stmt = $db->prepare("UPDATE customers SET room_number = ?, updated_at = NOW() WHERE id = ?");
        return $stmt->execute([$roomNumber, $id]);
    }
    // Xóa khách hàng
    public static function delete($id)
    {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM customers WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>