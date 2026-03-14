<?php
if (!isset($_GET['id'])) {
    header('Location: ' . BASE_URL . '?act=admin/tours');
    exit;
}

$tour = Tour::getById($_GET['id']);
if (!$tour) {
    header('Location: ' . BASE_URL . '?act=admin/tours');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    Tour::update($tour['id'], [
        'name' => $_POST['name'],
        'description' => $_POST['description'],
        'category_id' => $_POST['category_id'],
        'price' => $_POST['price'],
        'status' => $_POST['status']
    ]);
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
        <h3 class="card-title">Chỉnh sửa Tour: <?= htmlspecialchars($tour['name']) ?></h3>
      </div>
      <form method="post">
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label">Tên Tour *</label>
            <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($tour['name']) ?>" required>
          </div>
          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label">Mô tả</label>
              <textarea class="form-control" name="description" rows="3"><?= htmlspecialchars($tour['description']) ?></textarea>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Giá Tour (VNĐ) *</label>
              <input type="number" class="form-control" name="price" value="<?= $tour['price'] ?>" required>
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Trạng thái</label>
              <select class="form-select" name="status">
                <option value="1" <?= $tour['status'] == 1 ? 'selected' : '' ?>>Hoạt động</option>
                <option value="0" <?= $tour['status'] == 0 ? 'selected' : '' ?>>Tạm dừng</option>
              </select>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Danh mục *</label>
            <select class="form-select" name="category_id" required>
              <?php foreach ($categories as $c): ?>
                <option value="<?= $c->id ?>" <?= $tour['category_id'] == $c->id ? 'selected' : '' ?>><?= htmlspecialchars($c->name) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-primary">Cập nhật Tour</button>
          <a href="<?= BASE_URL ?>?act=admin/tours" class="btn btn-secondary">Hủy</a>
        </div>
      </form>
    </div>
  </div>
</div>

<?php
$content = ob_get_clean();
view('layouts.AdminLayout', [
    'title' => 'Chỉnh sửa Tour',
    'pageTitle' => 'Chỉnh sửa Tour',
    'content' => $content,
    'breadcrumb' => [
        ['label' => 'Quản lý Tour', 'url' => BASE_URL . '?act=admin/tours'],
        ['label' => 'Chỉnh sửa Tour', 'url' => BASE_URL . '?act=admin/tours/edit&id=' . $tour['id'], 'active' => true],
    ],
]);
?>
