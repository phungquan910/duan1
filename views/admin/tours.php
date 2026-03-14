<?php
if (isset($_GET['delete'])) {
    Tour::delete($_GET['delete']);
    header('Location: ' . BASE_URL . '?act=admin/tours');
    exit;
}

$tours = Tour::getAll();

ob_start();
?>

<div class="row mb-3">
  <div class="col-12">
    <a href="<?= BASE_URL ?>?act=admin/tours/add" class="btn btn-primary">
      <i class="bi bi-plus"></i> Thêm Tour mới
    </a>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Danh sách Tour (<?= count($tours) ?>)</h3>
      </div>
      <div class="card-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Tên Tour</th>
              <th>Danh mục</th>
              <th>Giá</th>
              <th>Trạng thái</th>
              <th>Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($tours as $t): ?>
              <tr>
                <td>#<?= $t['id'] ?></td>
                <td><?= htmlspecialchars($t['name']) ?></td>
                <td><?= htmlspecialchars($t['category_name'] ?? 'N/A') ?></td>
                <td><?= number_format($t['price']) ?> VNĐ</td>
                <td>
                  <span class="badge <?= $t['status'] == 1 ? 'bg-success' : 'bg-warning' ?>">
                    <?= $t['status'] == 1 ? 'Hoạt động' : 'Tạm dừng' ?>
                  </span>
                </td>
                <td>
                  <a href="<?= BASE_URL ?>?act=admin/tours/view&id=<?= $t['id'] ?>" class="btn btn-info btn-sm">
                    <i class="bi bi-eye"></i>
                  </a>
                  <a href="<?= BASE_URL ?>?act=admin/tours/edit&id=<?= $t['id'] ?>" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <button class="btn btn-danger btn-sm" onclick="deleteTour(<?= $t['id'] ?>)">
                    <i class="bi bi-trash"></i>
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
function deleteTour(id) {
  if (confirm('Bạn có chắc muốn xóa tour này?')) {
    window.location.href = '?act=admin/tours&delete=' + id;
  }
}
</script>

<?php
$content = ob_get_clean();

view('layouts.AdminLayout', [
    'title' => 'Quản lý Tour',
    'pageTitle' => 'Quản lý Tour',
    'content' => $content,
    'breadcrumb' => [
        ['label' => 'Quản lý Tour', 'url' => BASE_URL . '?act=admin/tours', 'active' => true],
    ],
]);
?>
