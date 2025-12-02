<?php require_once PATH_ROOT . '/views/header.php'; ?>

<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-center mt-5">
            <div class="card border-0 shadow-sm" style="width: 600px;">
                <div class="card-header bg-warning text-white fw-bold py-3">
                    <i class="fas fa-edit me-2"></i> Sửa Phiếu Thu
                </div>
                <div class="card-body">
                    <form action="index.php?act=thanhtoan-update" method="POST">
                        <input type="hidden" name="id" value="<?= $payment['id'] ?>">
                        <input type="hidden" name="booking_id" value="<?= $payment['booking_id'] ?>">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Số tiền thu (VNĐ)</label>
                            <input type="number" name="so_tien" class="form-control fs-4 fw-bold text-success" 
                                   value="<?= (int)$payment['so_tien'] ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Phương thức thanh toán</label>
                            <select name="phuong_thuc" class="form-select">
                                <option value="TIEN_MAT" <?= $payment['phuong_thuc'] == 'TIEN_MAT' ? 'selected' : '' ?>>Tiền mặt</option>
                                <option value="CHUYEN_KHOAN" <?= $payment['phuong_thuc'] == 'CHUYEN_KHOAN' ? 'selected' : '' ?>>Chuyển khoản ngân hàng</option>
                                <option value="THE" <?= $payment['phuong_thuc'] == 'THE' ? 'selected' : '' ?>>Quẹt thẻ (POS)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Ghi chú</label>
                            <textarea name="ghi_chu" class="form-control" rows="3"><?= htmlspecialchars($payment['ghi_chu']) ?></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning fw-bold text-white">Cập nhật phiếu thu</button>
                            <a href="index.php?act=thanhtoan-list&booking_id=<?= $payment['booking_id'] ?>" class="btn btn-secondary">Hủy bỏ</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require_once PATH_ROOT . '/views/footer.php'; ?>