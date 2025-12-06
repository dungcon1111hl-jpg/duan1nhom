<?php require_once PATH_ROOT . '/views/header.php'; ?>

<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-center mt-5">
            <div class="card border-0 shadow-sm" style="width: 600px;">
                <div class="card-header bg-success text-white fw-bold py-3">
                    <i class="fas fa-file-invoice-dollar me-2"></i> Tạo Phiếu Thu Mới
                </div>
                <div class="card-body">
                    
                    <form action="index.php?act=thanhtoan-store" method="POST">
                        
                        <?php if (!empty($booking)): ?>
                            <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                            <div class="alert alert-info mb-3">
                                <div><strong>Booking #<?= $booking['id'] ?></strong> - <?= htmlspecialchars($booking['snapshot_kh_ho_ten'] ?? 'N/A') ?></div>
                                <div class="mt-1">
                                    Tổng tiền: <strong><?= number_format($booking['tong_tien']) ?> ₫</strong> 
                                    <span class="mx-2">|</span> 
                                    Đã thanh toán: <strong><?= number_format($booking['da_thanh_toan']) ?> ₫</strong>
                                </div>
                                <div class="mt-2 text-danger fw-bold border-top pt-2">
                                    Cần thu: <?= number_format($booking['tong_tien'] - $booking['da_thanh_toan']) ?> ₫
                                </div>
                            </div>
                            <?php $goi_y_tien = $booking['tong_tien'] - $booking['da_thanh_toan']; ?>

                        <?php else: ?>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Chọn Booking cần thu tiền <span class="text-danger">*</span></label>
                                <select name="booking_id" class="form-select" required id="selectBooking" onchange="updateDebt(this)">
                                    <option value="">-- Chọn khách hàng / Tour --</option>
                                    <?php if(!empty($dsBooking)): foreach($dsBooking as $b): 
                                        $con_thieu = $b['tong_tien'] - $b['da_thanh_toan'];
                                        if($con_thieu <= 0) continue; // Ẩn những đơn đã thanh toán đủ
                                    ?>
                                        <option value="<?= $b['id'] ?>" data-debt="<?= $con_thieu ?>">
                                            #<?= $b['id'] ?> - <?= htmlspecialchars($b['snapshot_kh_ho_ten']) ?> 
                                            (Thiếu: <?= number_format($con_thieu) ?> ₫)
                                        </option>
                                    <?php endforeach; endif; ?>
                                </select>
                            </div>
                            <?php $goi_y_tien = 0; ?>
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Số tiền thu (VNĐ) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="so_tien" id="inputMoney" 
                                       class="form-control fw-bold text-success fs-4" 
                                       placeholder="0" required min="1000"
                                       value="<?= $goi_y_tien > 0 ? $goi_y_tien : '' ?>">
                                <span class="input-group-text">₫</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Phương thức thanh toán</label>
                            <select name="phuong_thuc" class="form-select">
                                <option value="TIEN_MAT">Tiền mặt</option>
                                <option value="CHUYEN_KHOAN">Chuyển khoản ngân hàng</option>
                                <option value="THE">Quẹt thẻ (POS)</option>
                                <option value="CONG_NO">Ghi nợ</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Ghi chú</label>
                            <textarea name="ghi_chu" class="form-control" rows="3" placeholder="VD: Thu tiền cọc đợt 1..."></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success fw-bold py-2">LƯU PHIẾU THU</button>
                            <a href="index.php?act=thanhtoan-list" class="btn btn-secondary">Hủy bỏ</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
// Tự động điền số tiền khi chọn booking ở Dropdown
function updateDebt(select) {
    var option = select.options[select.selectedIndex];
    var debt = option.getAttribute('data-debt');
    if (debt) {
        document.getElementById('inputMoney').value = debt;
    }
}
</script>

<?php require_once PATH_ROOT . '/views/footer.php'; ?>