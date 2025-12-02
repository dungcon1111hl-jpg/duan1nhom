<?php require_once ROOT . '/views/header.php'; ?>

<div id="layoutSidenav_content">
<main>
<div class="container-fluid px-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mt-4 mb-3 border-bottom pb-2">
        <div>
            <h1 class="m-0 text-primary fw-bold text-uppercase">
                <i class="fas fa-luggage-cart me-2"></i>Quản Lí Đặt Tour
            </h1>
            <ol class="breadcrumb mb-0 mt-1 bg-transparent p-0">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>?act=admin" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active text-muted">Danh sách Booking</li>
            </ol>
        </div>

        <a href="<?= BASE_URL ?>?act=booking-create" 
           class="btn btn-primary shadow-sm rounded-pill px-4">
            <i class="fas fa-plus-circle me-2"></i> Thêm Booking Mới
        </a>
    </div>

    <!-- CONFIG -->
    <?php 
        $loaiBookingMap = [
            'LE'     => ['label' => 'Khách Lẻ',   'badge' => 'bg-warning text-dark'],
            'DOAN'   => ['label' => 'Đặt Đoàn',   'badge' => 'bg-info text-dark'],
        ];

        $statusConfig = [
            'CHO_XAC_NHAN' => ['title' => 'Chờ xác nhận', 'class' => 'bg-warning text-dark', 'icon' => 'fas fa-clock'],
            'DA_COC'       => ['title' => 'Đã cọc',       'class' => 'bg-primary text-white', 'icon' => 'fas fa-hand-holding-usd'],
            'HOAN_TAT'     => ['title' => 'Hoàn tất',     'class' => 'bg-success', 'icon' => 'fas fa-check-circle'],
            'DA_HUY'       => ['title' => 'Đã hủy',        'class' => 'bg-danger', 'icon' => 'fas fa-times-circle'],
        ];
    ?>

    <!-- TABLE -->
    <div class="card shadow border-0 rounded-3 mb-4">
    <div class="card-header bg-white py-3">
        <h6 class="mb-0 fw-bold text-primary">
            <i class="fas fa-list me-2"></i>DANH SÁCH BOOKING
        </h6>
    </div>

    <div class="card-body p-0">
    <div class="table-responsive">

    <table id="datatablesSimple" class="table table-striped table-hover align-middle mb-0">

        <thead class="bg-light text-secondary">
            <tr>
                <th class="text-center py-3" style="width: 5%;">ID</th>
                <th style="width: 20%;">Khách hàng</th>
                <th style="width: 25%;">Tour</th>
                <th class="text-center" style="width: 8%;">Loại</th>
                <th class="text-center" style="width: 10%;">Ngày đặt</th>
                <th class="text-center" style="width: 10%;">Số lượng</th>
                <th class="text-end" style="width: 12%;">Tổng tiền</th>
                <th class="text-center" style="width: 12%;">Trạng thái</th>
                <th class="text-center" style="width: 8%;">#</th>
            </tr>
        </thead>

        <tbody>

        <?php if (!empty($bookings)): foreach ($bookings as $bk): ?>

            <?php 
                $slNL  = (int)($bk['so_luong_nguoi_lon']);
                $slTE  = (int)($bk['so_luong_tre_em']);
                $slEB  = (int)($bk['so_luong_em_be'] ?? 0);
                $tong  = $slNL + $slTE + $slEB;

                $tenTour = $bk['snapshot_ten_tour'] ?: ($bk['ten_tour_hien_tai'] ?? 'Tour đã xóa');
                $status  = $statusConfig[$bk['trang_thai']] ?? $statusConfig['CHO_XAC_NHAN'];
                $loai    = $loaiBookingMap[$bk['loai_booking']] ?? $loaiBookingMap['LE'];
            ?>

            <tr>
                <td class="text-center fw-bold text-muted">#<?= $bk['id'] ?></td>

                <!-- KHÁCH -->
                <td>
                    <div class="fw-bold"><?= htmlspecialchars($bk['snapshot_kh_ho_ten']) ?></div>
                    <div class="small text-muted"><?= $bk['snapshot_kh_so_dien_thoai'] ?></div>
                </td>

                <!-- TOUR -->
                <td>
                    <span class="fw-semibold text-primary">
                        <?= htmlspecialchars($tenTour) ?>
                    </span>
                </td>

                <!-- LOẠI -->
                <td class="text-center">
                    <span class="badge rounded-pill <?= $loai['badge'] ?>">
                        <?= $loai['label'] ?>
                    </span>
                </td>

                <!-- NGÀY ĐẶT -->
                <td class="text-center">
                    <div><?= date('d/m/Y', strtotime($bk['ngay_dat'])) ?></div>
                    <div class="small text-muted"><?= date('H:i', strtotime($bk['ngay_dat'])) ?></div>
                </td>

                <!-- SỐ LƯỢNG -->
                <td class="text-center">
                    <strong><?= $tong ?> khách</strong><br>
                    <small class="text-muted"><?= $slNL ?> NL - <?= $slTE ?> TE - <?= $slEB ?> EB</small>
                </td>

                <!-- TỔNG TIỀN -->
                <td class="text-end fw-bold text-danger">
                    <?= number_format($bk['tong_tien'], 0, ',', '.') ?> ₫
                </td>

                <!-- TRẠNG THÁI -->
                <td class="text-center">
                    <span class="badge <?= $status['class'] ?> px-3 py-2">
                        <i class="<?= $status['icon'] ?> me-1"></i>
                        <?= $status['title'] ?>
                    </span>
                </td>

                <!-- ACTION -->
                <td class="text-center">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light border dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-cog"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>?act=booking-detail&id=<?= $bk['id'] ?>"><i class="fas fa-eye text-info me-2"></i> Xem chi tiết</a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>?act=booking-edit&id=<?= $bk['id'] ?>"><i class="fas fa-edit text-warning me-2"></i> Chỉnh sửa</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" onclick="return confirm('Xóa đơn này?')" href="<?= BASE_URL ?>?act=booking-delete&id=<?= $bk['id'] ?>"><i class="fas fa-trash-alt me-2"></i> Xóa</a></li>
                        </ul>
                    </div>
                </td>
            </tr>

        <?php endforeach; else: ?>

            <tr>
                <td colspan="9" class="text-center py-5 text-muted">
                    <i class="fas fa-inbox fa-3x mb-3"></i><br>
                    <strong>Chưa có booking nào</strong>
                </td>
            </tr>

        <?php endif; ?>

        </tbody>
    </table>

    </div></div></div>

</div>
</main>
</div>

<?php require_once ROOT . '/views/footer.php'; ?>
