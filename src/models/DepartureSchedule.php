<?php

class DepartureSchedule
{
    public static function getAll()
    {
        $db = getDB();
        $stmt = $db->query("
            SELECT ds.*, t.name as tour_name, u.name as guide_name
            FROM departure_schedules ds
            LEFT JOIN tours t ON ds.tour_id = t.id
            LEFT JOIN users u ON ds.guide_id = u.id
            ORDER BY ds.departure_date DESC
        ");
        return $stmt->fetchAll();
    }

    public static function getById($id)
    {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM departure_schedules WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function create($data)
    {
        $db = getDB();
        $stmt = $db->prepare("
            INSERT INTO departure_schedules 
            (tour_id, departure_date, return_date, guide_id, driver_name, vehicle_info, hotel_info, max_guests, notes) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['tour_id'],
            $data['departure_date'],
            $data['return_date'] ?? null,
            $data['guide_id'] ?? null,
            $data['driver_name'] ?? null,
            $data['vehicle_info'] ?? null,
            $data['hotel_info'] ?? null,
            $data['max_guests'] ?? 30,
            $data['notes'] ?? null
        ]);
    }

    public static function update($id, $data)
    {
        $db = getDB();
        $stmt = $db->prepare("
            UPDATE departure_schedules 
            SET tour_id = ?, departure_date = ?, return_date = ?, guide_id = ?, 
                driver_name = ?, vehicle_info = ?, hotel_info = ?, max_guests = ?, 
                status = ?, notes = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['tour_id'],
            $data['departure_date'],
            $data['return_date'] ?? null,
            $data['guide_id'] ?? null,
            $data['driver_name'] ?? null,
            $data['vehicle_info'] ?? null,
            $data['hotel_info'] ?? null,
            $data['max_guests'] ?? 30,
            $data['status'] ?? 1,
            $data['notes'] ?? null,
            $id
        ]);
    }

    public static function delete($id)
    {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM departure_schedules WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
