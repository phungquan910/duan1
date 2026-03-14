<?php
ob_start();
?>

<div class="row mb-3">
  <div class="col-12">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDiaryModal">
      <i class="bi bi-plus-circle"></i> Ghi nhật ký mới
    </button>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h3 class="card-title">Nhật ký Tour</h3>
  </div>
  <div class="card-body">
    <?php if (empty($diaries)): ?>
      <p class="text-muted text-center">Chưa có nhật ký nào</p>
    <?php else: ?>
      <?php foreach ($diaries as $d): ?>
        <div class="card mb-3">
          <div class="card-header d-flex justify-content-between">
            <div>
              <strong><?= htmlspecialchars($d['title']) ?></strong>
              <?php if ($d['tour_name']): ?>
                <br><small class="text-muted"><?= htmlspecialchars($d['tour_name']) ?></small>
              <?php endif; ?>
            </div>
            <div class="text-end">
              <span class="text-muted">
                <i class="bi bi-calendar"></i> <?= date('d/m/Y', strtotime($d['diary_date'])) ?>
              </span>
              <br>
              <button class="btn btn-sm btn-outline-primary" onclick="editDiary(<?= $d['id'] ?>)">
                <i class="bi bi-pencil"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <p><?= nl2br(htmlspecialchars($d['content'])) ?></p>
            <?php if ($d['incidents']): ?>
              <div class="alert alert-warning">
                <strong>Sự cố:</strong> <?= nl2br(htmlspecialchars($d['incidents'])) ?>
              </div>
            <?php endif; ?>
            <?php if ($d['customer_feedback']): ?>
              <div class="alert alert-info">
                <strong>Phản hồi khách:</strong> <?= nl2br(htmlspecialchars($d['customer_feedback'])) ?>
              </div>
            <?php endif; ?>
            <small class="text-muted">
              <i class="bi bi-clock"></i> Ghi lúc: <?= date('d/m/Y H:i', strtotime($d['created_at'])) ?>
            </small>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

<!-- Add Diary Modal -->
<div class="modal fade" id="addDiaryModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form method="post" action="<?= BASE_URL ?>guide/save-diary">
        <div class="modal-header">
          <h5 class="modal-title">Ghi nhật ký Tour</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="diary_id" id="diary_id">
          <div class="mb-3">
            <label>Tour</label>
            <select name="booking_id" id="booking_id" class="form-select">
              <option value="">Chọn tour</option>
              <?php foreach ($bookings as $booking): ?>
                <option value="<?= $booking['id'] ?>">
                  <?= htmlspecialchars($booking['tour_name']) ?> - <?= date('d/m/Y', strtotime($booking['start_date'])) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label>Ngày <span class="text-danger">*</span></label>
            <input type="date" class="form-control" name="diary_date" id="diary_date" value="<?= date('Y-m-d') ?>" required>
          </div>
          <div class="mb-3">
            <label>Tiêu đề <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="title" id="title" placeholder="Ví dụ: Ngày 1 - Hà Nội" required>
          </div>
          <div class="mb-3">
            <label>Nội dung <span class="text-danger">*</span></label>
            <textarea class="form-control" name="content" id="content" rows="4" placeholder="Ghi chú hành trình, hoạt động..." required></textarea>
          </div>
          <div class="mb-3">
            <label>Sự cố (nếu có)</label>
            <textarea class="form-control" name="incidents" id="incidents" rows="3" placeholder="Ghi lại sự cố và cách xử lý..."></textarea>
          </div>
          <div class="mb-3">
            <label>Phản hồi khách hàng</label>
            <textarea class="form-control" name="customer_feedback" id="customer_feedback" rows="3" placeholder="Phản hồi, góp ý của khách..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
          <button type="submit" class="btn btn-primary">Lưu nhật ký</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function editDiary(id) {
  document.getElementById('diary_id').value = id;
  new bootstrap.Modal(document.getElementById('addDiaryModal')).show();
}
</script>

<?php
$content = ob_get_clean();
view('layouts.GuideLayout', [
    'title' => 'Nhật ký Tour',
    'pageTitle' => 'Nhật ký Tour',
    'content' => $content
]);
?>
