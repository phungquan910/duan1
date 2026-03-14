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

ob_start();
?>

<div class="row">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><?= htmlspecialchars($tour['name']) ?></h3>
        <div class="card-tools">
          <span class="badge <?= $tour['status'] == 1 ? 'bg-success' : 'bg-warning' ?> fs-6">
            <?= $tour['status'] == 1 ? 'Hoạt động' : 'Tạm dừng' ?>
          </span>
        </div>
      </div>
      <div class="card-body">
        <div class="mb-4">
          <table class="table table-borderless">
            <tr>
              <td><strong>Giá tour:</strong></td>
              <td class="text-danger fs-5"><strong><?= number_format($tour['price']) ?> VNĐ</strong></td>
            </tr>
            <tr>
              <td><strong>Mã tour:</strong></td>
              <td>#<?= $tour['id'] ?></td>
            </tr>
            <tr>
              <td><strong>Danh mục:</strong></td>
              <td><?= htmlspecialchars($tour['category_id'] == 1 ? 'Tour trong nước' : 'Tour quốc tế') ?></td>
            </tr>
          </table>
        </div>

        <div class="mb-4">
          <h5><i class="bi bi-info-circle text-primary"></i> Mô tả Tour</h5>
          <p class="text-muted"><?= nl2br(htmlspecialchars($tour['description'] ?? 'Chưa có mô tả')) ?></p>
        </div>

        <div class="mb-4">
          <h5><i class="bi bi-calendar-check text-success"></i> Lịch trình</h5>
          <div class="bg-light p-3 rounded">
            <?php
            if ($tour['schedule']) {
                $schedule = json_decode($tour['schedule'], true);
                if ($schedule && isset($schedule['days'])) {
                    foreach ($schedule['days'] as $index => $day) {
                        echo '<strong>Ngày ' . ($index + 1) . ':</strong> ' . date('d/m/Y', strtotime($day['date'])) . '<br>';
                        if (isset($day['activities'])) {
                            foreach ($day['activities'] as $activity) {
                                echo '- ' . htmlspecialchars($activity) . '<br>';
                            }
                        }
                        echo '<br>';
                    }
                } else {
                    echo 'Chưa có lịch trình chi tiết';
                }
            } else {
                echo 'Chưa có lịch trình chi tiết';
            }
            ?>
          </div>
        </div>
      </div>
      <div class="card-footer">
        <a href="<?= BASE_URL ?>?act=admin/tours/edit&id=<?= $tour['id'] ?>" class="btn btn-warning">
          <i class="bi bi-pencil"></i> Chỉnh sửa
        </a>
        <a href="<?= BASE_URL ?>?act=admin/tours" class="btn btn-secondary">
          <i class="bi bi-arrow-left"></i> Quay lại danh sách
        </a>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title">Thông tin nhanh</h5>
      </div>
      <div class="card-body">
        <div class="d-flex justify-content-between mb-2">
          <span>Trạng thái:</span>
          <span class="badge <?= $tour['status'] == 1 ? 'bg-success' : 'bg-warning' ?>">
            <?= $tour['status'] == 1 ? 'Hoạt động' : 'Tạm dừng' ?>
          </span>
        </div>
        <div class="d-flex justify-content-between mb-2">
          <span>Mã tour:</span>
          <span class="fw-bold">#<?= $tour['id'] ?></span>
        </div>
        <hr>
        <div class="text-center">
          <div class="fs-4 text-danger fw-bold"><?= number_format($tour['price']) ?> VNĐ</div>
          <small class="text-muted">Giá cho 1 khách</small>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
$content = ob_get_clean();

view('layouts.AdminLayout', [
    'title' => $title ?? 'Chi tiết Tour',
    'pageTitle' => $pageTitle ?? 'Chi tiết Tour: ' . $tour['name'],
    'content' => $content,
    'breadcrumb' => [
        ['label' => 'Quản lý Tour', 'url' => BASE_URL . '?act=admin/tours'],
        ['label' => 'Chi tiết Tour', 'url' => BASE_URL . '?act=admin/tours/view&id=' . $tour['id'], 'active' => true],
    ],
]);
?>
