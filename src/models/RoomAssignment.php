<?php

class RoomAssignment
{
    public static function getByBooking($bookingId)
    {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT ra.*, c.name as customer_name
            FROM room_assignments ra
            LEFT JOIN customers c ON ra.customer_id = c.id
            WHERE ra.booking_id = ?
        ");
        $stmt->execute([$bookingId]);
        return $stmt->fetchAll();
    }

    public static function create($data)
    {
        $db = getDB();
        $stmt = $db->prepare("
            INSERT INTO room_assignments 
            (booking_id, customer_id, hotel_name, room_number, room_type, check_in_date, check_out_date, notes) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['booking_id'],
            $data['customer_id'] ?? null,
            $data['hotel_name'] ?? null,
            $data['room_number'] ?? null,
            $data['room_type'] ?? null,
            $data['check_in_date'] ?? null,
            $data['check_out_date'] ?? null,
            $data['notes'] ?? null
        ]);
    }

    public static function delete($id)
    {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM room_assignments WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
