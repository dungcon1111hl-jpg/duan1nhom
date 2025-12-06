<?php require_once PATH_ROOT . '/views/header.php'; ?>

<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <h2 class="fw-bold text-success"><i class="fas fa-money-check-alt me-2"></i>Quản lý Tài Chính</h2>
            <a href="index.php?act=thanhtoan-create" class="btn btn-success shadow-sm"><i class="fas fa-plus me-1"></i> Tạo Phiếu Thu</a>
        </div>

        <?php 
            $tong_thu = 0;
            if(!empty($list_payment)) foreach($list_payment as $pay) $tong_thu += $pay['so_tien'];
        ?>
        <div class="card bg-success text-white mb-4" style="max-width: 400px;">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-white-50 small fw-bold">TỔNG DOANH THU</div>
                    <div class="fs-2 fw-bold"><?= number_format($tong_thu) ?> ₫</div>
                </div>
                <i class="fas fa-hand-holding-usd fa-3x opacity-25"></i>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="datatablesSimple" class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Mã GD</th>
                                <th>Ngày thu</th>
                                <th>Khách hàng / Tour</th>
                                <th>Số tiền</th>
                                <th>Phương thức</th>
                                <th>Người thu</th>
                                <th class="text-end">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($list_payment)): foreach($list_payment as $row): ?>
                            <tr>
                                <td>
                                    <span class="badge bg-light text-dark border font-monospace">
                                        <?= !empty($row['ma_giao_dich']) ? htmlspecialchars($row['ma_giao_dich']) : ('OLD-'.$row['id']) ?>
                                    </span>
                                </td>
                                <td><?= date('d/m H:i', strtotime($row['ngay_thanh_toan'])) ?></td>
                                <td>
                                    <div class="fw-bold text-primary"><?= htmlspecialchars($row['ten_khach'] ?? 'N/A') ?></div>
                                    <small class="text-muted fst-italic">Booking #<?= $row['booking_id'] ?> - <?= htmlspecialchars($row['ten_tour'] ?? '') ?></small>
                                </td>
                                <td class="fw-bold text-success">+ <?= number_format($row['so_tien']) ?> ₫</td>
                                <td>
                                    <?php 
                                        $pt = $row['phuong_thuc'];
                                        $bg = ($pt == 'CHUYEN_KHOAN') ? 'info' : (($pt == 'TIEN_MAT') ? 'secondary' : 'warning');
                                    ?>
                                    <span class="badge bg-<?= $bg ?>"><?= $pt ?></span>
                                </td>
                                <td><?= htmlspecialchars($row['ten_nhan_vien'] ?? 'Admin') ?></td>
                                <td class="text-end">
                                    <a href="index.php?act=thanhtoan-edit&id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-warning border-0"><i class="fas fa-edit"></i></a>
                                    <a href="index.php?act=thanhtoan-delete&id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Hủy giao dịch này?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                                <tr><td colspan="7" class="text-center py-4">Chưa có giao dịch nào.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require_once PATH_ROOT . '/views/footer.php'; ?>