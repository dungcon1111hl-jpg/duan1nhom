<?php require_once ROOT . '/views/header.php'; ?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                <h1 class="text-warning">Sửa thông tin khách</h1>
                <a href="<?= BASE_URL ?>?act=booking-guest-list&id=<?= $guest['booking_id'] ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại danh sách
                </a>
            </div>

            <div class="card mb-4 border-0 shadow-lg">
                <div class="card-body p-4">
                    <form action="<?= BASE_URL ?>?act=booking-guest-update" method="POST">
                        <input type="hidden" name="id" value="<?= $guest['id'] ?>">
                        <input type="hidden" name="booking_id" value="<?= $guest['booking_id'] ?>">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Họ tên <span class="text-danger">*</span></label>
                            <input type="text" name="ho_ten" class="form-control" value="<?= htmlspecialchars($guest['ho_ten']) ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Giới tính</label>
                                <select name="gioi_tinh" class="form-select">
                                    <option value="1" <?= $guest['gioi_tinh'] == 1 ? 'selected' : '' ?>>Nam</option>
                                    <option value="0" <?= $guest['gioi_tinh'] == 0 ? 'selected' : '' ?>>Nữ</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Ngày sinh</label>
                                <input type="date" name="ngay_sinh" class="form-control" value="<?= $guest['ngay_sinh'] ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Giấy tờ (CCCD/Passport)</label>
                            <input type="text" name="so_giay_to" class="form-control" value="<?= htmlspecialchars($guest['so_giay_to']) ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Yêu cầu đặc biệt</label>
                            <textarea name="yeu_cau_dac_biet" class="form-control" rows="2"><?= htmlspecialchars($guest['yeu_cau_dac_biet']) ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Ghi chú</label>
                            <textarea name="ghi_chu" class="form-control" rows="2"><?= htmlspecialchars($guest['ghi_chu']) ?></textarea>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-warning px-4 fw-bold text-white">
                                <i class="fas fa-save me-1"></i> Cập nhật
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require_once ROOT . '/views/footer.php'; ?>