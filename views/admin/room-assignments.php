<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (isset($_GET['delete'])) {
    RoomAssignment::delete($_GET['delete']);
    header('Location: ' . BASE_URL . '?act=admin/rooms');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_room'])) {
    RoomAssignment::create($_POST);
    header('Location: ' . BASE_URL . '?act=admin/rooms');
    exit;
}

$bookings = Booking::getAll();
$customers = Customer::getAll();

$selectedBooking = $_GET['booking_id'] ?? null;
$rooms = [];
if ($selectedBooking) {
    $rooms = RoomAssignment::getByBooking($selectedBooking);
}

ob_start();
?>

<div class="row mb-3">
  <div class="col-md-6">
    <label class="form-label">Chọn Booking</label>
    <select class="form-select" onchange="window.location.href='?act=admin/rooms&booking_id=' + this.value">
      <option value="">-- Chọn booking --</option>
      <?php foreach ($bookings as $b): ?>
        <option value="<?= $b['id'] ?>" <?= $selectedBooking == $b['id'] ? 'selected' : '' ?>>
          #<?= $b['id'] ?> - <?= htmlspecialchars($b['tour_name']) ?> (<?= date('d/m/Y', strtotime($b['start_date'])) ?>)
        </option>
      <?php endforeach; ?>
    </select>
  </div>
  <?php if ($selectedBooking): ?>
  <div class="col-md-6 text-end">
    <label class="form-label">&nbsp;</label><br>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
      <i class="bi bi-plus"></i> Thêm phân phòng
    </button>
  </div>
  <?php endif; ?>
</div>

<?php if ($selectedBooking): ?>
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Danh sách phân phòng</h3>
      </div>
      <div class="card-body">
        <?php if (empty($rooms)): ?>
          <p class="text-muted">Chưa có phân phòng nào</p>
        <?php else: ?>
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Khách hàng</th>
              <th>Khách sạn</th>
              <th>Số phòng</th>
              <th>Loại phòng</th>
              <th>Check-in</th>
              <th>Check-out</th>
              <th>Ghi chú</th>
              <th>Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rooms as $r): ?>
              <tr>
                <td><?= htmlspecialchars($r['customer_name'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($r['hotel_name'] ?? '-') ?></td>
                <td><?= htmlspecialchars($r['room_number'] ?? '-') ?></td>
                <td><?= htmlspecialchars($r['room_type'] ?? '-') ?></td>
                <td><?= $r['check_in_date'] ? date('d/m/Y', strtotime($r['check_in_date'])) : '-' ?></td>
                <td><?= $r['check_out_date'] ? date('d/m/Y', strtotime($r['check_out_date'])) : '-' ?></td>
                <td><?= htmlspecialchars($r['notes'] ?? '') ?></td>
                <td>
                  <button class="btn btn-danger btn-sm" onclick="deleteRoom(<?= $r['id'] ?>)">
                    <i class="bi bi-trash"></i>
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Thêm phân phòng</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="post">
        <div class="modal-body">
          <input type="hidden" name="booking_id" value="<?= $selectedBooking ?>">
          <div class="mb-3">
            <label class="form-label">Khách hàng</label>
            <select class="form-select" name="customer_id">
              <option value="">-- Chọn khách --</option>
              <?php foreach ($customers as $c): ?>
                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Khách sạn</label>
            <input type="text" class="form-control" name="hotel_name">
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Số phòng</label>
              <input type="text" class="form-control" name="room_number">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Loại phòng</label>
              <input type="text" class="form-control" name="room_type" placeholder="VD: Đơn, Đôi, VIP">
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Check-in</label>
              <input type="date" class="form-control" name="check_in_date">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Check-out</label>
              <input type="date" class="form-control" name="check_out_date">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Ghi chú</label>
            <textarea class="form-control" name="notes" rows="2"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" name="add_room" class="btn btn-primary">Thêm</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php endif; ?>

<script>
function deleteRoom(id) {
  if (confirm('Bạn có chắc muốn xóa phân phòng này?')) {
    window.location.href = '?act=admin/rooms&booking_id=<?= $selectedBooking ?>&delete=' + id;
  }
}
</script>

<?php
$content = ob_get_clean();

view('layouts.AdminLayout', [
    'title' => 'Phân phòng khách sạn',
    'pageTitle' => 'Quản lý Phân phòng khách sạn',
    'content' => $content,
    'breadcrumb' => [
        ['label' => 'Phân phòng', 'url' => BASE_URL . '?act=admin/rooms', 'active' => true],
    ],
]);
?>
