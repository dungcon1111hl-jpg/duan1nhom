<?php require_once PATH_ROOT . '/views/header.php'; ?>

<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-center mt-5">
            <div class="card border-0 shadow-sm" style="width: 700px;">
                <div class="card-header bg-warning text-white fw-bold py-3">
                    <i class="fas fa-edit me-2"></i> Cập Nhật Nhà Cung Cấp
                </div>
                <div class="card-body">
                    <form action="index.php?act=ncc-update" method="POST">
                        <input type="hidden" name="id" value="<?= $ncc['id'] ?>">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tên đơn vị <span class="text-danger">*</span></label>
                            <input type="text" name="ten_don_vi" class="form-control" value="<?= htmlspecialchars($ncc['ten_don_vi']) ?>" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Loại dịch vụ</label>
                                <select name="loai_dich_vu" class="form-select">
                                    <?php 
                                        $types = ['KHACH_SAN'=>'Khách sạn', 'VAN_CHUYEN'=>'Vận chuyển', 'NHA_HANG'=>'Nhà hàng', 'VE_THAM_QUAN'=>'Vé tham quan', 'KHAC'=>'Khác'];
                                        foreach($types as $k => $v) {
                                            $sel = ($ncc['loai_dich_vu'] == $k) ? 'selected' : '';
                                            echo "<option value='$k' $sel>$v</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Người liên hệ</label>
                                <input type="text" name="nguoi_lien_he" class="form-control" value="<?= htmlspecialchars($ncc['nguoi_lien_he']) ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="text" name="so_dien_thoai" class="form-control" value="<?= htmlspecialchars($ncc['so_dien_thoai']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Email</label>
                                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($ncc['email']) ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Địa chỉ</label>
                            <input type="text" name="dia_chi" class="form-control" value="<?= htmlspecialchars($ncc['dia_chi']) ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Ghi chú</label>
                            <textarea name="ghi_chu" class="form-control" rows="3"><?= htmlspecialchars($ncc['ghi_chu']) ?></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning fw-bold text-white">Cập nhật</button>
                            <a href="index.php?act=nha-cung-cap" class="btn btn-secondary">Hủy bỏ</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require_once PATH_ROOT . '/views/footer.php'; ?>