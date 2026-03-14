<?php
ob_start();
?>

<div class="row mb-3">
  <div class="col-md-3">
    <div class="card bg-primary text-white">
      <div class="card-body">
        <h6>Tổng khách</h6>
        <h3><?= count($customers) ?></h3>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card bg-success text-white">
      <div class="card-body">
        <h6>Đã check-in</h6>
        <h3><?= count(array_filter($customers, fn($c) => $c['check_in_status'])) ?></h3>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card bg-warning text-white">
      <div class="card-body">
        <h6>Yêu cầu đặc biệt</h6>
        <h3><?= count(array_filter($customers, fn($c) => !empty($c['special_requirements']))) ?></h3>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card bg-info text-white">
      <div class="card-body">
        <h6>Đã phân phòng</h6>
        <h3><?= count(array_filter($customers, fn($c) => !empty($c['room_number']))) ?></h3>
      </div>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h3 class="card-title">Danh sách khách trong đoàn</h3>
  </div>
  <div class="card-body">
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>STT</th>
          <th>Họ tên</th>
          <th>Liên hệ</th>
          <th>Tour</th>
          <th>Phòng</th>
          <th>Check-in</th>
          <th>Yêu cầu đặc biệt</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($customers)): ?>
          <tr>
            <td colspan="7" class="text-center text-muted">Chưa có khách</td>
          </tr>
        <?php else: ?>
          <?php foreach ($customers as $index => $c): ?>
            <tr>
              <td><?= $index + 1 ?></td>
              <td>
                <strong><?= htmlspecialchars($c['name']) ?></strong><br>
                <small class="text-muted">
                  <?php if ($c['gender']): ?>
                    <i class="bi bi-person"></i> <?= $c['gender'] ?>
                  <?php endif; ?>
                  <?php if ($c['birth_year']): ?>
                    | <?= $c['birth_year'] ?>
                  <?php endif; ?>
                </small>
              </td>
              <td>
                <?php if ($c['phone']): ?>
                  <i class="bi bi-telephone"></i> <?= htmlspecialchars($c['phone']) ?><br>
                <?php endif; ?>
                <?php if ($c['email']): ?>
                  <small><i class="bi bi-envelope"></i> <?= htmlspecialchars($c['email']) ?></small>
                <?php endif; ?>
              </td>
              <td>
                <?= htmlspecialchars($c['tour_name'] ?? 'N/A') ?><br>
                <?php if ($c['start_date']): ?>
                  <small class="text-muted"><?= date('d/m/Y', strtotime($c['start_date'])) ?></small>
                <?php endif; ?>
              </td>
              <td class="text-center">
                <?= $c['room_number'] ? '<span class="badge bg-info">Phòng ' . $c['room_number'] . '</span>' : '-' ?>
              </td>
              <td class="text-center">
                <?php if ($c['check_in_status']): ?>
                  <span class="badge bg-success"><i class="bi bi-check-circle"></i> Đã check-in</span>
                <?php else: ?>
                  <span class="badge bg-secondary">Chưa check-in</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if (!empty($c['special_requirements'])): ?>
                  <span class="badge bg-warning text-dark">
                    <i class="bi bi-exclamation-triangle"></i> <?= htmlspecialchars($c['special_requirements']) ?>
                  </span>
                <?php else: ?>
                  <span class="text-muted">-</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php
$content = ob_get_clean();
view('layouts.GuideLayout', [
    'title' => 'Danh sách khách',
    'pageTitle' => 'Danh sách khách trong đoàn',
    'content' => $content
]);
?>
