<?php
ob_start();
?>

<div class="card">
  <div class="card-header">
    <h3 class="card-title">Lịch trình Tour được phân công</h3>
  </div>
  <div class="card-body">
    <?php if (empty($assignments)): ?>
      <p class="text-muted">Chưa có tour được phân công</p>
    <?php else: ?>
      <?php foreach ($assignments as $assignment): ?>
        <div class="card mb-3">
          <div class="card-header">
            <h5><?= htmlspecialchars($assignment['tour_name']) ?></h5>
            <small class="text-muted">
              Từ <?= date('d/m/Y', strtotime($assignment['start_date'])) ?> 
              đến <?= date('d/m/Y', strtotime($assignment['end_date'])) ?>
            </small>
          </div>
          <div class="card-body">
            <div class="mb-3">
              <span class="badge bg-info">
                <i class="bi bi-people"></i> <?= $assignment['total_customers'] ?? $assignment['num_people'] ?? 0 ?> khách
              </span>
              <span class="badge bg-secondary">
                <?= $assignment['booking_type'] ?? 'Khách lẻ' ?>
              </span>
            </div>
            
            <?php if ($assignment['customer_names']): ?>
              <div class="mb-3">
                <h6><i class="bi bi-people-fill"></i> Danh sách khách:</h6>
                <div class="border rounded p-2 bg-light">
                  <?php foreach (explode(', ', $assignment['customer_names']) as $name): ?>
                    <span class="badge bg-light text-dark me-1 mb-1">
                      <i class="bi bi-person"></i> <?= htmlspecialchars($name) ?>
                    </span>
                  <?php endforeach; ?>
                </div>
              </div>
            <?php endif; ?>
            
            <?php if ($assignment['schedule']): ?>
              <?php $schedule = json_decode($assignment['schedule'], true); ?>
              <?php if (isset($schedule['days'])): ?>
                <h6><i class="bi bi-list-check"></i> Lịch trình:</h6>
                <?php foreach ($schedule['days'] as $day): ?>
                  <div class="mb-2">
                    <strong>Ngày <?= date('d/m/Y', strtotime($day['date'])) ?>:</strong>
                    <ul class="mb-0">
                      <?php foreach ($day['activities'] as $activity): ?>
                        <li><?= htmlspecialchars($activity) ?></li>
                      <?php endforeach; ?>
                    </ul>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            <?php endif; ?>
            
            <?php if ($assignment['special_requirements']): ?>
              <div class="alert alert-info mt-2">
                <i class="bi bi-info-circle"></i>
                <strong>Yêu cầu đặc biệt:</strong><br>
                <?= htmlspecialchars($assignment['special_requirements']) ?>
              </div>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

<?php
$content = ob_get_clean();
view('layouts.GuideLayout', [
    'title' => 'Lịch trình Tour',
    'pageTitle' => 'Lịch trình Tour của tôi',
    'content' => $content
]);
?>
