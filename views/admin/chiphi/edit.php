<?php require_once PATH_ROOT . '/views/header.php'; ?>
<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-center mt-5">
            <div class="card border-0 shadow-sm" style="width: 600px;">
                <div class="card-header bg-warning text-white fw-bold py-3">Cập nhật Khoản Chi</div>
                <div class="card-body">
                    <form action="index.php?act=chiphi-update" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= $chiphi['id'] ?>">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tên khoản chi</label>
                            <input type="text" name="ten_chi_phi" class="form-control" value="<?= htmlspecialchars($chiphi['ten_chi_phi']) ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Loại chi phí</label>
                                <select name="loai_chi_phi" class="form-select">
                                    <?php 
                                    $opts = ['CAU_DUONG'=>'Cầu đường', 'CHECKIN'=>'Check-in', 'PHAT_SINH_XE'=>'Xe phát sinh', 'AN_UONG'=>'Ăn uống', 'KHAC'=>'Khác'];
                                    foreach($opts as $k=>$v) {
                                        $sel = $chiphi['loai_chi_phi'] == $k ? 'selected' : '';
                                        echo "<option value='$k' $sel>$v</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Số tiền (VNĐ)</label>
                                <input type="number" name="so_tien" class="form-control fw-bold text-danger" value="<?= (int)$chiphi['so_tien'] ?>" required min="1">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ảnh hóa đơn mới (Để trống nếu không đổi)</label>
                            <input type="file" name="hinh_anh" class="form-control" accept="image/*">
                            <?php if($chiphi['hinh_anh']): ?>
                                <div class="mt-2"><small>Ảnh hiện tại: <a href="<?= BASE_URL . $chiphi['hinh_anh'] ?>" target="_blank">Xem</a></small></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="ghi_chu" class="form-control" rows="2"><?= htmlspecialchars($chiphi['ghi_chu']) ?></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning fw-bold text-white">Cập nhật</button>
                            <a href="index.php?act=chiphi-list&lich_id=<?= $chiphi['lich_id'] ?>" class="btn btn-secondary">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require_once PATH_ROOT . '/views/footer.php'; ?>