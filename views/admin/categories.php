<?php
ob_start();
?>

<!--begin::Row-->
<div class="row">
  <div class="col-12">
    <!-- Default box -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Danh sách danh mục tour </h3>
        <div class="card-tools">
          <button
            type="button"
            class="btn btn-tool"
            data-lte-toggle="card-collapse"
            title="Collapse"
          >
            <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
            <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
          </button>
        </div>
      </div>
      <div class="card-body">
        <?php if(!empty($categories)): ?>
            <div class="table-reponsive">
                <table class="table table-border table-striped table-hover">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên</th>
                            <th>Mô tả</th>
                            <th>Trạng thái</th>
                            <th>Cập nhật</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach($categories as $index => $category) : ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= $category-> name?? '' ?></td>
                                <td><?= $category-> description?? '' ?></td>
                                <td><?= $category->status == 1 ? "Hoat dong" : "Dung hoat dong" ?></td>
                                <td><?= $category->updated_at ? date('H:i:s d-m-Y', strtotime($category->updated_at)) : '' ?></td>
                                <td>
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewModal<?= $category->id ?>">
                                        <i class="bi bi-eye"></i> Xem chi tiết
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>

                </table>
            </div>
        <?php endif ?>
      </div>
    </div>
  </div>
</div>

<?php foreach($categories as $category) : ?>
<div class="modal fade" id="viewModal<?= $category->id ?>" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Chi tiết Danh mục</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p><strong>Tên:</strong> <?= htmlspecialchars($category->name) ?></p>
        <p><strong>Mô tả:</strong> <?= htmlspecialchars($category->description ?? 'Không có') ?></p>
        <p><strong>Trạng thái:</strong> 
          <span class="badge <?= $category->status == 1 ? 'bg-success' : 'bg-warning' ?>">
            <?= $category->status == 1 ? 'Hoạt động' : 'Tạm dừng' ?>
          </span>
        </p>
        <p><strong>Ngày tạo:</strong> <?= date('H:i:s d-m-Y', strtotime($category->created_at)) ?></p>
        <p><strong>Cập nhật:</strong> <?= date('H:i:s d-m-Y', strtotime($category->updated_at)) ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
      </div>
    </div>
  </div>
</div>
<?php endforeach ?>


<!--end::Row-->

<?php
$content = ob_get_clean();

// Hiển thị layout với nội dung
view('layouts.AdminLayout', [
    'title' => $title,
    'pageTitle' => $title,
    'content' => $content,
    'breadcrumb' => [
        ['label' => 'Trang chủ', 'url' => BASE_URL . 'home', 'active' => true],
         ['label' => 'Danh mục', 'url' => BASE_URL . '', 'active' => true],
    ],
]);
?>
