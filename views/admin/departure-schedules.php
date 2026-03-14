<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (isset($_GET['delete'])) {
    DepartureSchedule::delete($_GET['delete']);
    header('Location: ' . BASE_URL . '?act=admin/schedules');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_schedule'])) {
    DepartureSchedule::create($_POST);
    header('Location: ' . BASE_URL . '?act=admin/schedules');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_schedule'])) {
    DepartureSchedule::update($_POST['schedule_id'], $_POST);
    header('Location: ' . BASE_URL . '?act=admin/schedules');
    exit;
}

$schedules = DepartureSchedule::getAll();
$tours = Tour::getAll();
$guides = Guide::getAll();

ob_start();
?>

<div class="row mb-3">
  <div class="col-12">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
      <i class="bi bi-plus"></i> Thêm lịch khởi hành
    </button>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Lịch khởi hành & Phân bổ nhân sự</h3>
      </div>
      <div class="card-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Tour</th>
              <th>Ngày khởi hành</th>
              <th>Ngày về</th>
              <th>HDV</th>
              <th>Tài xế</th>
              <th>Xe</th>
              <th>Khách</th>
              <th>Trạng thái</th>
              <th>Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($schedules as $s): ?>
              <tr>
                <td>#<?= $s['id'] ?></td>
                <td><?= htmlspecialchars($s['tour_name']) ?></td>
                <td><?= date('d/m/Y', strtotime($s['departure_date'])) ?></td>
                <td><?= $s['return_date'] ? date('d/m/Y', strtotime($s['return_date'])) : '-' ?></td>
                <td><?= htmlspecialchars($s['guide_name'] ?? 'Chưa phân') ?></td>
                <td><?= htmlspecialchars($s['driver_name'] ?? '-') ?></td>
                <td><?= htmlspecialchars($s['vehicle_info'] ?? '-') ?></td>
                <td><?= $s['current_guests'] ?>/<?= $s['max_guests'] ?></td>
                <td>
                  <?php
                  $badges = [1 => 'bg-success', 2 => 'bg-primary', 3 => 'bg-secondary', 4 => 'bg-danger'];
                  $statuses = [1 => 'Sẵn sàng', 2 => 'Đang diễn ra', 3 => 'Hoàn thành', 4 => 'Hủy'];
                  ?>
                  <span class="badge <?= $badges[$s['status']] ?>">
                    <?= $statuses[$s['status']] ?>
                  </span>
                </td>
                <td>
                  <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $s['id'] ?>">
                    <i class="bi bi-pencil"></i>
                  </button>
                  <button class="btn btn-danger btn-sm" onclick="deleteSchedule(<?= $s['id'] ?>)">
                    <i class="bi bi-trash"></i>
                  </button>
                </td>
              </tr>

              <!-- Edit Modal -->
              <div class="modal fade" id="editModal<?= $s['id'] ?>" tabindex="-1">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Chỉnh sửa lịch khởi hành</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="post">
                      <div class="modal-body">
                        <input type="hidden" name="schedule_id" value="<?= $s['id'] ?>">
                        <div class="row">
                          <div class="col-md-6 mb-3">
                            <label class="form-label">Tour</label>
                            <select class="form-select" name="tour_id" required>
                              <?php foreach ($tours as $t): ?>
                                <option value="<?= $t['id'] ?>" <?= $s['tour_id'] == $t['id'] ? 'selected' : '' ?>>
                                  <?= htmlspecialchars($t['name']) ?>
                                </option>
                              <?php endforeach; ?>
                            </select>
                          </div>
                          <div class="col-md-6 mb-3">
                            <label class="form-label">HDV</label>
                            <select class="form-select" name="guide_id">
                              <option value="">Chưa phân</option>
                              <?php foreach ($guides as $g): ?>
                                <option value="<?= $g['user_id'] ?>" <?= $s['guide_id'] == $g['user_id'] ? 'selected' : '' ?>>
                                  <?= htmlspecialchars($g['name']) ?>
                                </option>
                              <?php endforeach; ?>
                            </select>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-6 mb-3">
                            <label class="form-label">Ngày khởi hành</label>
                            <input type="date" class="form-control" name="departure_date" value="<?= $s['departure_date'] ?>" required>
                          </div>
                          <div class="col-md-6 mb-3">
                            <label class="form-label">Ngày về</label>
                            <input type="date" class="form-control" name="return_date" value="<?= $s['return_date'] ?>">
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-6 mb-3">
                            <label class="form-label">Tài xế</label>
                            <input type="text" class="form-control" name="driver_name" value="<?= htmlspecialchars($s['driver_name'] ?? '') ?>">
                          </div>
                          <div class="col-md-6 mb-3">
                            <label class="form-label">Thông tin xe</label>
                            <input type="text" class="form-control" name="vehicle_info" value="<?= htmlspecialchars($s['vehicle_info'] ?? '') ?>">
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-6 mb-3">
                            <label class="form-label">Số khách tối đa</label>
                            <input type="number" class="form-control" name="max_guests" value="<?= $s['max_guests'] ?>">
                          </div>
                          <div class="col-md-6 mb-3">
                            <label class="form-label">Trạng thái</label>
                            <select class="form-select" name="status">
                              <option value="1" <?= $s['status'] == 1 ? 'selected' : '' ?>>Sẵn sàng</option>
                              <option value="2" <?= $s['status'] == 2 ? 'selected' : '' ?>>Đang diễn ra</option>
                              <option value="3" <?= $s['status'] == 3 ? 'selected' : '' ?>>Hoàn thành</option>
                              <option value="4" <?= $s['status'] == 4 ? 'selected' : '' ?>>Hủy</option>
                            </select>
                          </div>
                        </div>
                        <div class="mb-3">
                          <label class="form-label">Thông tin khách sạn</label>
                          <textarea class="form-control" name="hotel_info" rows="2"><?= htmlspecialchars($s['hotel_info'] ?? '') ?></textarea>
                        </div>
                        <div class="mb-3">
                          <label class="form-label">Ghi chú</label>
                          <textarea class="form-control" name="notes" rows="2"><?= htmlspecialchars($s['notes'] ?? '') ?></textarea>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" name="update_schedule" class="btn btn-primary">Cập nhật</button>
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

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Thêm lịch khởi hành mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="post">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Tour *</label>
              <select class="form-select" name="tour_id" required>
                <option value="">Chọn tour</option>
                <?php foreach ($tours as $t): ?>
                  <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">HDV</label>
              <select class="form-select" name="guide_id">
                <option value="">Chưa phân</option>
                <?php foreach ($guides as $g): ?>
                  <option value="<?= $g['user_id'] ?>"><?= htmlspecialchars($g['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Ngày khởi hành *</label>
              <input type="date" class="form-control" name="departure_date" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Ngày về</label>
              <input type="date" class="form-control" name="return_date">
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Tài xế</label>
              <input type="text" class="form-control" name="driver_name">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Thông tin xe</label>
              <input type="text" class="form-control" name="vehicle_info">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Số khách tối đa</label>
            <input type="number" class="form-control" name="max_guests" value="30">
          </div>
          <div class="mb-3">
            <label class="form-label">Thông tin khách sạn</label>
            <textarea class="form-control" name="hotel_info" rows="2"></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Ghi chú</label>
            <textarea class="form-control" name="notes" rows="2"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" name="add_schedule" class="btn btn-primary">Thêm mới</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function deleteSchedule(id) {
  if (confirm('Bạn có chắc muốn xóa lịch khởi hành này?')) {
    window.location.href = '?act=admin/schedules&delete=' + id;
  }
}
</script>

<?php
$content = ob_get_clean();

view('layouts.AdminLayout', [
    'title' => 'Lịch khởi hành',
    'pageTitle' => 'Quản lý Lịch khởi hành & Phân bổ nhân sự',
    'content' => $content,
    'breadcrumb' => [
        ['label' => 'Lịch khởi hành', 'url' => BASE_URL . '?act=admin/schedules', 'active' => true],
    ],
]);
?>
