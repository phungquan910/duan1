<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => $_POST['name'],
        'description' => $_POST['description'],
        'category_id' => $_POST['category_id'],
        'price' => $_POST['price'],
        'schedule' => $_POST['schedule'] ?? null,
        'images' => $_POST['images'] ?? null,
        'prices' => $_POST['prices'] ?? null,
        'policies' => $_POST['policies'] ?? null,
        'suppliers' => $_POST['suppliers'] ?? null,
        'status' => $_POST['status']
    ];
    
    Tour::create($data);
    header('Location: ' . BASE_URL . '?act=admin/tours');
    exit;
}

$categories = Category::getAll();

ob_start();
?>

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Thêm Tour mới</h3>
      </div>
      <form method="post">
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label">Tên Tour *</label>
            <input type="text" class="form-control" name="name" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea class="form-control" name="description" rows="2"></textarea>
          </div>
          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label">Danh mục *</label>
              <select class="form-select" name="category_id" required>
                <option value="">-- Chọn danh mục --</option>
                <?php foreach ($categories as $c): ?>
                  <option value="<?= $c->id ?>"><?= htmlspecialchars($c->name) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Giá Tour (VNĐ) *</label>
              <input type="number" class="form-control" name="price" required>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Trạng thái</label>
              <select class="form-select" name="status">
                <option value="1">Hoạt động</option>
                <option value="0">Tạm dừng</option>
              </select>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-primary">Thêm Tour</button>
          <a href="<?= BASE_URL ?>?act=admin/tours" class="btn btn-secondary">Hủy</a>
        </div>
      </form>
    </div>
  </div>
</div>

<?php
$content = ob_get_clean();

view('layouts.AdminLayout', [
    'title' => 'Thêm Tour',
    'pageTitle' => 'Thêm Tour mới',
    'content' => $content,
    'breadcrumb' => [
        ['label' => 'Quản lý Tour', 'url' => BASE_URL . '?act=admin/tours'],
        ['label' => 'Thêm Tour', 'url' => BASE_URL . '?act=admin/tours/add', 'active' => true],
    ],
]);
?>
