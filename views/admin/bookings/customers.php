<?php
ob_start();
?>

<div class="row mb-3">
  <div class="col-md-6">
    <h5>Tour: <?= htmlspecialchars($booking['tour_name'] ?? '') ?></h5>
    <p>Ngày khởi hành: <?= date('d/m/Y', strtotime($booking['start_date'])) ?></p>
  </div>
  <div class="col-md-6 text-end">
    <a href="<?= BASE_URL ?>admin/customers/add?booking_id=<?= $booking['id'] ?>" class="btn btn-primary">
      <i class="bi bi-plus-circle"></i> Thêm khách
    </a>
    <a href="<?= BASE_URL ?>admin/print-guest-list?booking_id=<?= $booking['id'] ?>" class="btn btn-success" target="_blank">
      <i class="bi bi-printer"></i> In danh sách
    </a>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Danh sách khách (<?= count($customers) ?> người)</h3>
      </div>
      <div class="card-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>STT</th>
              <th>Họ tên</th>
              <th>Giới tính</th>
              <th>Năm sinh</th>
              <th>CMND/CCCD</th>
              <th>Điện thoại</th>
              <th>Thanh toán</th>
              <th>Check-in</th>
              <th>Phòng</th>
              <th>Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($customers as $index => $c): ?>
              <tr>
                <td><?= $index + 1 ?></td>
                <td><?= htmlspecialchars($c['name']) ?></td>
                <td><?= $c['gender'] ?? '' ?></td>
                <td><?= $c['birth_year'] ?? '' ?></td>
                <td><?= htmlspecialchars($c['id_number'] ?? '') ?></td>
                <td><?= htmlspecialchars($c['phone'] ?? '') ?></td>
                <td>
                  <span class="badge <?= $c['payment_status'] == 'Hoàn tất' ? 'bg-success' : ($c['payment_status'] == 'Đã cọc' ? 'bg-primary' : 'bg-warning') ?>">
                    <?= $c['payment_status'] ?>
                  </span>
                </td>
                <td>
                  <?php if ($c['check_in_status'] == 'Đã check-in'): ?>
                    <span class="badge bg-success">Đã check-in</span>
                  <?php else: ?>
                    <a href="<?= BASE_URL ?>admin/customers/checkin?id=<?= $c['id'] ?>&status=Đã check-in" class="badge bg-secondary">
                      Check-in
                    </a>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if ($c['room_number']): ?>
                    <?= $c['room_number'] ?>
                  <?php else: ?>
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#roomModal<?= $c['id'] ?>">
                      Phân phòng
                    </button>
                  <?php endif; ?>
                </td>
                <td>
                  <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewModal<?= $c['id'] ?>">
                    <i class="bi bi-eye"></i>
                  </button>
                  <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $c['id'] ?>">
                    <i class="bi bi-pencil"></i>
                  </button>
                </td>
              </tr>

              <!-- View Modal -->
              <div class="modal fade" id="viewModal<?= $c['id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Chi tiết khách hàng</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                      <p><strong>Họ tên:</strong> <?= htmlspecialchars($c['name']) ?></p>
                      <p><strong>Giới tính:</strong> <?= $c['gender'] ?? 'Chưa có' ?></p>
                      <p><strong>Năm sinh:</strong> <?= $c['birth_year'] ?? 'Chưa có' ?></p>
                      <p><strong>CMND/CCCD:</strong> <?= htmlspecialchars($c['id_number'] ?? 'Chưa có') ?></p>
                      <p><strong>Điện thoại:</strong> <?= htmlspecialchars($c['phone'] ?? 'Chưa có') ?></p>
                      <p><strong>Email:</strong> <?= htmlspecialchars($c['email'] ?? 'Chưa có') ?></p>
                      <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($c['address'] ?? 'Chưa có') ?></p>
                      <p><strong>Yêu cầu cá nhân:</strong> <?= htmlspecialchars($c['special_requirements'] ?? 'Không có') ?></p>
                      <p><strong>Thanh toán:</strong> <?= $c['payment_status'] ?></p>
                      <p><strong>Check-in:</strong> <?= $c['check_in_status'] ?></p>
                      <p><strong>Phòng:</strong> <?= $c['room_number'] ?? 'Chưa phân' ?></p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Edit Modal -->
              <div class="modal fade" id="editModal<?= $c['id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <form method="post" action="<?= BASE_URL ?>admin/customers">
                      <input type="hidden" name="customer_id" value="<?= $c['id'] ?>">
                      <div class="modal-header">
                        <h5 class="modal-title">Chỉnh sửa thông tin</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body">
                        <div class="mb-3">
                          <label>Họ tên <span class="text-danger">*</span></label>
                          <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($c['name']) ?>" required>
                        </div>
                        <div class="row">
                          <div class="col-md-6 mb-3">
                            <label>Giới tính</label>
                            <select class="form-select" name="gender">
                              <option value="">Chọn</option>
                              <option value="Nam" <?= $c['gender'] == 'Nam' ? 'selected' : '' ?>>Nam</option>
                              <option value="Nữ" <?= $c['gender'] == 'Nữ' ? 'selected' : '' ?>>Nữ</option>
                            </select>
                          </div>
                          <div class="col-md-6 mb-3">
                            <label>Năm sinh</label>
                            <input type="number" class="form-control" name="birth_year" value="<?= $c['birth_year'] ?? '' ?>">
                          </div>
                        </div>
                        <div class="mb-3">
                          <label>CMND/CCCD</label>
                          <input type="text" class="form-control" name="id_number" value="<?= htmlspecialchars($c['id_number'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                          <label>Điện thoại</label>
                          <input type="text" class="form-control" name="phone" value="<?= htmlspecialchars($c['phone'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                          <label>Email</label>
                          <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($c['email'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                          <label>Thanh toán</label>
                          <select class="form-select" name="payment_status">
                            <option value="Chưa thanh toán" <?= $c['payment_status'] == 'Chưa thanh toán' ? 'selected' : '' ?>>Chưa thanh toán</option>
                            <option value="Đã cọc" <?= $c['payment_status'] == 'Đã cọc' ? 'selected' : '' ?>>Đã cọc</option>
                            <option value="Hoàn tất" <?= $c['payment_status'] == 'Hoàn tất' ? 'selected' : '' ?>>Hoàn tất</option>
                          </select>
                        </div>
                        <div class="mb-3">
                          <label>Yêu cầu cá nhân</label>
                          <textarea class="form-control" name="special_requirements" rows="2"><?= htmlspecialchars($c['special_requirements'] ?? '') ?></textarea>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" name="update_customer" class="btn btn-primary">Cập nhật</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>

              <!-- Room Modal -->
              <div class="modal fade" id="roomModal<?= $c['id'] ?>" tabindex="-1">
                <div class="modal-dialog modal-sm">
                  <div class="modal-content">
                    <form method="post" action="<?= BASE_URL ?>admin/customers/assign-room">
                      <input type="hidden" name="customer_id" value="<?= $c['id'] ?>">
                      <div class="modal-header">
                        <h5 class="modal-title">Phân phòng</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body">
                        <div class="mb-3">
                          <label>Số phòng <span class="text-danger">*</span></label>
                          <input type="text" class="form-control" name="room_number" required>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Lưu</button>
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
    'title' => 'Danh sách khách',
    'pageTitle' => 'Danh sách khách theo tour',
    'content' => $content,
    'breadcrumb' => [
        ['label' => 'Booking', 'url' => BASE_URL . 'admin/bookings', 'active' => false],
        ['label' => 'Danh sách khách', 'url' => '', 'active' => true],
    ],
]);
?>
