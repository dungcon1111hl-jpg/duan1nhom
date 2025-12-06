<?php require_once PATH_ROOT . '/views/header.php'; ?>

<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <div>
                <h2 class="fw-bold text-primary"><i class="fas fa-users me-2"></i>Danh Sách Đoàn</h2>
                <div class="text-muted">Tour: <?= htmlspecialchars($lich['ten_tour']) ?> (#<?= $lich['id'] ?>)</div>
            </div>
            <div>
                <button onclick="window.print()" class="btn btn-dark shadow-sm me-2"><i class="fas fa-print"></i> In Danh Sách</button>
                <a href="index.php?act=lich-detail&id=<?= $lich['id'] ?>" class="btn btn-secondary shadow-sm">Quay lại</a>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <table class="table table-bordered align-middle">
                    <thead class="table-light text-center">
                        <tr>
                            <th>STT</th>
                            <th>Họ và Tên</th>
                            <th>Năm sinh</th>
                            <th>Giới tính</th>
                            <th>SĐT Liên hệ</th>
                            <th>Ghi chú / Ăn kiêng</th>
                            <th>Nhóm / Booking</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $stt = 1;
                        if (!empty($passengers)): foreach($passengers as $p): ?>
                        <tr>
                            <td class="text-center"><?= $stt++ ?></td>
                            <td class="fw-bold"><?= htmlspecialchars($p['ho_ten']) ?></td>
                            <td class="text-center"><?= $p['ngay_sinh'] ? date('Y', strtotime($p['ngay_sinh'])) : '-' ?></td>
                            <td class="text-center"><?= $p['gioi_tinh'] == 1 ? 'Nam' : 'Nữ' ?></td>
                            <td class="text-center"><?= $p['sdt_lien_he'] ?></td>
                            <td class="text-danger small"><?= htmlspecialchars($p['ghi_chu_dac_biet']) ?></td>
                            <td class="text-center small text-muted">#<?= $p['ma_booking'] ?></td>
                        </tr>
                        <?php endforeach; else: ?>
                            <tr><td colspan="7" class="text-center text-muted">Chưa có khách.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<style>
@media print {
    body * { visibility: hidden; }
    main, main * { visibility: visible; }
    main { position: absolute; left: 0; top: 0; width: 100%; }
    .btn, .breadcrumb { display: none !important; }
}
</style>

<?php require_once PATH_ROOT . '/views/footer.php'; ?>