<?php
ob_start();
?>

<div class="row mb-3">
  <div class="col-12">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
      <i class="bi bi-plus-circle"></i> Thêm HDV
    </button>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Danh sách Hướng dẫn viên (<?= count($guides) ?>)</h3>
      </div>
      <div class="card-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Họ tên</th>
              <th>Điện thoại</th>
              <th>Nhóm</th>
              <th>Chuyên môn</th>
              <th>Đánh giá</th>
              <th>Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($guides as $g): ?>
              <tr>
                <td>#<?= $g['id'] ?></td>
                <td><?= htmlspecialchars($g['name'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($g['phone'] ?? '') ?></td>
                <td>
                  <span class="badge <?= $g['group_type'] == 'quốc tế' ? 'bg-primary' : 'bg-success' ?>">
                    <?= ucfirst($g['group_type'] ?? 'N/A') ?>
                  </span>
                </td>
                <td><?= htmlspecialchars($g['speciality'] ?? '') ?></td>
                <td>
                  <?php if ($g['rating']): ?>
                    <span class="badge bg-warning"><?= $g['rating'] ?>/5.0</span>
                  <?php endif; ?>
                </td>
                <td>
                  <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewModal<?= $g['id'] ?>">
                    <i class="bi bi-eye"></i>
                  </button>
                  <a href="<?= BASE_URL ?>admin/guides?delete=<?= $g['id'] ?>" 
                     class="btn btn-sm btn-danger" 
                     onclick="return confirm('Xóa HDV này?')">
                    <i class="bi bi-trash"></i>
                  </a>
                </td>
              </tr>

              <!-- View Modal -->
              <div class="modal fade" id="viewModal<?= $g['id'] ?>" tabindex="-1">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Hồ sơ HDV: <?= htmlspecialchars($g['name'] ?? '') ?></h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                      <div class="row">
                        <div class="col-md-6">
                          <p><strong>Họ tên:</strong> <?= htmlspecialchars($g['name'] ?? '') ?></p>
                          <p><strong>Email:</strong> <?= htmlspecialchars($g['email'] ?? 'Chưa có') ?></p>
                          <p><strong>Điện thoại:</strong> <?= htmlspecialchars($g['phone'] ?? 'Chưa có') ?></p>
                          <p><strong>Ngày sinh:</strong> <?= $g['birthdate'] ? date('d/m/Y', strtotime($g['birthdate'])) : 'Chưa có' ?></p>
                        </div>
                        <div class="col-md-6">
                          <p><strong>Nhóm:</strong> <?= ucfirst($g['group_type'] ?? 'N/A') ?></p>
                          <p><strong>Chuyên môn:</strong> <?= htmlspecialchars($g['speciality'] ?? 'Chưa có') ?></p>
                          <p><strong>Đánh giá:</strong> <?= $g['rating'] ?? 'Chưa có' ?>/5.0</p>
                          <p><strong>Sức khỏe:</strong> <?= htmlspecialchars($g['health_status'] ?? 'Chưa có') ?></p>
                        </div>
                      </div>
                      <hr>
                      <p><strong>Chứng chỉ:</strong> 
                        <?php 
                        $cert = json_decode($g['certificate'] ?? '[]', true);
                        echo is_array($cert) ? implode(', ', $cert) : ($g['certificate'] ?? 'Chưa có');
                        ?>
                      </p>
                      <p><strong>Ngôn ngữ:</strong> 
                        <?php 
                        $langs = json_decode($g['languages'] ?? '[]', true);
                        echo is_array($langs) ? implode(', ', $langs) : ($g['languages'] ?? 'Chưa có');
                        ?>
                      </p>
                      <p><strong>Kinh nghiệm:</strong> <?= htmlspecialchars($g['experience'] ?? 'Chưa có') ?></p>
                    </div>
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
      <form method="post">
        <div class="modal-header">
          <h5 class="modal-title">Thêm HDV mới</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Họ tên <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="name" required>
          </div>
          <div class="mb-3">
            <label>Email</label>
            <input type="email" class="form-control" name="email">
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label>Ngày sinh</label>
              <input type="date" class="form-control" name="birthdate">
            </div>
            <div class="col-md-6 mb-3">
              <label>Điện thoại</label>
              <input type="text" class="form-control" name="phone">
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label>Nhóm</label>
              <select class="form-select" name="group_type">
                <option value="">Chọn nhóm</option>
                <option value="nội địa">Nội địa</option>
                <option value="quốc tế">Quốc tế</option>
                <option value="chuyên tuyến">Chuyên tuyến</option>
                <option value="chuyên khách đoàn">Chuyên khách đoàn</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label>Chuyên môn</label>
              <input type="text" class="form-control" name="speciality">
            </div>
          </div>
          <div class="mb-3">
            <label>Chứng chỉ</label>
            <input type="text" class="form-control" name="certificate">
          </div>
          <div class="mb-3">
            <label>Ngôn ngữ</label>
            <input type="text" class="form-control" name="languages" placeholder="Ví dụ: Tiếng Anh, Tiếng Việt">
          </div>
          <div class="mb-3">
            <label>Kinh nghiệm</label>
            <textarea class="form-control" name="experience" rows="3"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
          <button type="submit" name="add_guide" class="btn btn-primary">Thêm</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php
$content = ob_get_clean();

view('layouts.AdminLayout', [
    'title' => 'Quản lý HDV',
    'pageTitle' => 'Quản lý Hướng dẫn viên',
    'content' => $content,
    'breadcrumb' => [
        ['label' => 'HDV', 'url' => BASE_URL . 'admin/guides', 'active' => true],
    ],
]);
?>
