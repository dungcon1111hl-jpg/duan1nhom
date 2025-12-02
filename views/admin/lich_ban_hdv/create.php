<?php require_once PATH_ROOT . '/views/header.php'; ?>

<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-center mt-5">
            <div class="card border-0 shadow-sm" style="width: 600px;">
                <div class="card-header bg-danger text-white fw-bold py-3">
                    <i class="fas fa-calendar-minus me-2"></i> Đăng Ký Lịch Bận
                </div>
                <div class="card-body">
                    <form action="index.php?act=lich-ban-store" method="POST">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Hướng dẫn viên <span class="text-danger">*</span></label>
                            <select name="hdv_id" class="form-select" required>
                                <option value="">-- Chọn HDV --</option>
                                <?php foreach ($hdvs as $h): ?>
                                    <option value="<?= $h['id'] ?>"><?= htmlspecialchars($h['ho_ten']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Bắt đầu từ</label>
                                <input type="datetime-local" name="ngay_bat_dau" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Đến hết ngày</label>
                                <input type="datetime-local" name="ngay_ket_thuc" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Lý do bận</label>
                            <input type="text" name="ly_do" class="form-control" required placeholder="VD: Đi tour khác, Bận việc gia đình...">
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Phân loại</label>
                                <select name="loai_lich" class="form-select">
                                    <option value="TAM_THOI">Tạm thời (Đột xuất)</option>
                                    <option value="CO_DINH">Cố định (Định kỳ)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Trạng thái</label>
                                <select name="trang_thai" class="form-select">
                                    <option value="CHO_XAC_NHAN">Chờ xác nhận</option>
                                    <option value="DA_XAC_NHAN">Đã xác nhận</option>
                                    <option value="TU_CHOI">Từ chối</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Ghi chú thêm</label>
                            <textarea name="ghi_chu" class="form-control" rows="2"></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-danger fw-bold">Lưu Lịch Bận</button>
                            <a href="index.php?act=lich-ban-hdv" class="btn btn-secondary">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require_once PATH_ROOT . '/views/footer.php'; ?>