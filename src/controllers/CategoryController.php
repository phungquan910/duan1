<?php

// Controller xử lý các chức năng quản lý danh mục tour
class CategoryController 
{
    // Hiển thị danh sách danh mục với chức năng tìm kiếm và lọc
    public function index()
    {
        requireLogin();

        // Lay du lieu tu form 
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $status = isset($_GET['status']) && $_GET['status'] != '' ? $_GET['status'] : null;

        // Lay danh muc voi bo loc

        $categories = Category::all($status, $keyword);

        // Truyen du lieu sang view
        view('admin.categories', [
            'title' => "Quản lý danh mục",
            'categories' => $categories,
            'currentKeyword' => $keyword,
            'currentStatus' => $status,
        ]);

  
    }
}