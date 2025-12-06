<?php require_once PATH_ROOT . '/views/header.php'; ?>
<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-center mt-5">
            <div class="card border-0 shadow-sm" style="width: 600px;">
                <div class="card-header bg-danger text-white fw-bold py-3">Thêm Khoản Chi Mới</div>
                <div class="card-body">
                    <form action="index.php?act=chiphi-store" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="lich_id" value="<?= $lich_id ?>">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tên khoản chi <span class="text-danger">*</span></label>
                            <input type="text" name="ten_chi_phi" class="form-control" required placeholder="VD: Phí cầu đường cao tốc...">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Loại chi phí</label>
                                <select name="loai_chi_phi" class="form-select">
                                    <option value="CAU_DUONG">Cầu đường / Bến bãi</option>
                                    <option value="CHECKIN">Phí Check-in / Vé</option>
                                    <option value="PHAT_SINH_XE">Xe phát sinh</option>
                                    <option value="AN_UONG">Ăn uống</option>
                                    <option value="KHAC">Khác</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Số tiền (VNĐ) <span class="text-danger">*</span></label>
                                <input type="number" name="so_tien" class="form-control fw-bold text-danger" required min="1">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ảnh hóa đơn (nếu có)</label>
                            <input type="file" name="hinh_anh" class="form-control" accept="image/*">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="ghi_chu" class="form-control" rows="2"></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-danger fw-bold">Lưu lại</button>
                            <a href="index.php?act=chiphi-list&lich_id=<?= $lich_id ?>" class="btn btn-secondary">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require_once PATH_ROOT . '/views/footer.php'; ?>