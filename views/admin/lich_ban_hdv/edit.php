<?php require_once PATH_ROOT . '/views/header.php'; ?>

<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-center mt-5">
            <div class="card border-0 shadow-sm" style="width: 600px;">
                <div class="card-header bg-warning text-white fw-bold py-3">
                    <i class="fas fa-edit me-2"></i> Cập Nhật Lịch Bận
                </div>
                <div class="card-body">
                    <form action="index.php?act=lich-ban-update" method="POST">
                        <input type="hidden" name="id" value="<?= $lich_ban['id'] ?>">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Hướng dẫn viên</label>
                            <select name="hdv_id" class="form-select" required>
                                <?php foreach ($hdvs as $h): ?>
                                    <option value="<?= $h['id'] ?>" <?= $lich_ban['hdv_id'] == $h['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($h['ho_ten']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Bắt đầu từ</label>
                                <input type="datetime-local" name="ngay_bat_dau" class="form-control" value="<?= $lich_ban['ngay_bat_dau'] ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Đến hết ngày</label>
                                <input type="datetime-local" name="ngay_ket_thuc" class="form-control" value="<?= $lich_ban['ngay_ket_thuc'] ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Lý do bận</label>
                            <input type="text" name="ly_do" class="form-control" value="<?= htmlspecialchars($lich_ban['ly_do']) ?>" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Phân loại</label>
                                <select name="loai_lich" class="form-select">
                                    <option value="TAM_THOI" <?= $lich_ban['loai_lich'] == 'TAM_THOI' ? 'selected' : '' ?>>Tạm thời</option>
                                    <option value="CO_DINH" <?= $lich_ban['loai_lich'] == 'CO_DINH' ? 'selected' : '' ?>>Cố định</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Trạng thái</label>
                                <select name="trang_thai" class="form-select">
                                    <option value="CHO_XAC_NHAN" <?= $lich_ban['trang_thai'] == 'CHO_XAC_NHAN' ? 'selected' : '' ?>>Chờ xác nhận</option>
                                    <option value="DA_XAC_NHAN" <?= $lich_ban['trang_thai'] == 'DA_XAC_NHAN' ? 'selected' : '' ?>>Đã xác nhận</option>
                                    <option value="TU_CHOI" <?= $lich_ban['trang_thai'] == 'TU_CHOI' ? 'selected' : '' ?>>Từ chối</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Ghi chú thêm</label>
                            <textarea name="ghi_chu" class="form-control" rows="2"><?= htmlspecialchars($lich_ban['ghi_chu']) ?></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning fw-bold text-white">Cập nhật</button>
                            <a href="index.php?act=lich-ban-hdv" class="btn btn-secondary">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require_once PATH_ROOT . '/views/footer.php'; ?>