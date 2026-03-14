<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$bookings = Booking::getAll();
$selectedBooking = $_GET['booking_id'] ?? null;
$bookingInfo = null;
$customers = [];

if ($selectedBooking) {
    $bookingInfo = Booking::getById($selectedBooking);
    $customers = Customer::getAll();
}

ob_start();
?>

<div class="row mb-3">
  <div class="col-md-8">
    <label class="form-label">Chọn Booking để in danh sách</label>
    <select class="form-select" onchange="window.location.href='?act=admin/print-guest-list&booking_id=' + this.value">
      <option value="">-- Chọn booking --</option>
      <?php foreach ($bookings as $b): ?>
        <option value="<?= $b['id'] ?>" <?= $selectedBooking == $b['id'] ? 'selected' : '' ?>>
          #<?= $b['id'] ?> - <?= htmlspecialchars($b['tour_name']) ?> - <?= date('d/m/Y', strtotime($b['start_date'])) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>
  <?php if ($selectedBooking): ?>
  <div class="col-md-4 text-end">
    <label class="form-label">&nbsp;</label><br>
    <button class="btn btn-primary" onclick="window.print()">
      <i class="bi bi-printer"></i> In danh sách
    </button>
  </div>
  <?php endif; ?>
</div>

<?php if ($selectedBooking && $bookingInfo): ?>
<div class="row">
  <div class="col-12">
    <div class="card" id="printArea">
      <div class="card-body">
        <div class="text-center mb-4">
          <h3>DANH SÁCH ĐOÀN KHÁCH</h3>
          <h5><?= htmlspecialchars($bookingInfo['tour_name']) ?></h5>
          <p>Ngày khởi hành: <?= date('d/m/Y', strtotime($bookingInfo['start_date'])) ?></p>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <p><strong>Mã booking:</strong> #<?= $bookingInfo['id'] ?></p>
            <p><strong>Hướng dẫn viên:</strong> <?= htmlspecialchars($bookingInfo['guide_name'] ?? 'Chưa phân') ?></p>
          </div>
          <div class="col-md-6">
            <p><strong>Ngày về:</strong> <?= $bookingInfo['end_date'] ? date('d/m/Y', strtotime($bookingInfo['end_date'])) : 'N/A' ?></p>
            <p><strong>Tổng số khách:</strong> <?= count($customers) ?> người</p>
          </div>
        </div>

        <table class="table table-bordered">
          <thead class="table-light">
            <tr>
              <th width="5%">STT</th>
              <th width="20%">Họ và tên</th>
              <th width="10%">Giới tính</th>
              <th width="10%">Năm sinh</th>
              <th width="15%">Số điện thoại</th>
              <th width="15%">CMND/CCCD</th>
              <th width="25%">Ghi chú</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($customers)): ?>
              <tr>
                <td colspan="7" class="text-center text-muted">Chưa có khách hàng nào</td>
              </tr>
            <?php else: ?>
              <?php foreach ($customers as $index => $c): ?>
                <tr>
                  <td><?= $index + 1 ?></td>
                  <td><?= htmlspecialchars($c['name']) ?></td>
                  <td>-</td>
                  <td>-</td>
                  <td><?= htmlspecialchars($c['phone'] ?? '') ?></td>
                  <td><?= htmlspecialchars($c['id_number'] ?? '') ?></td>
                  <td><?= htmlspecialchars($c['special_notes'] ?? '') ?></td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>

        <div class="row mt-5">
          <div class="col-6 text-center">
            <p><strong>Hướng dẫn viên</strong></p>
            <p class="mt-5">(Ký và ghi rõ họ tên)</p>
          </div>
          <div class="col-6 text-center">
            <p><strong>Trưởng đoàn</strong></p>
            <p class="mt-5">(Ký và ghi rõ họ tên)</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<style>
@media print {
  .btn, .form-select, .form-label, .breadcrumb, .sidebar, .navbar, .card-header {
    display: none !important;
  }
  .card {
    border: none !important;
    box-shadow: none !important;
  }
  body {
    background: white !important;
  }
}
</style>

<?php
$content = ob_get_clean();

view('layouts.AdminLayout', [
    'title' => 'In danh sách đoàn',
    'pageTitle' => 'In danh sách đoàn khách',
    'content' => $content,
    'breadcrumb' => [
        ['label' => 'In danh sách', 'url' => BASE_URL . '?act=admin/print-guest-list', 'active' => true],
    ],
]);
?>
