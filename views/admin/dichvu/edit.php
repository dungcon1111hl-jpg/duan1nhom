<?php require_once PATH_ROOT . '/views/header.php'; ?>

<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-center mt-5">
            <div class="card border-0 shadow-sm" style="width: 600px;">
                <div class="card-header bg-warning text-white fw-bold py-3">
                    <i class="fas fa-edit me-2"></i> Sửa Dịch Vụ
                </div>
                <div class="card-body">
                    <form action="index.php?act=dichvu-update" method="POST">
                        <input type="hidden" name="id" value="<?= $dichvu['id'] ?>">
                        <input type="hidden" name="booking_id" value="<?= $dichvu['booking_id'] ?>">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tên dịch vụ</label>
                            <input type="text" name="ten_dich_vu" class="form-control" value="<?= htmlspecialchars($dichvu['ten_dich_vu']) ?>" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Số lượng</label>
                                <input type="number" name="so_luong" id="so_luong" class="form-control" value="<?= $dichvu['so_luong'] ?>" min="1" required oninput="calculateTotal()">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Đơn giá</label>
                                <input type="number" name="don_gia" id="don_gia" class="form-control" value="<?= (int)$dichvu['don_gia'] ?>" required oninput="calculateTotal()">
                            </div>
                        </div>

                        <div class="mb-3 p-3 bg-light rounded border">
                            <label class="small text-muted fw-bold">TỔNG TIỀN MỚI</label>
                            <div class="fs-4 fw-bold text-danger" id="thanh_tien_display">
                                <?= number_format($dichvu['so_luong'] * $dichvu['don_gia']) ?> ₫
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Ghi chú</label>
                            <textarea name="ghi_chu" class="form-control" rows="2"><?= htmlspecialchars($dichvu['ghi_chu']) ?></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning fw-bold text-white">Cập nhật</button>
                            <a href="index.php?act=booking-detail&id=<?= $dichvu['booking_id'] ?>" class="btn btn-secondary">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    function calculateTotal() {
        let sl = document.getElementById('so_luong').value || 0;
        let gia = document.getElementById('don_gia').value || 0;
        let total = sl * gia;
        document.getElementById('thanh_tien_display').innerText = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(total);
    }
</script>

<?php require_once PATH_ROOT . '/views/footer.php'; ?>