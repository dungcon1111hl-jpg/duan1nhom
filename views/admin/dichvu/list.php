<?php require_once PATH_ROOT . '/views/header.php'; ?>

<main>
    <div class="container-fluid px-4">

        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <div>
                <h2 class="fw-bold text-primary">Quản lý Dịch vụ phát sinh</h2>
                <div class="text-muted">
                    <?php if ($booking_id > 0): ?>
                        Đang xem cho Booking ID: <strong>#<?= htmlspecialchars($booking_id) ?></strong>
                    <?php else: ?>
                        Danh sách tất cả dịch vụ
                    <?php endif; ?>
                </div>
            </div>

            <div>
                <a href="index.php?act=dichvu-create<?= $booking_id > 0 ? '&booking_id=' . $booking_id : '' ?>" 
                   class="btn btn-success shadow-sm">
                    <i class="fas fa-plus me-1"></i> Thêm dịch vụ
                </a>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <?php if ($booking_id == 0): ?>
                                    <th class="ps-4">Booking ID</th>
                                <?php endif; ?>

                                <th class="<?= $booking_id > 0 ? 'ps-4' : '' ?>">Tên dịch vụ</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-end">Đơn giá</th>
                                <th class="text-end">Thành tiền</th>
                                <th class="text-center">Hành động</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (!empty($dichvus)): ?>
                                <?php foreach ($dichvus as $dv): ?>
                                <tr>
                                    <?php if ($booking_id == 0): ?>
                                        <td class="ps-4">
                                            <a href="index.php?act=dichvu-list&booking_id=<?= $dv['booking_id'] ?>" 
                                               class="badge bg-secondary text-decoration-none">
                                                #<?= htmlspecialchars($dv['booking_id']) ?>
                                            </a>
                                        </td>
                                    <?php endif; ?>

                                    <td class="<?= $booking_id > 0 ? 'ps-4' : '' ?> fw-bold text-dark">
                                        <?= htmlspecialchars($dv['ten_dich_vu']) ?>
                                        <?php if (!empty($dv['ghi_chu'])): ?>
                                            <br>
                                            <small class="text-muted fst-italic">
                                                <i class="fas fa-info-circle me-1"></i>
                                                <?= htmlspecialchars($dv['ghi_chu']) ?>
                                            </small>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-center"><?= $dv['so_luong'] ?></td>
                                    <td class="text-end"><?= number_format($dv['don_gia']) ?> ₫</td>
                                    <td class="text-end fw-bold text-success"><?= number_format($dv['thanh_tien']) ?> ₫</td>

                                    <td class="text-center">
                                        <a href="index.php?act=dichvu-edit&id=<?= $dv['id'] ?>" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="index.php?act=dichvu-delete&id=<?= $dv['id'] ?>&booking_id=<?= $dv['booking_id'] ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Xóa dịch vụ này?');">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="<?= $booking_id == 0 ? 6 : 5 ?>" class="text-center py-4 text-muted">
                                        Chưa có dữ liệu.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>

                        <?php if ($booking_id > 0): ?>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="3" class="text-end fw-bold pe-3">TỔNG CỘNG:</td>
                                <td class="text-end fw-bold text-danger fs-5">
                                    <?= number_format($tong_tien) ?> ₫
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>

    </div>
</main>

<?php require_once PATH_ROOT . '/views/footer.php'; ?>