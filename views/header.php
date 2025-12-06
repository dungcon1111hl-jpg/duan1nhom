<?php
// Lấy action hiện tại để highlight menu
$act = $_GET['act'] ?? 'admin';
$role = $_SESSION['user_admin']['role'] ?? '';

// Hàm kiểm tra active cho menu cha (để mở collapse)
function isActiveGroup($act, $groupItems = []) {
    return in_array($act, $groupItems) ? 'show' : '';
}

// Hàm kiểm tra active cho menu con
function isActiveItem($act, $targetAct) {
    return $act === $targetAct ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="Hệ thống quản lý Tour du lịch" />
        <meta name="author" content="" />
        <title>Quản Trị Hệ Thống - Tour Manager</title>
        
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="<?= BASE_URL ?>views/css/styles.css" rel="stylesheet" />
        
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

        <style>
            :root { --bs-body-font-size: 0.9rem; }
            body { background-color: #f5f6f8; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
            
            /* Navbar Customization */
            .sb-topnav { background: #1a202c !important; height: 60px; }
            .navbar-brand { font-weight: 700; letter-spacing: 0.5px; font-size: 1.1rem; }
            
            /* Sidebar Customization */
            .sb-sidenav-dark { background-color: #2d3748; color: #cbd5e0; }
            .sb-sidenav-menu .nav-link { color: #a0aec0; transition: all 0.3s; font-weight: 500; }
            .sb-sidenav-menu .nav-link:hover { color: #fff; background-color: rgba(255,255,255,0.05); padding-left: 1.25rem; }
            .sb-sidenav-menu .nav-link.active { color: #fff; background: linear-gradient(90deg, #4f46e5 0%, #3730a3 100%); border-left: 4px solid #818cf8; }
            .sb-sidenav-menu-heading { color: #718096; font-weight: 800; font-size: 0.75rem; letter-spacing: 1px; margin-top: 10px; }
            
            /* Icons Colors */
            .icon-dashboard { color: #63b3ed; }
            .icon-product { color: #f6ad55; }
            .icon-operation { color: #68d391; }
            .icon-business { color: #fc8181; }
            .icon-finance { color: #f687b3; }
            .icon-system { color: #a3bffa; }
        </style>
    </head>
    
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark shadow-sm">
            <a class="navbar-brand ps-3" href="<?= BASE_URL ?>?act=admin">
                <i class="fas fa-paper-plane me-2 text-primary"></i>TOUR MANAGER
            </a>
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle"><i class="fas fa-bars"></i></button>
            
            <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                <div class="input-group">
                    <input class="form-control" type="text" placeholder="Tìm kiếm nhanh..." aria-label="Search" />
                    <button class="btn btn-primary" type="button"><i class="fas fa-search"></i></button>
                </div>
            </form>
            
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user_admin']['full_name'] ?? 'Admin') ?>&background=random" class="rounded-circle" width="30" height="30">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="navbarDropdown">
                        <li><h6 class="dropdown-header">Xin chào, <?= htmlspecialchars($_SESSION['user_admin']['full_name'] ?? 'Admin') ?></h6></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user-circle me-2 text-muted"></i>Hồ sơ cá nhân</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-key me-2 text-muted"></i>Đổi mật khẩu</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item text-danger fw-bold" href="<?= BASE_URL ?>?act=logout"><i class="fas fa-sign-out-alt me-2"></i>Đăng xuất</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            
                            <div class="sb-sidenav-menu-heading">Tổng quan</div>
                            <a class="nav-link <?= isActiveItem($act, 'admin') ?>" href="<?= BASE_URL ?>?act=admin">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt icon-dashboard"></i></div>
                                Dashboard
                            </a>
                            
                            <div class="sb-sidenav-menu-heading">Sản phẩm</div>
                            <a class="nav-link collapsed <?= in_array($act, ['tours', 'tour-create', 'tour-edit', 'tour-detail', 'danhmuc-list']) ? 'active' : '' ?>" href="#" data-bs-toggle="collapse" data-bs-target="#collapseTour">
                                <div class="sb-nav-link-icon"><i class="fas fa-map-marked-alt icon-product"></i></div>
                                Quản lý Tour
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse <?= isActiveGroup($act, ['tours', 'tour-create', 'tour-edit', 'tour-detail', 'danhmuc-list']) ?>" id="collapseTour" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link <?= isActiveItem($act, 'tours') ?>" href="<?= BASE_URL ?>?act=tours">Danh sách Tour</a>
                                    <a class="nav-link <?= isActiveItem($act, 'danhmuc-list') ?>" href="<?= BASE_URL ?>?act=danhmuc-list">Danh mục Tour</a>
                                </nav>
                            </div>

                            <div class="sb-sidenav-menu-heading">Điều hành</div>
                            <a class="nav-link collapsed <?= in_array($act, ['lich-khoi-hanh', 'lich-khoi-hanh-create', 'lich-detail', 'hdv-list', 'lich-ban-hdv']) ? 'active' : '' ?>" href="#" data-bs-toggle="collapse" data-bs-target="#collapseTrip">
                                <div class="sb-nav-link-icon"><i class="fas fa-bus icon-operation"></i></div>
                                Lịch & Nhân sự
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse <?= isActiveGroup($act, ['lich-khoi-hanh', 'lich-khoi-hanh-create', 'lich-detail', 'hdv-list', 'lich-ban-hdv']) ?>" id="collapseTrip" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link <?= isActiveItem($act, 'lich-khoi-hanh') ?>" href="<?= BASE_URL ?>?act=lich-khoi-hanh">Lịch khởi hành</a>
                                    <a class="nav-link <?= isActiveItem($act, 'hdv-list') ?>" href="<?= BASE_URL ?>?act=hdv-list">Hướng dẫn viên</a>
                                    <a class="nav-link <?= isActiveItem($act, 'lich-ban-hdv') ?>" href="<?= BASE_URL ?>?act=lich-ban-hdv">Lịch bận HDV</a>
                                </nav>
                            </div>

                            <a class="nav-link collapsed <?= in_array($act, ['diem-danh', 'phan-phong', 'nhat-ky-tour']) ? 'active' : '' ?>" href="#" data-bs-toggle="collapse" data-bs-target="#collapseTourOps">
                                <div class="sb-nav-link-icon"><i class="fas fa-clipboard-list icon-operation"></i></div>
                                Điều hành Tour
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse <?= isActiveGroup($act, ['diem-danh', 'phan-phong', 'nhat-ky-tour']) ?>" id="collapseTourOps" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link <?= isActiveItem($act, 'diem-danh') ?>" href="<?= BASE_URL ?>?act=diem-danh">
                                        <i class="fas fa-user-check me-2"></i>Điểm danh khách
                                    </a>
                                    <a class="nav-link <?= isActiveItem($act, 'phan-phong') ?>" href="<?= BASE_URL ?>?act=phan-phong">
                                        <i class="fas fa-bed me-2"></i>Phân phòng KS
                                    </a>
                                    <a class="nav-link <?= isActiveItem($act, 'nhat-ky-tour') ?>" href="<?= BASE_URL ?>?act=nhat-ky-tour">
                                        <i class="fas fa-book me-2"></i>Nhật ký tour
                                    </a>
                                </nav>
                            </div>

                            <div class="sb-sidenav-menu-heading">Kinh doanh</div>
                            <a class="nav-link collapsed <?= in_array($act, ['booking-list', 'booking-create', 'booking-edit', 'booking-detail', 'khach-hang']) ? 'active' : '' ?>" href="#" data-bs-toggle="collapse" data-bs-target="#collapseBooking">
                                <div class="sb-nav-link-icon"><i class="fas fa-users icon-business"></i></div>
                                Khách & Đơn hàng
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse <?= isActiveGroup($act, ['booking-list', 'booking-create', 'booking-edit', 'booking-detail', 'khach-hang']) ?>" id="collapseBooking" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link <?= isActiveItem($act, 'booking-list') ?>" href="<?= BASE_URL ?>?act=booking-list">Danh sách Booking</a>
                                    <a class="nav-link <?= isActiveItem($act, 'khach-hang') ?>" href="<?= BASE_URL ?>?act=khach-hang">Danh sách Khách hàng</a>
                                </nav>
                            </div>

                            <div class="sb-sidenav-menu-heading">Hậu cần & Tài chính</div>
                            
                            <a class="nav-link <?= isActiveItem($act, 'dichvu-list') ?>" href="<?= BASE_URL ?>?act=dichvu-list">
                                <div class="sb-nav-link-icon"><i class="fas fa-concierge-bell icon-operation"></i></div>
                                Dịch vụ phát sinh
                            </a>
                            
                            <a class="nav-link <?= isActiveItem($act, 'nha-cung-cap') ?>" href="<?= BASE_URL ?>?act=nha-cung-cap">
                                <div class="sb-nav-link-icon"><i class="fas fa-handshake icon-product"></i></div>
                                Nhà cung cấp
                            </a>

                            <?php if($role == 'admin'): ?>
                                <a class="nav-link <?= isActiveItem($act, 'thanhtoan-list') ?>" href="<?= BASE_URL ?>?act=thanhtoan-list">
                                    <div class="sb-nav-link-icon"><i class="fas fa-file-invoice-dollar icon-finance"></i></div>
                                    Thanh toán / Thu chi
                                </a>
                            <?php endif; ?>

                            <?php if($role == 'admin'): ?>
                                <div class="sb-sidenav-menu-heading">Hệ thống</div>
                                
                                <a class="nav-link <?= isActiveItem($act, 'user-list') ?>" href="<?= BASE_URL ?>?act=user-list">
                                    <div class="sb-nav-link-icon"><i class="fas fa-users-cog icon-system"></i></div>
                                    Quản lý Tài khoản
                                </a>

                                <a class="nav-link <?= isActiveItem($act, 'baocao-tonghop') ?>" href="<?= BASE_URL ?>?act=baocao-tonghop">
                                    <div class="sb-nav-link-icon"><i class="fas fa-chart-line text-danger"></i></div>
                                    Báo cáo Thống kê
                                </a>
                            <?php endif; ?>

                        </div>
                    </div>
                    
                    <div class="sb-sidenav-footer">
                        <div class="small">Đang đăng nhập:</div>
                        <div class="fw-bold d-flex align-items-center">
                            <?= htmlspecialchars($_SESSION['user_admin']['username'] ?? 'User') ?>
                            <span class="badge bg-<?= ($role=='admin')?'danger':(($role=='guide')?'success':'info') ?> ms-2" style="font-size: 0.65em;">
                                <?= strtoupper($role) ?>
                            </span>
                        </div>
                    </div>
                </nav>
            </div>
            
            <div id="layoutSidenav_content">