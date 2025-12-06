<?php require_once PATH_ROOT . '/views/header.php'; ?>

<?php $lich = $lich ?? []; ?>

<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <h2 class="fw-bold text-warning"><i class="fas fa-edit me-2"></i>Sửa Lịch Khởi Hành</h2>
            <a href="index.php?act=lich-khoi-hanh" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left me-1"></i> Quay lại
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form method="POST" action="index.php?act=lich-khoi-hanh-update">
                    <input type="hidden" name="id" value="<?= $lich['id'] ?? 0 ?>">

                    <div class="row g-3">
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Tour du lịch</label>
                            <select name="tour_id" class="form-select" required>
                                <?php if (!empty($tours)): foreach ($tours as $t): ?>
                                    <option value="<?= $t['id'] ?>" <?= (isset($lich['tour_id']) && $t['id'] == $lich['tour_id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($t['ten_tour'] ?? '') ?>
                                    </option>
                                <?php endforeach; endif; ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Ngày khởi hành</label>
                            <input type="datetime-local" name="ngay_khoi_hanh" class="form-control"
                                   value="<?= !empty($lich['ngay_khoi_hanh']) ? date('Y-m-d\TH:i', strtotime($lich['ngay_khoi_hanh'])) : '' ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Ngày kết thúc</label>
                            <input type="datetime-local" name="ngay_ket_thuc" class="form-control"
                                   value="<?= !empty($lich['ngay_ket_thuc']) ? date('Y-m-d\TH:i', strtotime($lich['ngay_ket_thuc'])) : '' ?>" required>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Điểm tập trung</label>
                            <input type="text" name="diem_tap_trung" class="form-control"
                                   value="<?= htmlspecialchars($lich['diem_tap_trung'] ?? '') ?>">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Số chỗ tối đa</label>
                            <input type="number" name="so_cho_toi_da" class="form-control"
                                   value="<?= $lich['so_cho_toi_da'] ?? 20 ?>">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Số chỗ đã đặt</label>
                            <input type="number" class="form-control bg-light"
                                   value="<?= $lich['so_cho_da_dat'] ?? 0 ?>" readonly>
                            <div class="form-text">Tự động tính từ Booking.</div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Trạng thái</label>
                            <select name="trang_thai" class="form-select">
                                <?php
                                $statuses = ['DU_KIEN', 'NHAN_KHACH', 'CHOT_SO', 'DANG_CHAY', 'HOAN_THANH', 'HUY'];
                                foreach ($statuses as $st) {
                                    $selected = (isset($lich['trang_thai']) && $lich['trang_thai'] == $st) ? 'selected' : '';
                                    echo "<option value='$st' $selected>$st</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Giá người lớn (VNĐ)</label>
                            <input type="number" name="gia_nguoi_lon" class="form-control fw-bold text-success"
                                   value="<?= (int)($lich['gia_nguoi_lon'] ?? 0) ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Giá trẻ em (VNĐ)</label>
                            <input type="number" name="gia_tre_em" class="form-control fw-bold text-primary"
                                   value="<?= (int)($lich['gia_tre_em'] ?? 0) ?>">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Ghi chú</label>
                            <textarea name="ghi_chu" class="form-control" rows="3"><?= htmlspecialchars($lich['ghi_chu'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a class="btn btn-secondary me-md-2" href="index.php?act=lich-khoi-hanh">Hủy bỏ</a>
                        <button class="btn btn-warning fw-bold text-white px-4">Cập nhật Lịch</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php require_once PATH_ROOT . '/views/footer.php'; ?>