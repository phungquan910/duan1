<?php

class TourExpense
{
    public static function getByBooking($bookingId)
    {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM tour_expenses WHERE booking_id = ? ORDER BY expense_date DESC");
        $stmt->execute([$bookingId]);
        return $stmt->fetchAll();
    }

    public static function create($data)
    {
        $db = getDB();
        $stmt = $db->prepare("
            INSERT INTO tour_expenses 
            (booking_id, expense_type, amount, description, expense_date, created_by) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['booking_id'],
            $data['expense_type'],
            $data['amount'],
            $data['description'] ?? null,
            $data['expense_date'] ?? date('Y-m-d'),
            $data['created_by'] ?? 1
        ]);
    }

    public static function getTotalByBooking($bookingId)
    {
        $db = getDB();
        $stmt = $db->prepare("SELECT SUM(amount) as total FROM tour_expenses WHERE booking_id = ?");
        $stmt->execute([$bookingId]);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    public static function delete($id)
    {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM tour_expenses WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
