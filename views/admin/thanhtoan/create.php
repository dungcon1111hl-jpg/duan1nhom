<?php require_once PATH_ROOT . '/views/header.php'; ?>

<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-center mt-5">
            <div class="card border-0 shadow-sm" style="width: 600px;">
                <div class="card-header bg-success text-white fw-bold py-3">
                    <i class="fas fa-file-invoice-dollar me-2"></i> Tạo Phiếu Thu Mới
                </div>
                <div class="card-body">
                    
                    <div class="alert alert-info d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Mã Booking: #<?= $booking['id'] ?></strong><br>
                            Khách: <?= htmlspecialchars($booking['ten_khach'] ?? $booking['ho_ten']) ?>
                        </div>
                        <div class="text-end">
                            <small>Còn thiếu:</small><br>
                            <strong class="text-danger fs-5">
                                <?= number_format($booking['tong_tien'] - $booking['da_thanh_toan']) ?> ₫
                            </strong>
                        </div>
                    </div>

                    <form action="index.php?act=thanhtoan-store" method="POST">
                        <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Số tiền thu (VNĐ) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="so_tien" class="form-control fw-bold text-success fs-4" 
                                       placeholder="0" required min="1000"
                                       value="<?= $booking['tong_tien'] - $booking['da_thanh_toan'] ?>">
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
                            <a href="index.php?act=thanhtoan-list&booking_id=<?= $booking['id'] ?>" class="btn btn-secondary">Hủy bỏ</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require_once PATH_ROOT . '/views/footer.php'; ?>