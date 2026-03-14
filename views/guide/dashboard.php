<?php
ob_start();
?>

<div class="row">
  <div class="col-md-3">
    <div class="card text-white bg-primary">
      <div class="card-body text-center">
        <h6>Tour được giao</h6>
        <h2><?= $totalTours ?? 0 ?></h2>
        <i class="bi bi-map"></i>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-white bg-info">
      <div class="card-body text-center">
        <h6>Tổng khách</h6>
        <h2><?= $totalCustomers ?? 0 ?></h2>
        <i class="bi bi-people"></i>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-white bg-success">
      <div class="card-body text-center">
        <h6>Đã check-in</h6>
        <h2><?= $checkedIn ?? 0 ?></h2>
        <i class="bi bi-check-circle"></i>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-white bg-warning">
      <div class="card-body text-center">
        <h6>Yêu cầu đặc biệt</h6>
        <h2><?= $specialRequests ?? 0 ?></h2>
        <i class="bi bi-exclamation-triangle"></i>
      </div>
    </div>
  </div>
</div>

<div class="row mt-3">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Truy cập nhanh</h3>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-3">
            <a href="<?= BASE_URL ?>guide/schedule" class="btn btn-outline-primary w-100 p-3">
              <i class="bi bi-calendar-event fs-4 d-block mb-2"></i>
              <strong>Lịch trình Tour</strong><br>
              <small>Xem lịch làm việc</small>
            </a>
          </div>
          <div class="col-md-3">
            <a href="<?= BASE_URL ?>guide/customers" class="btn btn-outline-info w-100 p-3">
              <i class="bi bi-people fs-4 d-block mb-2"></i>
              <strong>Danh sách khách</strong><br>
              <small>Thông tin khách tham gia</small>
            </a>
          </div>
          <div class="col-md-3">
            <a href="<?= BASE_URL ?>guide/checkin" class="btn btn-outline-success w-100 p-3">
              <i class="bi bi-check-circle fs-4 d-block mb-2"></i>
              <strong>Check-in</strong><br>
              <small>Điểm danh khách</small>
            </a>
          </div>
          <div class="col-md-3">
            <a href="<?= BASE_URL ?>guide/diary" class="btn btn-outline-secondary w-100 p-3">
              <i class="bi bi-journal-text fs-4 d-block mb-2"></i>
              <strong>Nhật ký Tour</strong><br>
              <small>Ghi chú hành trình</small>
            </a>
          </div>
        </div>
        <div class="row g-3 mt-2">
          <div class="col-md-6">
            <a href="<?= BASE_URL ?>guide/requirements" class="btn btn-outline-warning w-100 p-3">
              <i class="bi bi-exclamation-triangle fs-4 d-block mb-2"></i>
              <strong>Yêu cầu đặc biệt</strong><br>
              <small>Quản lý nhu cầu khách</small>
            </a>
          </div>
          <div class="col-md-6">
            <div class="card">
              <div class="card-body text-center">
                <i class="bi bi-person-badge fs-4 text-muted mb-2 d-block"></i>
                <strong>Hướng dẫn viên</strong><br>
                <small class="text-muted"><?= $_SESSION['user_name'] ?? 'HDV' ?></small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
$content = ob_get_clean();
view('layouts.GuideLayout', [
    'title' => 'Dashboard HDV',
    'pageTitle' => 'Dashboard',
    'content' => $content
]);
?>
