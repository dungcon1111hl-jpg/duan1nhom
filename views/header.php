<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="Hệ thống quản lý Tour du lịch" />
        <meta name="author" content="" />
        <title>Dashboard - Quản trị viên</title>
        
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="<?= BASE_URL ?>views/css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

        <style>
            :root {
                --bs-body-font-size: 0.875rem; /* Giảm xuống 14px */
            }
            body {
                font-size: var(--bs-body-font-size);
            }
            /* Tinh chỉnh Menu trái */
            .sb-sidenav-menu .nav-link {
                font-size: 0.9rem;
            }
            .sb-sidenav-menu-heading {
                font-size: 0.75rem;
                font-weight: bold;
                color: rgba(255,255,255,0.4);
            }
            /* Tinh chỉnh Logo */
            .navbar-brand {
                font-size: 1.1rem;
            }
            /* Tinh chỉnh Tiêu đề trang */
            h1, .h1 { font-size: 1.5rem; font-weight: 600; }
            h2, .h2 { font-size: 1.25rem; font-weight: 600; }
            h3, .h3 { font-size: 1.1rem; }
            
            /* Tinh chỉnh Bảng và Form */
            .table { font-size: 0.85rem; }
            .form-control, .form-select, .btn { font-size: 0.875rem; }
            .input-group-text { font-size: 0.875rem; }
            
            /* Badge trạng thái nhỏ gọn */
            .badge { font-size: 0.75rem; font-weight: 500; padding: 0.4em 0.6em; }
            
            /* Dropdown menu */
            .dropdown-item { font-size: 0.875rem; }
        </style>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark shadow">
            <a class="navbar-brand ps-3 fw-bold text-uppercase" href="<?= BASE_URL ?>?act=admin">
                <i class="fas fa-globe-asia me-2"></i>Tour Manager
            </a>
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                <div class="input-group input-group-sm">
                    <input class="form-control" type="text" placeholder="Tìm kiếm nhanh..." aria-label="Search" />
                    <button class="btn btn-primary" type="button"><i class="fas fa-search"></i></button>
                </div>
            </form>
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle fa-lg fa-fw"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#!"><i class="fas fa-cog me-2 text-muted"></i>Cài đặt</a></li>
                        <li><a class="dropdown-item" href="#!"><i class="fas fa-history me-2 text-muted"></i>Nhật ký hoạt động</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>?act=logout"><i class="fas fa-sign-out-alt me-2"></i>Đăng xuất</a></li>
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
                            <a class="nav-link" href="<?= BASE_URL ?>?act=admin">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt text-info"></i></div>
                                Dashboard
                            </a>
                            
                            <div class="sb-sidenav-menu-heading">Sản phẩm</div>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseTour" aria-expanded="false" aria-controls="collapseTour">
                                <div class="sb-nav-link-icon"><i class="fas fa-suitcase-rolling text-warning"></i></div>
                                QUẢN LÝ TOUR
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseTour" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="<?= BASE_URL ?>?act=tours">Quản lý thông tin Tour</a>
                                   
                                </nav>
                            </div>

                            <div class="sb-sidenav-menu-heading">Điều hành</div>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseTrip" aria-expanded="false" aria-controls="collapseTrip">
                                <div class="sb-nav-link-icon"><i class="fas fa-plane-departure text-success"></i></div>
                                LỊCH & CHUYẾN ĐI
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseTrip" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="<?= BASE_URL ?>?act=lich-khoi-hanh">Lịch khởi hành</a>
                                    <a class="nav-link" href="<?= BASE_URL ?>?act=hdv-list">Hướng dẫn viên</a>
                                    <a class="nav-link" href="<?= BASE_URL ?>?act=lich-ban-hdv">Lịch bận HDV</a>
                                    <a class="nav-link" href="<?= BASE_URL ?>?act=phan-cong">Phân công nhân sự</a>
                                </nav>
                            </div>

                            <div class="sb-sidenav-menu-heading">Kinh doanh</div>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseBooking" aria-expanded="false" aria-controls="collapseBooking">
                                <div class="sb-nav-link-icon"><i class="fas fa-ticket-alt text-danger"></i></div>
                                ĐẶT TOUR (BOOKING)
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseBooking" aria-labelledby="headingThree" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="<?= BASE_URL ?>?act=bookings">Thông tin đặt tour</a>
                                    <a class="nav-link" href="<?= BASE_URL ?>?act=booking-history">Lịch sử booking</a>
                                    <a class="nav-link" href="<?= BASE_URL ?>?act=khach-hang">Thông tin khách hàng</a>
                                    <a class="nav-link" href="<?= BASE_URL ?>?act=ds-khach-tour">DS khách tham gia</a>
                                </nav>
                            </div>

                            <div class="sb-sidenav-menu-heading">Hậu cần</div>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseService" aria-expanded="false" aria-controls="collapseService">
                                <div class="sb-nav-link-icon"><i class="fas fa-concierge-bell text-info"></i></div>
                                DỊCH VỤ & CHI PHÍ
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseService" aria-labelledby="headingFour" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="<?= BASE_URL ?>?act=dich-vu">Dịch vụ phát sinh</a>
                                    <a class="nav-link" href="<?= BASE_URL ?>?act=chi-phi">Chi phí phát sinh</a>
                                    <a class="nav-link" href="<?= BASE_URL ?>?act=nha-cung-cap">Nhà cung cấp</a>
                                    <a class="nav-link" href="<?= BASE_URL ?>?act=lien-ket-ncc">Liên kết Tour - NCC</a>
                                </nav>
                            </div>

                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseOperation" aria-expanded="false" aria-controls="collapseOperation">
                                <div class="sb-nav-link-icon"><i class="fas fa-tasks text-primary"></i></div>
                                ĐIỀU HÀNH TOUR
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseOperation" aria-labelledby="headingFive" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="<?= BASE_URL ?>?act=diem-danh">Điểm danh khách</a>
                                    <a class="nav-link" href="<?= BASE_URL ?>?act=phan-phong">Phân phòng khách sạn</a>
                                    <a class="nav-link" href="<?= BASE_URL ?>?act=nhat-ky-tour">Nhật ký tour</a>
                                </nav>
                            </div>

                            <div class="sb-sidenav-menu-heading">Tài chính</div>
                            <a class="nav-link" href="<?= BASE_URL ?>?act=thanh-toan">
                                <div class="sb-nav-link-icon"><i class="fas fa-money-bill-wave text-success"></i></div>
                                THANH TOÁN
                            </a>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer py-2">
                        <div class="small text-muted">Đăng nhập bởi:</div>
                        <strong class="text-light"><?php echo isset($_SESSION['user_admin']['username']) ? $_SESSION['user_admin']['username'] : 'Admin'; ?></strong>
                    </div>
                </nav>
            </div>
            
            <div id="layoutSidenav_content">