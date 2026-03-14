<?php

// Controller xử lý các chức năng quản trị hệ thống
class AdminController
{
    // Hiển thị danh sách tất cả các tour
    public function tours()
    {
        requireLogin();
        view('admin.tours', [
            'title' => 'Quản lý Tour',
            'pageTitle' => 'Quản lý Tour'
        ]);
    }

    // Hiển thị form thêm tour mới
    public function addTour()
    {
        view('admin.add-tour', [
            'title' => 'Thêm Tour',
            'pageTitle' => 'Thêm Tour mới'
        ]);
    }

    // Hiển thị form chỉnh sửa thông tin tour
    public function editTour()
    {
        view('admin.edit-tour', [
            'title' => 'Chỉnh sửa Tour',
            'pageTitle' => 'Chỉnh sửa Tour'
        ]);
    }

    // Hiển thị chi tiết thông tin của một tour
    public function viewTour()
    {
        requireLogin();
        view('admin.view-tour', [
            'title' => 'Chi tiết Tour',
            'pageTitle' => 'Chi tiết Tour'
        ]);
    }

    // Quản lý danh sách booking (đặt tour)
    // Xử lý cập nhật trạng thái và xóa booking
    public function bookings()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
            Booking::updateStatus($_POST['booking_id'], $_POST['status']);
            header('Location: ' . BASE_URL . 'admin/bookings');
            exit;
        }

        if (isset($_GET['delete'])) {
            Booking::delete($_GET['delete']);
            header('Location: ' . BASE_URL . 'admin/bookings');
            exit;
        }

        $bookings = class_exists('Booking') ? Booking::getAll() : [];
        view('admin.bookings.index', [
            'title' => 'Quản lý Booking',
            'pageTitle' => 'Quản lý Booking',
            'bookings' => $bookings
        ]);
    }

    // Thêm booking mới và tự động tạo customer
    public function addBooking()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bookingId = Booking::create($_POST);
            
            // Tự động tạo customer từ thông tin booking
            if ($bookingId && !empty($_POST['customer_name'])) {
                Customer::create([
                    'booking_id' => $bookingId,
                    'name' => $_POST['customer_name'],
                    'phone' => $_POST['customer_phone'] ?? '',
                    'email' => $_POST['customer_email'] ?? '',
                    'payment_status' => 'Chưa thanh toán'
                ]);
            }
            
            header('Location: ' . BASE_URL . 'admin/bookings');
            exit;
        }

        $tours = class_exists('Tour') ? Tour::getAll() : [];
        $guides = class_exists('Guide') ? Guide::getAll() : [];
        view('admin.bookings.add', [
            'title' => 'Thêm Booking',
            'pageTitle' => 'Tạo Booking mới',
            'tours' => $tours,
            'guides' => $guides
        ]);
    }

    // Hiển thị danh sách khách hàng theo booking cụ thể
    public function bookingCustomers()
    {
        $bookingId = $_GET['booking_id'] ?? null;
        $customers = $bookingId ? Customer::getByBookingId($bookingId) : [];
        $booking = $bookingId ? Booking::getById($bookingId) : null;
        
        view('admin.bookings.customers', [
            'title' => 'Danh sách khách',
            'pageTitle' => 'Danh sách khách theo tour',
            'customers' => $customers,
            'booking' => $booking
        ]);
    }

    // Quản lý danh sách khách hàng
    // Xử lý cập nhật thông tin và xóa khách hàng
    public function customers()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_customer'])) {
            Customer::update($_POST['customer_id'], $_POST);
            header('Location: ' . BASE_URL . 'admin/customers');
            exit;
        }

        if (isset($_GET['delete'])) {
            Customer::delete($_GET['delete']);
            header('Location: ' . BASE_URL . 'admin/customers');
            exit;
        }

        $customers = class_exists('Customer') ? Customer::getAll() : [];
        $bookings = class_exists('Booking') ? Booking::getAll() : [];
        view('admin.customers.index', [
            'title' => 'Quản lý Khách hàng',
            'pageTitle' => 'Quản lý Khách hàng',
            'customers' => $customers,
            'bookings' => $bookings
        ]);
    }

    // Thêm khách hàng mới vào hệ thống
    public function addCustomer()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Customer::create($_POST);
            $bookingId = $_POST['booking_id'] ?? null;
            $redirect = $bookingId ? 'admin/bookings/customers?booking_id=' . $bookingId : 'admin/customers';
            header('Location: ' . BASE_URL . $redirect);
            exit;
        }

        $bookingId = $_GET['booking_id'] ?? null;
        $booking = $bookingId ? Booking::getById($bookingId) : null;
        $bookings = Booking::getAll();
        
        view('admin.customers.add', [
            'title' => 'Thêm khách hàng',
            'pageTitle' => 'Thêm khách hàng mới',
            'booking' => $booking,
            'bookings' => $bookings
        ]);
    }

    // Cập nhật trạng thái check-in của khách hàng
    public function customerCheckIn()
    {
        if (isset($_GET['id']) && isset($_GET['status'])) {
            Customer::updateCheckIn($_GET['id'], $_GET['status']);
        }
        header('Location: ' . $_SERVER['HTTP_REFERER'] ?? BASE_URL . 'admin/customers');
        exit;
    }

    // Phân phòng cho khách hàng
    public function assignRoom()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Customer::updateRoom($_POST['customer_id'], $_POST['room_number']);
        }
        header('Location: ' . $_SERVER['HTTP_REFERER'] ?? BASE_URL . 'admin/customers');
        exit;
    }

    // Quản lý danh sách hướng dẫn viên
    // Thêm HDV mới và tự động tạo tài khoản user
    public function guides()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_guide'])) {
            $data = $_POST;
            foreach ($data as $k => $v) {
                if ($v === '') $data[$k] = null;
            }
            $existingUser = User::findByEmail($data['email']);
            if ($existingUser) {
                $userId = $existingUser->id;
                if (Guide::existsByUserId($userId)) {
                    header('Location: ' . BASE_URL . 'admin/guides');
                    exit;
                }
            } else {
                $userId = User::create(['name' => $data['name'], 'email' => $data['email'], 'password' => password_hash('123456', PASSWORD_DEFAULT), 'role' => 'guide']);
            }
            $data['user_id'] = $userId;
            Guide::create($data);
            header('Location: ' . BASE_URL . 'admin/guides');
            exit;
        }

        if (isset($_GET['delete'])) {
            Guide::delete($_GET['delete']);
            header('Location: ' . BASE_URL . 'admin/guides');
            exit;
        }

        $guides = class_exists('Guide') ? Guide::getAll() : [];
        $users = User::getAll();
        
        view('admin.guides.index', [
            'title' => 'Quản lý HDV',
            'pageTitle' => 'Quản lý Hướng dẫn viên',
            'guides' => $guides,
            'users' => $users
        ]);
    }

    // Hiển thị báo cáo doanh thu và chi phí
    public function reports()
    {
        requireLogin();
        $summary = Report::getSummary();
        $tourReports = Report::getByTour();
        $topSelling = Report::getTopSelling();
        $topProfit = Report::getTopProfit();
        
        view('admin.reports', [
            'title' => 'Báo cáo',
            'pageTitle' => 'Báo cáo Doanh thu & Chi phí',
            'summary' => $summary,
            'tourReports' => $tourReports,
            'topSelling' => $topSelling,
            'topProfit' => $topProfit
        ]);
    }

    // Quản lý lịch khởi hành của các tour
    public function schedules()
    {
        requireLogin();
        view('admin.departure-schedules', [
            'title' => 'Lịch khởi hành',
            'pageTitle' => 'Quản lý Lịch khởi hành'
        ]);
    }

    // Thêm lịch khởi hành mới
    public function addSchedule()
    {
        requireLogin();
        view('admin.departure-schedules', [
            'title' => 'Thêm lịch khởi hành',
            'pageTitle' => 'Thêm lịch khởi hành mới'
        ]);
    }

    // Chỉnh sửa lịch khởi hành
    public function editSchedule()
    {
        requireLogin();
        view('admin.departure-schedules', [
            'title' => 'Chỉnh sửa lịch khởi hành',
            'pageTitle' => 'Chỉnh sửa lịch khởi hành'
        ]);
    }

    // Quản lý phân phòng khách sạn
    public function rooms()
    {
        view('admin.room-assignments', [
            'title' => 'Phân phòng',
            'pageTitle' => 'Quản lý Phân phòng khách sạn'
        ]);
    }

    // Quản lý chi phí của các tour
    public function expenses()
    {
        view('admin.tour-expenses', [
            'title' => 'Chi phí Tour',
            'pageTitle' => 'Quản lý Chi phí Tour'
        ]);
    }

    // Báo cáo doanh thu và lợi nhuận
    public function revenue()
    {
        view('admin.revenue', [
            'title' => 'Báo cáo Doanh thu',
            'pageTitle' => 'Báo cáo Doanh thu & Lợi nhuận'
        ]);
    }

    // In danh sách khách trong đoàn
    public function printGuestList()
    {
        view('admin.print-guest-list', [
            'title' => 'In danh sách đoàn',
            'pageTitle' => 'In danh sách đoàn'
        ]);
    }
}
