<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Nạp cấu hình chung của ứng dụng
$config = require __DIR__ . '/config/config.php';

// Nạp các file chứa hàm trợ giúp
require_once __DIR__ . '/src/helpers/helpers.php'; // Helper chứa các hàm trợ giúp (hàm xử lý view, block, asset, session, ...)
require_once __DIR__ . '/src/helpers/database.php'; // Helper kết nối database(kết nối với cơ sở dữ liệu)

// Nạp các file chứa model
require_once __DIR__ . '/src/models/User.php';
require_once __DIR__ . '/src/models/Category.php';
require_once __DIR__ . '/src/models/DepartureSchedule.php';
require_once __DIR__ . '/src/models/RoomAssignment.php';
require_once __DIR__ . '/src/models/TourExpense.php';

if (file_exists(__DIR__ . '/src/models/Tour.php')) require_once __DIR__ . '/src/models/Tour.php';
if (file_exists(__DIR__ . '/src/models/Booking.php')) require_once __DIR__ . '/src/models/Booking.php';
if (file_exists(__DIR__ . '/src/models/Guide.php')) require_once __DIR__ . '/src/models/Guide.php';
if (file_exists(__DIR__ . '/src/models/Customer.php')) require_once __DIR__ . '/src/models/Customer.php';
if (file_exists(__DIR__ . '/src/models/Report.php')) require_once __DIR__ . '/src/models/Report.php';

// Nạp các file chứa controller
require_once __DIR__ . '/src/controllers/HomeController.php';
require_once __DIR__ . '/src/controllers/AuthController.php';
require_once __DIR__ . '/src/controllers/CategoryController.php';
require_once __DIR__ . '/src/controllers/AdminController.php';
require_once __DIR__ . '/src/controllers/GuideController.php';

// Khởi tạo các controller
$homeController = new HomeController();
$authController = new AuthController();
$categoryController = new CategoryController();
$adminController = new AdminController();
$guideController = new GuideController();

// Xác định route dựa trên tham số act (mặc định là trang chủ '/')
$act = $_GET['act'] ?? '/';

// Match đảm bảo chỉ một action tương ứng được gọi
match ($act) {
    // Trang welcome (cho người chưa đăng nhập) - mặc định khi truy cập '/'
    '/', 'welcome' => $homeController->welcome(),

    // Trang home (cho người đã đăng nhập)
    'home' => $homeController->home(),

    // Đường dẫn đăng nhập, đăng xuất
    'login' => $authController->login(),
    'check-login' => $authController->checkLogin(),
    'logout' => $authController->logout(),

    // Đường dẫn quản lý danh mục tour
    'categories' => $categoryController->index(),

    // Admin routes
    'admin/dashboard' => $homeController->home(),
    'admin/tours' => $adminController->tours(),
    'admin/tours/add' => $adminController->addTour(),
    'admin/tours/edit' => $adminController->editTour(),
    'admin/tours/view' => $adminController->viewTour(),
    'admin/bookings' => $adminController->bookings(),
    'admin/bookings/add' => $adminController->addBooking(),
    'admin/bookings/customers' => $adminController->bookingCustomers(),
    'admin/customers' => $adminController->customers(),
    'admin/customers/add' => $adminController->addCustomer(),
    'admin/guides' => $adminController->guides(),
    'admin/reports' => $adminController->reports(),
    'admin/schedules' => $adminController->schedules(),
    'admin/schedules/add' => $adminController->addSchedule(),
    'admin/schedules/edit' => $adminController->editSchedule(),
    'admin/rooms' => $adminController->rooms(),
    'admin/expenses' => $adminController->expenses(),
    'admin/revenue' => $adminController->revenue(),
    'admin/customers/checkin' => $adminController->customerCheckIn(),
    'admin/customers/assign-room' => $adminController->assignRoom(),
    'admin/print-guest-list' => $adminController->printGuestList(),

    // Guide routes
    'guide/dashboard' => $guideController->dashboard(),
    'guide/schedule' => $guideController->schedule(),
    'guide/customers' => $guideController->customers(),
    'guide/diary' => $guideController->diary(),
    'guide/checkin' => $guideController->checkin(),
    'guide/requirements' => $guideController->requirements(),
    'guide/update-requirement' => $guideController->updateRequirement(),
    'guide/save-diary' => $guideController->saveDiary(),

    // Đường dẫn không tồn tại
    default => $homeController->notFound(),
};
