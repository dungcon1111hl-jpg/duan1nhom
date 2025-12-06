<?php require_once PATH_ROOT . '/views/header.php'; ?>

<?php 
    // [FIX LỖI] Khởi tạo biến $booking_id ngay đầu file để tránh lỗi Undefined
    $booking_id = isset($selected_booking_id) ? $selected_booking_id : 0;
?>

<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-center mt-5">
            <div class="card border-0 shadow-sm" style="width: 700px;">
                
                <div class="card-header bg-success text-white fw-bold py-3">
                    <i class="fas fa-cart-plus me-2"></i> 
                    Thêm Dịch Vụ Phát Sinh 
                    <?php if($booking_id > 0): ?>
                        (Booking #<?= htmlspecialchars($booking_id) ?>)
                    <?php endif; ?>
                </div>

                <div class="card-body">
                    <form action="index.php?act=dichvu-store" method="POST">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Chọn Booking (Khách hàng) <span class="text-danger">*</span></label>
                            <select name="booking_id" class="form-select" required>
                                <option value="">-- Chọn đơn hàng --</option>
                                <?php if (!empty($bookings)): ?>
                                    <?php foreach ($bookings as $bk): 
                                        $id = $bk['id'];
                                        $ten = $bk['snapshot_kh_ho_ten'] ?? 'Khách lẻ';
                                        $selected = ($booking_id == $id) ? 'selected' : '';
                                    ?>
                                        <option value="<?= $id ?>" <?= $selected ?>>
                                            #<?= $id ?> - <?= htmlspecialchars($ten) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tên dịch vụ <span class="text-danger">*</span></label>
                            <input type="text" name="ten_dich_vu" class="form-control" required placeholder="VD: Vé cáp treo...">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Số lượng</label>
                                <input type="number" name="so_luong" id="so_luong" class="form-control" value="1" min="1" required oninput="calcTotal()">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Đơn giá (VNĐ)</label>
                                <input type="number" name="don_gia" id="don_gia" class="form-control" placeholder="0" required oninput="calcTotal()">
                            </div>
                        </div>

                        <div class="mb-3 p-3 bg-light rounded text-center">
                            <span class="fw-bold text-muted">THÀNH TIỀN: </span>
                            <span class="fs-4 fw-bold text-success" id="total_display">0 ₫</span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Ghi chú</label>
                            <textarea name="ghi_chu" class="form-control" rows="2"></textarea>
                        </div>

                        <div class="text-end">
                            <a href="index.php?act=dichvu-list" class="btn btn-secondary me-2">Hủy</a>
                            <button type="submit" class="btn btn-success fw-bold px-4">Lưu Dịch Vụ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
function calcTotal() {
    let sl = document.getElementById('so_luong').value || 0;
    let gia = document.getElementById('don_gia').value || 0;
    let total = sl * gia;
    document.getElementById('total_display').innerText = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(total);
}
</script>

<?php require_once PATH_ROOT . '/views/footer.php'; ?>