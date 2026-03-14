<?php

// Controller xử lý các chức năng dành cho hướng dẫn viên
class GuideController
{
    // Hiển thị dashboard với thống kê tổng quan cho HDV
    public function dashboard()
    {
        requireLogin();
        $db = getDB();
        $guideId = $_SESSION['user_id'];
        
        $stmt = $db->prepare("SELECT COUNT(*) FROM customers c JOIN bookings b ON c.booking_id = b.id WHERE b.assigned_guide_id = ?");
        $stmt->execute([$guideId]);
        $totalCustomers = $stmt->fetchColumn();
        
        $stmt = $db->prepare("SELECT COUNT(*) FROM customers c JOIN bookings b ON c.booking_id = b.id WHERE b.assigned_guide_id = ? AND c.check_in_status = 'Đã check-in'");
        $stmt->execute([$guideId]);
        $checkedIn = $stmt->fetchColumn();
        
        $stmt = $db->prepare("SELECT COUNT(*) FROM customers c JOIN bookings b ON c.booking_id = b.id WHERE b.assigned_guide_id = ? AND c.special_requirements IS NOT NULL AND c.special_requirements != ''");
        $stmt->execute([$guideId]);
        $specialRequests = $stmt->fetchColumn();
        
        $stmt = $db->prepare("SELECT COUNT(*) FROM bookings WHERE assigned_guide_id = ?");
        $stmt->execute([$guideId]);
        $totalTours = $stmt->fetchColumn();
        
        view('guide.dashboard', [
            'title' => 'Dashboard HDV',
            'pageTitle' => 'Dashboard',
            'totalCustomers' => $totalCustomers,
            'checkedIn' => $checkedIn,
            'specialRequests' => $specialRequests,
            'totalTours' => $totalTours
        ]);
    }

    // Hiển thị lịch trình các tour được phân công
    public function schedule()
    {
        requireLogin();
        $db = getDB();
        $guideId = $_SESSION['user_id'];
        
        $stmt = $db->prepare("
            SELECT b.*, t.name as tour_name, t.schedule,
                   GROUP_CONCAT(c.name SEPARATOR ', ') as customer_names,
                   COUNT(c.id) as total_customers
            FROM bookings b
            JOIN tours t ON b.tour_id = t.id
            LEFT JOIN customers c ON c.booking_id = b.id
            WHERE b.assigned_guide_id = ?
            GROUP BY b.id, b.tour_id, b.customer_name, b.customer_phone, b.customer_email, 
                     b.booking_type, b.num_people, b.created_by, b.assigned_guide_id, 
                     b.status, b.start_date, b.end_date, b.special_requirements, 
                     b.schedule_detail, b.service_detail, b.diary, b.lists_file, 
                     b.notes, b.created_at, b.updated_at, t.name, t.schedule
            ORDER BY b.start_date DESC
        ");
        $stmt->execute([$guideId]);
        $stmt->execute([$guideId]);
        $assignments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        view('guide.schedule', [
            'title' => 'Lịch trình Tour',
            'pageTitle' => 'Lịch trình Tour của tôi',
            'assignments' => $assignments
        ]);
    }

    // Hiển thị danh sách khách hàng trong các tour được phân công
    public function customers()
    {
        requireLogin();
        $customers = Customer::getAll();
        view('guide.customers', [
            'title' => 'Danh sách khách',
            'pageTitle' => 'Danh sách khách trong đoàn',
            'customers' => $customers
        ]);
    }

    // Hiển thị nhật ký tour của HDV
    public function diary()
    {
        requireLogin();
        $diaries = [];
        $bookings = [];
        
        view('guide.diary', [
            'title' => 'Nhật ký Tour',
            'pageTitle' => 'Nhật ký Tour',
            'diaries' => $diaries,
            'bookings' => $bookings
        ]);
    }

    // Lưu nhật ký tour
    public function saveDiary()
    {
        requireLogin();
        header('Location: ' . BASE_URL . 'guide/diary');
        exit;
    }

    // Chức năng check-in và điểm danh khách hàng
    public function checkin()
    {
        requireLogin();
        if (isset($_GET['id']) && isset($_GET['status'])) {
            Customer::updateCheckIn($_GET['id'], $_GET['status']);
            header('Location: ' . BASE_URL . 'guide/checkin');
            exit;
        }
        $customers = Customer::getAll();
        view('guide.checkin', [
            'title' => 'Check-in khách',
            'pageTitle' => 'Check-in & Điểm danh khách',
            'customers' => $customers
        ]);
    }

    // Hiển thị và quản lý yêu cầu đặc biệt của khách
    public function requirements()
    {
        requireLogin();
        $customers = Customer::getAll();
        view('guide.requirements', [
            'title' => 'Yêu cầu đặc biệt',
            'pageTitle' => 'Yêu cầu đặc biệt của khách',
            'customers' => $customers
        ]);
    }

    // Cập nhật yêu cầu đặc biệt của khách hàng
    public function updateRequirement()
    {
        requireLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = getDB();
            $stmt = $db->prepare("UPDATE customers SET special_requirements = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$_POST['special_requirements'], $_POST['customer_id']]);
        }
        header('Location: ' . BASE_URL . 'guide/requirements');
        exit;
    }
}
