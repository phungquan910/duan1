<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$bookings = Booking::getAll();
$tours = Tour::getAll();

$revenueData = [];
foreach ($bookings as $b) {
    $tourPrice = 0;
    foreach ($tours as $t) {
        if ($t['id'] == $b['tour_id']) {
            $tourPrice = $t['price'];
            break;
        }
    }
    
    $expense = TourExpense::getTotalByBooking($b['id']);
    $profit = $tourPrice - $expense;
    
    $revenueData[] = [
        'booking_id' => $b['id'],
        'tour_name' => $b['tour_name'],
        'start_date' => $b['start_date'],
        'revenue' => $tourPrice,
        'expense' => $expense,
        'profit' => $profit
    ];
}

$totalRevenue = array_sum(array_column($revenueData, 'revenue'));
$totalExpense = array_sum(array_column($revenueData, 'expense'));
$totalProfit = $totalRevenue - $totalExpense;

ob_start();
?>

<div class="row mb-3">
  <div class="col-md-4">
    <div class="card bg-primary text-white">
      <div class="card-body">
        <h6>Tổng Doanh thu</h6>
        <h3><?= number_format($totalRevenue) ?> VNĐ</h3>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card bg-danger text-white">
      <div class="card-body">
        <h6>Tổng Chi phí</h6>
        <h3><?= number_format($totalExpense) ?> VNĐ</h3>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card bg-success text-white">
      <div class="card-body">
        <h6>Lợi nhuận</h6>
        <h3><?= number_format($totalProfit) ?> VNĐ</h3>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Báo cáo chi tiết theo Tour</h3>
      </div>
      <div class="card-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Booking</th>
              <th>Tour</th>
              <th>Ngày khởi hành</th>
              <th>Doanh thu</th>
              <th>Chi phí</th>
              <th>Lợi nhuận</th>
              <th>Tỷ lệ LN</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($revenueData as $r): ?>
              <tr>
                <td>#<?= $r['booking_id'] ?></td>
                <td><?= htmlspecialchars($r['tour_name']) ?></td>
                <td><?= date('d/m/Y', strtotime($r['start_date'])) ?></td>
                <td class="text-end"><?= number_format($r['revenue']) ?> VNĐ</td>
                <td class="text-end text-danger"><?= number_format($r['expense']) ?> VNĐ</td>
                <td class="text-end <?= $r['profit'] >= 0 ? 'text-success' : 'text-danger' ?>">
                  <?= number_format($r['profit']) ?> VNĐ
                </td>
                <td class="text-center">
                  <?php 
                  $profitRate = $r['revenue'] > 0 ? ($r['profit'] / $r['revenue']) * 100 : 0;
                  ?>
                  <span class="badge <?= $profitRate >= 20 ? 'bg-success' : ($profitRate >= 10 ? 'bg-warning' : 'bg-danger') ?>">
                    <?= number_format($profitRate, 1) ?>%
                  </span>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot class="table-info">
            <tr>
              <th colspan="3" class="text-end">TỔNG CỘNG:</th>
              <th class="text-end"><?= number_format($totalRevenue) ?> VNĐ</th>
              <th class="text-end text-danger"><?= number_format($totalExpense) ?> VNĐ</th>
              <th class="text-end text-success"><?= number_format($totalProfit) ?> VNĐ</th>
              <th class="text-center">
                <?php $avgRate = $totalRevenue > 0 ? ($totalProfit / $totalRevenue) * 100 : 0; ?>
                <?= number_format($avgRate, 1) ?>%
              </th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>

<?php
$content = ob_get_clean();

view('layouts.AdminLayout', [
    'title' => 'Báo cáo Doanh thu',
    'pageTitle' => 'Báo cáo Doanh thu & Lợi nhuận',
    'content' => $content,
    'breadcrumb' => [
        ['label' => 'Báo cáo Doanh thu', 'url' => BASE_URL . '?act=admin/revenue', 'active' => true],
    ],
]);
?>
