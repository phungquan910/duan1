<?php
ob_start();
?>

<div class="row mb-3">
  <div class="col-12">
    <a href="<?= BASE_URL ?>admin/bookings/add" class="btn btn-primary">
      <i class="bi bi-plus-circle"></i> Tạo Booking mới
    </a>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Danh sách Booking</h3>
      </div>
      <div class="card-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Khách hàng</th>
              <th>Tour</th>
              <th>Loại</th>
              <th>Số người</th>
              <th>Ngày khởi hành</th>
              <th>Trạng thái</th>
              <th>Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($bookings as $b): ?>
              <tr>
                <td>#<?= $b['id'] ?></td>
                <td>
                  <?php if (!empty($b['all_customers'])): ?>
                    <strong><?= htmlspecialchars($b['all_customers']) ?></strong>
                  <?php else: ?>
                    <strong><?= htmlspecialchars($b['customer_name'] ?? 'Chưa có khách') ?></strong><br>
                    <small><?= htmlspecialchars($b['customer_phone'] ?? '') ?></small>
                  <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($b['tour_name']) ?></td>
                <td>
                  <span class="badge <?= $b['booking_type'] == 'Đoàn' ? 'bg-info' : 'bg-secondary' ?>">
                    <?= $b['booking_type'] ?>
                  </span>
                </td>
                <td>
                  <?= $b['num_people'] ?>
                  <?php if (!empty($b['all_customers'])): ?>
                    <br><small class="text-muted">(<?= $b['customer_count'] ?> đã đăng ký)</small>
                  <?php endif; ?>
                </td>
                <td><?= date('d/m/Y', strtotime($b['start_date'])) ?></td>
                <td>
                  <span class="badge 
                    <?= $b['status'] == 3 ? 'bg-success' : 
                       ($b['status'] == 2 ? 'bg-primary' : 
                       ($b['status'] == 4 ? 'bg-danger' : 'bg-warning')) ?>">
                    <?= $b['status_name'] ?>
                  </span>
                </td>
                <td>
                  <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewModal<?= $b['id'] ?>">
                    <i class="bi bi-eye"></i>
                  </button>
                  <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#statusModal<?= $b['id'] ?>">
                    <i class="bi bi-pencil"></i>
                  </button>
                  <a href="<?= BASE_URL ?>admin/bookings?delete=<?= $b['id'] ?>" 
                     class="btn btn-sm btn-danger" 
                     onclick="return confirm('Xóa booking này?')">
                    <i class="bi bi-trash"></i>
                  </a>
                </td>
              </tr>

              <!-- View Modal -->
              <div class="modal fade" id="viewModal<?= $b['id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Chi tiết Booking #<?= $b['id'] ?></h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                      <p><strong>Khách hàng:</strong> <?= htmlspecialchars($b['customer_name'] ?? '') ?></p>
                      <p><strong>Điện thoại:</strong> <?= htmlspecialchars($b['customer_phone'] ?? '') ?></p>
                      <p><strong>Email:</strong> <?= htmlspecialchars($b['customer_email'] ?? '') ?></p>
                      <p><strong>Tour:</strong> <?= htmlspecialchars($b['tour_name']) ?></p>
                      <p><strong>Loại booking:</strong> <?= $b['booking_type'] ?></p>
                      <p><strong>Số người:</strong> <?= $b['num_people'] ?></p>
                      <p><strong>Ngày khởi hành:</strong> <?= date('d/m/Y', strtotime($b['start_date'])) ?></p>
                      <p><strong>Yêu cầu đặc biệt:</strong> <?= htmlspecialchars($b['special_requirements'] ?? 'Không có') ?></p>
                      <p><strong>Trạng thái:</strong> <span class="badge bg-info"><?= $b['status_name'] ?></span></p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Status Modal -->
              <div class="modal fade" id="statusModal<?= $b['id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <form method="post">
                      <div class="modal-header">
                        <h5 class="modal-title">Cập nhật trạng thái</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body">
                        <input type="hidden" name="booking_id" value="<?= $b['id'] ?>">
                        <div class="mb-3">
                          <label class="form-label">Trạng thái</label>
                          <select class="form-select" name="status" required>
                            <option value="1" <?= $b['status'] == 1 ? 'selected' : '' ?>>Chờ xác nhận</option>
                            <option value="2" <?= $b['status'] == 2 ? 'selected' : '' ?>>Đã cọc</option>
                            <option value="3" <?= $b['status'] == 3 ? 'selected' : '' ?>>Hoàn tất</option>
                            <option value="4" <?= $b['status'] == 4 ? 'selected' : '' ?>>Hủy</option>
                          </select>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" name="update_status" class="btn btn-primary">Cập nhật</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php
$content = ob_get_clean();

view('layouts.AdminLayout', [
    'title' => 'Quản lý Booking',
    'pageTitle' => 'Quản lý Booking',
    'content' => $content,
    'breadcrumb' => [
        ['label' => 'Booking', 'url' => BASE_URL . 'admin/bookings', 'active' => true],
    ],
]);
?>
