<?php
// Controller chịu trách nhiệm xử lý logic cho các trang cơ bản
class HomeController
{
    // Trang welcome - hiển thị cho người chưa đăng nhập
    // Nếu đã đăng nhập thì redirect về trang home
    public function welcome(): void
    {
        // Nếu đã đăng nhập thì redirect về trang home
        if (isLoggedIn()) {
            header('Location: ' . BASE_URL . 'home');
            exit;
        }

        // Hiển thị view welcome
        view('welcome', [
            'title' => 'Chào mừng - Website Quản Lý Tour',
        ]);
    }

    // Trang home - chỉ dành cho người đã đăng nhập
    // Nếu chưa đăng nhập thì redirect về trang welcome
    public function home(): void
    {
        if (!isLoggedIn()) {
            header('Location: ' . BASE_URL . 'welcome');
            exit;
        }

        $currentUser = getCurrentUser();
        
        if (isGuide()) {
            header('Location: ' . BASE_URL . 'guide/dashboard');
            exit;
        }

        view('home', [
            'title' => 'Trang chủ - Website Quản Lý Tour',
            'user' => $currentUser,
        ]);
    }

    // Trang hiển thị khi route không tồn tại
    public function notFound(): void
    {
        http_response_code(404);
        // Hiển thị view not_found với dữ liệu title
        view('not_found', [
            'title' => 'Không tìm thấy trang',
        ]);
    }
}
