<?php require_once PATH_ROOT . '/views/header.php'; ?>

<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-center mt-5">
            <div class="card border-0 shadow-sm" style="width: 700px;">
                <div class="card-header bg-primary text-white fw-bold py-3">
                    <i class="fas fa-plus-circle me-2"></i> Thêm Nhà Cung Cấp
                </div>
                <div class="card-body">
                    <form action="index.php?act=ncc-store" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tên đơn vị <span class="text-danger">*</span></label>
                            <input type="text" name="ten_don_vi" class="form-control" required placeholder="VD: Khách sạn Mường Thanh, Nhà xe Thành Bưởi...">
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Loại dịch vụ</label>
                                <select name="loai_dich_vu" class="form-select">
                                    <option value="KHACH_SAN">Khách sạn / Lưu trú</option>
                                    <option value="VAN_CHUYEN">Vận chuyển (Xe, Vé máy bay)</option>
                                    <option value="NHA_HANG">Nhà hàng / Ăn uống</option>
                                    <option value="VE_THAM_QUAN">Vé tham quan</option>
                                    <option value="KHAC">Khác</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Người liên hệ</label>
                                <input type="text" name="nguoi_lien_he" class="form-control" placeholder="Tên người phụ trách">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="text" name="so_dien_thoai" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Email</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Địa chỉ</label>
                            <input type="text" name="dia_chi" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Ghi chú</label>
                            <textarea name="ghi_chu" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary fw-bold">Lưu thông tin</button>
                            <a href="index.php?act=nha-cung-cap" class="btn btn-secondary">Hủy bỏ</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require_once PATH_ROOT . '/views/footer.php'; ?>