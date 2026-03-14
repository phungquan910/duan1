<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (isset($_GET['delete'])) {
    TourExpense::delete($_GET['delete']);
    header('Location: ' . BASE_URL . '?act=admin/expenses&booking_id=' . $_GET['booking_id']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_expense'])) {
    TourExpense::create($_POST);
    header('Location: ' . BASE_URL . '?act=admin/expenses&booking_id=' . $_POST['booking_id']);
    exit;
}

$bookings = Booking::getAll();
$selectedBooking = $_GET['booking_id'] ?? null;
$expenses = [];
$totalExpense = 0;

if ($selectedBooking) {
    $expenses = TourExpense::getByBooking($selectedBooking);
    $totalExpense = TourExpense::getTotalByBooking($selectedBooking);
}

ob_start();
?>

<div class="row mb-3">
  <div class="col-md-6">
    <label class="form-label">Chọn Booking</label>
    <select class="form-select" onchange="window.location.href='?act=admin/expenses&booking_id=' + this.value">
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
      <i class="bi bi-plus"></i> Thêm chi phí
    </button>
  </div>
  <?php endif; ?>
</div>

<?php if ($selectedBooking): ?>
<div class="row">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Danh sách chi phí</h3>
      </div>
      <div class="card-body">
        <?php if (empty($expenses)): ?>
          <p class="text-muted">Chưa có chi phí nào</p>
        <?php else: ?>
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Ngày</th>
              <th>Loại chi phí</th>
              <th>Số tiền</th>
              <th>Mô tả</th>
              <th>Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($expenses as $e): ?>
              <tr>
                <td><?= date('d/m/Y', strtotime($e['expense_date'])) ?></td>
                <td><?= htmlspecialchars($e['expense_type']) ?></td>
                <td class="text-end"><?= number_format($e['amount']) ?> VNĐ</td>
                <td><?= htmlspecialchars($e['description'] ?? '') ?></td>
                <td>
                  <button class="btn btn-danger btn-sm" onclick="deleteExpense(<?= $e['id'] ?>)">
                    <i class="bi bi-trash"></i>
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr class="table-info">
              <th colspan="2" class="text-end">Tổng chi phí:</th>
              <th class="text-end"><?= number_format($totalExpense) ?> VNĐ</th>
              <th colspan="2"></th>
            </tr>
          </tfoot>
        </table>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title">Tổng quan chi phí</h5>
      </div>
      <div class="card-body">
        <div class="mb-3">
          <h6>Tổng chi phí</h6>
          <h3 class="text-danger"><?= number_format($totalExpense) ?> VNĐ</h3>
        </div>
        <hr>
        <div class="mb-2">
          <small class="text-muted">Số khoản chi: <?= count($expenses) ?></small>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Thêm chi phí</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="post">
        <div class="modal-body">
          <input type="hidden" name="booking_id" value="<?= $selectedBooking ?>">
          <div class="mb-3">
            <label class="form-label">Loại chi phí *</label>
            <select class="form-select" name="expense_type" required>
              <option value="">-- Chọn loại --</option>
              <option value="Xe">Xe</option>
              <option value="Khách sạn">Khách sạn</option>
              <option value="Ăn uống">Ăn uống</option>
              <option value="Vé tham quan">Vé tham quan</option>
              <option value="HDV">HDV</option>
              <option value="Khác">Khác</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Số tiền *</label>
            <input type="number" class="form-control" name="amount" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Ngày chi</label>
            <input type="date" class="form-control" name="expense_date" value="<?= date('Y-m-d') ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea class="form-control" name="description" rows="3"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" name="add_expense" class="btn btn-primary">Thêm</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php endif; ?>

<script>
function deleteExpense(id) {
  if (confirm('Bạn có chắc muốn xóa chi phí này?')) {
    window.location.href = '?act=admin/expenses&booking_id=<?= $selectedBooking ?>&delete=' + id;
  }
}
</script>

<?php
$content = ob_get_clean();

view('layouts.AdminLayout', [
    'title' => 'Chi phí Tour',
    'pageTitle' => 'Quản lý Chi phí Tour',
    'content' => $content,
    'breadcrumb' => [
        ['label' => 'Chi phí Tour', 'url' => BASE_URL . '?act=admin/expenses', 'active' => true],
    ],
]);
?>
