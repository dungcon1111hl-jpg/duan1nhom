<?php require_once ROOT . '/views/header.php'; ?>

<div id="layoutSidenav_content">
<main>
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
        <h1 class="text-primary fw-bold h3"><i class="fas fa-info-circle me-2"></i>Chi tiết Booking #<?= $booking['id'] ?></h1>
        <a href="<?= BASE_URL ?>?act=booking-list" class="btn btn-secondary shadow-sm"><i class="fas fa-arrow-left"></i> Quay lại</a>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-primary text-white fw-bold">Thông tin Đặt Tour</div>
                <div class="card-body">
                    <p><strong>Tour:</strong> <span class="text-primary fw-bold"><?= htmlspecialchars($booking['snapshot_ten_tour']) ?></span></p>
                    <p><strong>Khách hàng:</strong> <?= htmlspecialchars($booking['snapshot_kh_ho_ten']) ?></p>
                    <p><strong>SĐT:</strong> <?= htmlspecialchars($booking['snapshot_kh_so_dien_thoai']) ?></p>
                    <p><strong>Ngày đặt:</strong> <?= date('d/m/Y H:i', strtotime($booking['ngay_dat'])) ?></p>
                    <p><strong>Số lượng:</strong> <?= $booking['so_luong_nguoi_lon'] ?> NL - <?= $booking['so_luong_tre_em'] ?> TE</p>
                    
                    <form action="<?= BASE_URL ?>?act=booking-update-status" method="POST" class="mt-3 p-3 bg-light rounded border d-flex gap-2 align-items-center">
                        <input type="hidden" name="id" value="<?= $booking['id'] ?>">
                        <strong class="text-nowrap">Trạng thái:</strong>
                        <select name="trang_thai" class="form-select form-select-sm">
                            <?php foreach(['CHO_XU_LY', 'DA_XAC_NHAN', 'DA_THANH_TOAN', 'HOAN_THANH', 'HUY'] as $st): ?>
                                <option value="<?= $st ?>" <?= $booking['trang_thai'] == $st ? 'selected' : '' ?>><?= $st ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button class="btn btn-success btn-sm"><i class="fas fa-check"></i> Lưu</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-success text-white fw-bold">Chi tiết Thanh toán</div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                        <span class="text-muted">Tổng giá trị đơn hàng:</span>
                        <span class="fs-5 fw-bold text-primary"><?= number_format($booking['tong_tien']) ?> ₫</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                        <span class="text-muted">Đã thanh toán / Đặt cọc:</span>
                        <span class="fs-5 fw-bold text-success"><?= number_format($booking['da_thanh_toan']) ?> ₫</span>
                    </div>

                    <?php $con_lai = $booking['tong_tien'] - $booking['da_thanh_toan']; ?>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Số tiền còn lại:</span>
                        <span class="fs-4 fw-bold <?= $con_lai > 0 ? 'text-danger' : 'text-secondary' ?>">
                            <?= number_format($con_lai) ?> ₫
                        </span>
                    </div>

                    <?php if ($con_lai > 0): ?>
                        <div class="alert alert-warning small mb-0">
                            <i class="fas fa-exclamation-triangle me-1"></i> Khách hàng cần thanh toán thêm <?= number_format($con_lai) ?> đ.
                        </div>
                    <?php else: ?>
                        <div class="alert alert-success small mb-0">
                            <i class="fas fa-check-circle me-1"></i> Đơn hàng đã thanh toán đủ.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white fw-bold">Danh sách hành khách (<?= count($guestList) ?>)</div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>#</th><th>Họ tên</th><th>Giới tính</th><th>Ngày sinh</th><th>Giấy tờ</th><th>Ghi chú</th></tr>
                </thead>
                <tbody>
                    <?php if(empty($guestList)): ?>
                        <tr><td colspan="6" class="text-center py-3 text-muted">Chưa có danh sách khách.</td></tr>
                    <?php else: foreach($guestList as $k => $g): ?>
                        <tr>
                            <td><?= $k+1 ?></td>
                            <td class="fw-bold"><?= htmlspecialchars($g['ho_ten']) ?></td>
                            <td><?= $g['gioi_tinh'] == 1 ? 'Nam' : 'Nữ' ?></td>
                            <td><?= $g['ngay_sinh'] ? date('d/m/Y', strtotime($g['ngay_sinh'])) : '-' ?></td>
                            <td><?= htmlspecialchars($g['so_giay_to']) ?></td>
                            <td><?= htmlspecialchars($g['ghi_chu']) ?></td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
</main>
</div>
<?php require_once ROOT . '/views/footer.php'; ?>   