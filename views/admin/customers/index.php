<?php
ob_start();
?>

<div class="row mb-3">
  <div class="col-12">
    <a href="<?= BASE_URL ?>admin/customers/add" class="btn btn-primary">
      <i class="bi bi-plus-circle"></i> Thêm khách hàng
    </a>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Danh sách Khách hàng</h3>
      </div>
      <div class="card-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Tên</th>
              <th>Giới tính</th>
              <th>Năm sinh</th>
              <th>Điện thoại</th>
              <th>Tour</th>
              <th>Thanh toán</th>
              <th>Check-in</th>
              <th>Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($customers as $c): ?>
              <tr>
                <td>#<?= $c['id'] ?></td>
                <td><?= htmlspecialchars($c['name']) ?></td>
                <td><?= $c['gender'] ?? '' ?></td>
                <td><?= $c['birth_year'] ?? '' ?></td>
                <td><?= htmlspecialchars($c['phone'] ?? '') ?></td>
                <td><?= htmlspecialchars($c['tour_name'] ?? 'Không có') ?></td>
                <td>
                  <span class="badge <?= $c['payment_status'] == 'Hoàn tất' ? 'bg-success' : ($c['payment_status'] == 'Đã cọc' ? 'bg-primary' : 'bg-warning') ?>">
                    <?= $c['payment_status'] ?>
                  </span>
                </td>
                <td>
                  <span class="badge <?= $c['check_in_status'] == 'Đã check-in' ? 'bg-success' : 'bg-secondary' ?>">
                    <?= $c['check_in_status'] ?>
                  </span>
                </td>
                <td>
                  <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewModal<?= $c['id'] ?>">
                    <i class="bi bi-eye"></i>
                  </button>
                  <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $c['id'] ?>">
                    <i class="bi bi-pencil"></i>
                  </button>
                  <a href="<?= BASE_URL ?>admin/customers?delete=<?= $c['id'] ?>" 
                     class="btn btn-sm btn-danger" 
                     onclick="return confirm('Xóa khách hàng này?')">
                    <i class="bi bi-trash"></i>
                  </a>
                </td>
              </tr>

              <!-- View Modal -->
              <div class="modal fade" id="viewModal<?= $c['id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Chi tiết Khách hàng</h5>
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
                      <p><strong>Tour:</strong> <?= htmlspecialchars($c['tour_name'] ?? 'Không có') ?></p>
                      <p><strong>Ngày khởi hành:</strong> <?= $c['start_date'] ? date('d/m/Y', strtotime($c['start_date'])) : 'Chưa có' ?></p>
                      <p><strong>Thanh toán:</strong> <?= $c['payment_status'] ?></p>
                      <p><strong>Check-in:</strong> <?= $c['check_in_status'] ?></p>
                      <p><strong>Phòng:</strong> <?= $c['room_number'] ?? 'Chưa phân' ?></p>
                      <p><strong>Yêu cầu cá nhân:</strong> <?= htmlspecialchars($c['special_requirements'] ?? 'Không có') ?></p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Edit Modal -->
              <div class="modal fade" id="editModal<?= $c['id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <form method="post">
                      <div class="modal-header">
                        <h5 class="modal-title">Chỉnh sửa Khách hàng</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body">
                        <input type="hidden" name="customer_id" value="<?= $c['id'] ?>">
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
                          <label>Tour</label>
                          <select class="form-select" name="booking_id">
                            <option value="">Không có booking</option>
                            <?php foreach ($bookings as $b): ?>
                              <option value="<?= $b['id'] ?>" <?= $c['booking_id'] == $b['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($b['tour_name']) ?>
                              </option>
                            <?php endforeach; ?>
                          </select>
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
    'title' => 'Quản lý Khách hàng',
    'pageTitle' => 'Quản lý Khách hàng',
    'content' => $content,
    'breadcrumb' => [
        ['label' => 'Khách hàng', 'url' => BASE_URL . 'admin/customers', 'active' => true],
    ],
]);
?>
