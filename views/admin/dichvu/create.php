<?php require_once PATH_ROOT . '/views/header.php'; ?>

<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-center mt-5">
            <div class="card border-0 shadow-sm" style="width: 600px;">
                <div class="card-header bg-white fw-bold py-3">
                    <i class="fas fa-plus-circle text-success me-2"></i> Thêm Dịch Vụ Mới (Booking #<?= $booking_id ?>)
                </div>
                <div class="card-body">
                    <form action="index.php?act=dichvu-store" method="POST">
                        <input type="hidden" name="booking_id" value="<?= $booking_id ?>">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tên dịch vụ <span class="text-danger">*</span></label>
                            <input type="text" name="ten_dich_vu" class="form-control" required placeholder="VD: Nâng hạng phòng view biển">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Số lượng</label>
                                <input type="number" name="so_luong" class="form-control" value="1" min="1" required oninput="calcTotal()">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Đơn giá (VNĐ)</label>
                                <input type="number" name="don_gia" class="form-control" placeholder="0" required oninput="calcTotal()">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Thành tiền (Tạm tính)</label>
                            <input type="text" id="total_preview" class="form-control bg-light text-success fw-bold" readonly value="0 ₫">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Ghi chú</label>
                            <textarea name="ghi_chu" class="form-control" rows="2" placeholder="Ghi chú thêm..."></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success fw-bold">Lưu Dịch Vụ</button>
                            <a href="index.php?act=dichvu-list&booking_id=<?= $booking_id ?>" class="btn btn-secondary">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
function calcTotal() {
    let sl = document.querySelector('[name="so_luong"]').value;
    let gia = document.querySelector('[name="don_gia"]').value;
    if(sl && gia) {
        let total = sl * gia;
        document.getElementById('total_preview').value = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(total);
    }
}
</script>
<?php require_once PATH_ROOT . '/views/footer.php'; ?>