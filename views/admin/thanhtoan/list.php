<?php require_once PATH_ROOT . '/views/header.php'; ?>

<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <div>
                <h2 class="fw-bold text-success">Lịch sử thanh toán</h2>
                <div class="fs-5">
                    Mã Booking: <span class="badge bg-warning text-dark">#<?= $booking['id'] ?></span>
                    <span class="text-muted mx-2">|</span>
                    Khách hàng: <strong><?= htmlspecialchars($booking['ten_khach'] ?? $booking['ho_ten'] ?? 'N/A') ?></strong>
                </div>
            </div>
            <div>
                <a href="index.php?act=bookings" class="btn btn-secondary me-2">
                    <i class="fas fa-arrow-left"></i> Về danh sách Booking
                </a>
                <a href="index.php?act=thanhtoan-create&booking_id=<?= $booking['id'] ?>" class="btn btn-success shadow-sm">
                    <i class="fas fa-plus me-1"></i> Tạo phiếu thu
                </a>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="me-3">
                                <div class="text-white-75 small">Tổng giá trị đơn hàng</div>
                                <div class="fs-4 fw-bold"><?= number_format($booking['tong_tien']) ?> ₫</div>
                            </div>
                            <i class="fas fa-money-bill-wave fa-2x text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="me-3">
                                <div class="text-white-75 small">Đã thanh toán</div>
                                <div class="fs-4 fw-bold"><?= number_format($booking['da_thanh_toan']) ?> ₫</div>
                            </div>
                            <i class="fas fa-check-circle fa-2x text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <?php $con_thieu = $booking['tong_tien'] - $booking['da_thanh_toan']; ?>
                <div class="card <?= $con_thieu > 0 ? 'bg-danger' : 'bg-secondary' ?> text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="me-3">
                                <div class="text-white-75 small">Còn thiếu</div>
                                <div class="fs-4 fw-bold"><?= number_format($con_thieu) ?> ₫</div>
                            </div>
                            <i class="fas fa-exclamation-circle fa-2x text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Mã GD</th>
                                <th>Ngày thu</th>
                                <th>Số tiền</th>
                                <th>Phương thức</th>
                                <th>Người thu</th>
                                <th>Ghi chú</th>
                                <th class="text-end">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($lich_su)): foreach ($lich_su as $ls): ?>
                            <tr>
                                <td><span class="text-muted small">#<?= $ls['id'] ?></span></td>
                                <td><?= date('d/m/Y H:i', strtotime($ls['ngay_thanh_toan'])) ?></td>
                                <td class="fw-bold text-success">+ <?= number_format($ls['so_tien']) ?> ₫</td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        <?= $ls['phuong_thuc'] ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($ls['ten_nhan_vien'] ?? 'Admin') ?></td>
                                <td class="text-muted small"><?= htmlspecialchars($ls['ghi_chu']) ?></td>
                                <td class="text-end">
                                    <a href="index.php?act=thanhtoan-edit&id=<?= $ls['id'] ?>" class="btn btn-sm btn-warning text-white" title="Sửa"><i class="fas fa-edit"></i></a>
                                    <a href="index.php?act=thanhtoan-delete&id=<?= $ls['id'] ?>&booking_id=<?= $booking['id'] ?>" 
                                       class="btn btn-sm btn-danger" onclick="return confirm('Hủy phiếu thu này?')" title="Xóa"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                                <tr><td colspan="7" class="text-center py-5 text-muted">Chưa có giao dịch nào.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require_once PATH_ROOT . '/views/footer.php'; ?>