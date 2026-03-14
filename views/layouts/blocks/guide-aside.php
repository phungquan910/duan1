<!--begin::Sidebar-->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
  <!--begin::Sidebar Brand-->
  <div class="sidebar-brand">
    <a href="<?= BASE_URL . 'guide/dashboard' ?>" class="brand-link">
      <img src="<?= asset('dist/assets/img/AdminLTELogo.png') ?>" alt="Logo" class="brand-image opacity-75 shadow" />
      <span class="brand-text fw-light">HDV - Quản Lý Tour</span>
    </a>
  </div>
  <!--end::Sidebar Brand-->
  <!--begin::Sidebar Wrapper-->
  <div class="sidebar-wrapper">
    <nav class="mt-2">
      <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
          <a href="<?= BASE_URL . 'guide/dashboard' ?>" class="nav-link">
            <i class="nav-icon bi bi-speedometer"></i>
            <p>Dashboard</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= BASE_URL . 'guide/schedule' ?>" class="nav-link">
            <i class="nav-icon bi bi-calendar-event"></i>
            <p>Lịch trình Tour</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= BASE_URL . 'guide/customers' ?>" class="nav-link">
            <i class="nav-icon bi bi-people"></i>
            <p>Danh sách khách</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= BASE_URL . 'guide/diary' ?>" class="nav-link">
            <i class="nav-icon bi bi-journal-text"></i>
            <p>Nhật ký Tour</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= BASE_URL . 'guide/checkin' ?>" class="nav-link">
            <i class="nav-icon bi bi-check-circle"></i>
            <p>Check-in khách</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= BASE_URL . 'guide/requirements' ?>" class="nav-link">
            <i class="nav-icon bi bi-exclamation-circle"></i>
            <p>Yêu cầu đặc biệt</p>
          </a>
        </li>
        <li class="nav-header">HỆ THỐNG</li>
        <li class="nav-item">
          <a href="<?= BASE_URL . 'logout' ?>" class="nav-link">
            <i class="nav-icon bi bi-box-arrow-right"></i>
            <p>Đăng xuất</p>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>
