<?php require_once PATH_ROOT . '/views/header.php'; ?>
<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <h2 class="fw-bold text-warning">Sửa Hướng dẫn viên</h2>
            <a href="index.php?act=hdv-list" class="btn btn-secondary shadow-sm">Quay lại</a>
        </div>

        <form action="index.php?act=hdv-update" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $hdv['id'] ?>">
            <input type="hidden" name="anh_cu" value="<?= $hdv['anh_dai_dien'] ?>">
            
            <div class="row">
                <div class="col-lg-8">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header fw-bold bg-white">Thông tin cá nhân</div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Họ và tên</label>
                                    <input type="text" name="ho_ten" class="form-control" value="<?= htmlspecialchars($hdv['ho_ten']) ?>" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Ngày sinh</label>
                                    <input type="date" name="ngay_sinh" class="form-control" value="<?= $hdv['ngay_sinh'] ?>">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Giới tính</label>
                                    <select name="gioi_tinh" class="form-select">
                                        <option value="Nam" <?= $hdv['gioi_tinh'] == 'Nam' ? 'selected' : '' ?>>Nam</option>
                                        <option value="Nu" <?= $hdv['gioi_tinh'] == 'Nu' ? 'selected' : '' ?>>Nữ</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">SĐT</label>
                                    <input type="text" name="so_dien_thoai" class="form-control" value="<?= htmlspecialchars($hdv['so_dien_thoai']) ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Email</label>
                                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($hdv['email']) ?>">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold">Địa chỉ</label>
                                    <input type="text" name="dia_chi" class="form-control" value="<?= htmlspecialchars($hdv['dia_chi']) ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header fw-bold bg-white">Kỹ năng & Hồ sơ</div>
                        <div class="card-body">
                            <div class="mb-3 text-center">
                                <?php if($hdv['anh_dai_dien']): ?>
                                    <img src="<?= BASE_URL . $hdv['anh_dai_dien'] ?>" class="img-thumbnail mb-2" width="100">
                                <?php endif; ?>
                                <input type="file" name="anh_dai_dien" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Ngôn ngữ</label>
                                <input type="text" name="ngon_ngu" class="form-control" value="<?= htmlspecialchars($hdv['ngon_ngu']) ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Kinh nghiệm (năm)</label>
                                <input type="number" name="kinh_nghiem" class="form-control" value="<?= $hdv['kinh_nghiem'] ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Chứng chỉ</label>
                                <input type="text" name="chung_chi" class="form-control" value="<?= htmlspecialchars($hdv['chung_chi']) ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Trạng thái</label>
                                <select name="trang_thai" class="form-select">
                                    <option value="DANG_LAM_VIEC" <?= $hdv['trang_thai'] == 'DANG_LAM_VIEC' ? 'selected' : '' ?>>Đang làm việc</option>
                                    <option value="NGHI_VIEC" <?= $hdv['trang_thai'] == 'NGHI_VIEC' ? 'selected' : '' ?>>Nghỉ việc</option>
                                </select>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-warning fw-bold text-white">Cập nhật</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>
<?php require_once PATH_ROOT . '/views/footer.php'; ?>