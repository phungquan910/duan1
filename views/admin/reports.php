<?php
ob_start();
?>

<div class="row mb-3">
  <div class="col-md-3">
    <div class="card text-white bg-primary">
      <div class="card-body">
        <h5>Tổng Booking</h5>
        <h2><?= number_format($summary['total_bookings'] ?? 0) ?></h2>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-white bg-success">
      <div class="card-body">
        <h5>Doanh thu</h5>
        <h2><?= number_format($summary['total_revenue'] ?? 0) ?> VNĐ</h2>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-white bg-warning">
      <div class="card-body">
        <h5>Chi phí</h5>
        <h2><?= number_format($summary['total_expense'] ?? 0) ?> VNĐ</h2>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-white bg-info">
      <div class="card-body">
        <h5>Lợi nhuận</h5>
        <h2><?= number_format($summary['total_profit'] ?? 0) ?> VNĐ</h2>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Báo cáo theo Tour</h3>
      </div>
      <div class="card-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Tour</th>
              <th>Số Booking</th>
              <th>Số khách</th>
              <th>Doanh thu</th>
              <th>Chi phí</th>
              <th>Lợi nhuận</th>
              <th>Tỷ lệ LN</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($tourReports)): ?>
              <tr>
                <td colspan="7" class="text-center text-muted">Chưa có dữ liệu</td>
              </tr>
            <?php else: ?>
              <?php foreach ($tourReports as $tr): ?>
                <tr>
                  <td><?= htmlspecialchars($tr['tour_name']) ?></td>
                  <td><?= number_format($tr['booking_count'] ?? 0) ?></td>
                  <td><?= number_format($tr['customer_count'] ?? 0) ?></td>
                  <td><?= number_format($tr['revenue'] ?? 0) ?> VNĐ</td>
                  <td><?= number_format($tr['expense'] ?? 0) ?> VNĐ</td>
                  <td><?= number_format($tr['profit'] ?? 0) ?> VNĐ</td>
                  <td>
                    <?php 
                    $revenue = $tr['revenue'] ?? 0;
                    $profit = $tr['profit'] ?? 0;
                    $rate = $revenue > 0 ? ($profit / $revenue * 100) : 0;
                    echo number_format($rate, 1) . '%';
                    ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="row mt-3">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Top 5 Tour bán chạy</h3>
      </div>
      <div class="card-body">
        <table class="table">
          <thead>
            <tr>
              <th>Tour</th>
              <th>Số booking</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($topSelling)): ?>
              <tr>
                <td colspan="2" class="text-center text-muted">Chưa có dữ liệu</td>
              </tr>
            <?php else: ?>
              <?php foreach ($topSelling as $ts): ?>
                <tr>
                  <td><?= htmlspecialchars($ts['tour_name']) ?></td>
                  <td><?= number_format($ts['booking_count'] ?? 0) ?></td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Top 5 Tour lợi nhuận cao</h3>
      </div>
      <div class="card-body">
        <table class="table">
          <thead>
            <tr>
              <th>Tour</th>
              <th>Lợi nhuận</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($topProfit)): ?>
              <tr>
                <td colspan="2" class="text-center text-muted">Chưa có dữ liệu</td>
              </tr>
            <?php else: ?>
              <?php foreach ($topProfit as $tp): ?>
                <tr>
                  <td><?= htmlspecialchars($tp['tour_name']) ?></td>
                  <td><?= number_format($tp['profit'] ?? 0) ?> VNĐ</td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php
$content = ob_get_clean();

view('layouts.AdminLayout', [
    'title' => 'Báo cáo',
    'pageTitle' => 'Báo cáo Doanh thu & Chi phí',
    'content' => $content,
    'breadcrumb' => [
        ['label' => 'Báo cáo', 'url' => BASE_URL . 'admin/reports', 'active' => true],
    ],
]);
?>
