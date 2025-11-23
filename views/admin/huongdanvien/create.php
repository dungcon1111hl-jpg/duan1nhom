<?php require_once PATH_ROOT . '/views/header.php'; ?>
<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <h2 class="fw-bold text-success">Thêm Hướng dẫn viên</h2>
            <a href="index.php?act=hdv-list" class="btn btn-secondary shadow-sm">Quay lại</a>
        </div>

        <form action="index.php?act=hdv-store" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header fw-bold bg-white">Thông tin cá nhân</div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Họ và tên <span class="text-danger">*</span></label>
                                    <input type="text" name="ho_ten" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Ngày sinh</label>
                                    <input type="date" name="ngay_sinh" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Giới tính</label>
                                    <select name="gioi_tinh" class="form-select">
                                        <option value="Nam">Nam</option>
                                        <option value="Nu">Nữ</option>
                                        <option value="Khac">Khác</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Số điện thoại <span class="text-danger">*</span></label>
                                    <input type="text" name="so_dien_thoai" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Email</label>
                                    <input type="email" name="email" class="form-control">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold">Địa chỉ</label>
                                    <input type="text" name="dia_chi" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header fw-bold bg-white">Kỹ năng & Hồ sơ</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Ảnh đại diện</label>
                                <input type="file" name="anh_dai_dien" class="form-control" accept="image/*">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Ngôn ngữ thành thạo</label>
                                <input type="text" name="ngon_ngu" class="form-control" placeholder="VD: Anh, Trung, Pháp">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Số năm kinh nghiệm</label>
                                <input type="number" name="kinh_nghiem" class="form-control" placeholder="VD: 3">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Chứng chỉ hành nghề</label>
                                <input type="text" name="chung_chi" class="form-control" placeholder="VD: Thẻ HDV Quốc tế...">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Trạng thái</label>
                                <select name="trang_thai" class="form-select">
                                    <option value="DANG_LAM_VIEC">Đang làm việc</option>
                                    <option value="NGHI_VIEC">Nghỉ việc</option>
                                </select>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success fw-bold">Lưu thông tin</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>
<?php require_once PATH_ROOT . '/views/footer.php'; ?>