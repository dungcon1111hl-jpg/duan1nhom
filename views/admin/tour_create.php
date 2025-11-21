<?php require_once PATH_ROOT . '/views/header.php'; ?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Thêm Tour Mới</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.php?act=admin">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="index.php?controller=tour&action=index">Danh sách Tour</a></li>
        <li class="breadcrumb-item active">Thêm mới</li>
    </ol>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-success fw-bold">
            <i class="fas fa-plus-circle me-2"></i> Nhập thông tin Tour
        </h2>
        <a href="index.php?controller=tour&action=index" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <form action="index.php?controller=tour&action=store" method="POST" enctype="multipart/form-data" id="tourForm">
                <div class="row g-3">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted">
                                <i class="fas fa-barcode me-1"></i> Mã tour <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="ma_tour" class="form-control" placeholder="VD: TOUR-2025-001" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted">
                                <i class="fas fa-heading me-1"></i> Tên tour <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="ten_tour" class="form-control" placeholder="Nhập tên tour..." required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted">
                                <i class="fas fa-align-left me-1"></i> Mô tả ngắn
                            </label>
                            <textarea name="mo_ta_ngan" class="form-control" rows="2" placeholder="Tóm tắt ngắn gọn về tour..."></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-muted">
                                    <i class="fas fa-map-marker-alt text-primary me-1"></i> Địa điểm bắt đầu
                                </label>
                                <input type="text" name="dia_diem_bat_dau" class="form-control" placeholder="Hà Nội">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-muted">
                                    <i class="fas fa-flag-checkered text-success me-1"></i> Địa điểm kết thúc
                                </label>
                                <input type="text" name="dia_diem_ket_thuc" class="form-control" placeholder="Đà Nẵng">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-muted">
                                    <i class="far fa-calendar-alt me-1"></i> Ngày khởi hành
                                </label>
                                <input type="date" name="ngay_khoi_hanh" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-muted">
                                    <i class="far fa-calendar-check me-1"></i> Ngày kết thúc
                                </label>
                                <input type="date" name="ngay_ket_thuc" class="form-control">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted">
                                <i class="fas fa-coins text-warning me-1"></i> Giá tour (VNĐ)
                            </label>
                            <input type="number" name="gia_tour" class="form-control" min="0" placeholder="0">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted">
                                <i class="fas fa-file-alt me-1"></i> Mô tả chi tiết
                            </label>
                            <textarea name="mo_ta_chi_tiet" class="form-control" rows="4" placeholder="Lịch trình, dịch vụ, lưu ý..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted">
                                <i class="fas fa-image text-info me-1"></i> Ảnh minh họa
                            </label>
                            <input type="file" name="anh_minh_hoa" id="anh_minh_hoa" class="form-control" accept="image/*">
                            <div class="mt-2" id="imagePreview" style="display: none;">
                                <img src="" alt="Preview" class="img-thumbnail" style="max-height: 180px; border-radius: 8px;">
                            </div>
                            <small class="text-muted">Dung lượng tối đa: 5MB. Định dạng: JPG, PNG, GIF</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-muted">
                                    <i class="fas fa-ticket-alt me-1"></i> Số lượng vé
                                </label>
                                <input type="number" name="so_luong_ve" class="form-control" min="0" value="50">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-muted">
                                    <i class="fas fa-clipboard-check me-1"></i> Vé còn lại
                                </label>
                                <input type="number" name="so_ve_con_lai" class="form-control" min="0" value="50">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted">
                                <i class="fas fa-info-circle me-1"></i> Trạng thái
                            </label>
                            <select name="trang_thai" class="form-select">
                                <option value="CON_VE" selected>Còn vé</option>
                                <option value="HET_VE">Hết vé</option>
                                <option value="HUY">Hủy</option>
                                <option value="DA_KHOI_HANH">Đã khởi hành</option>
                                <option value="DA_KET_THUC">Đã kết thúc</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted">
                                <i class="fas fa-shield-alt text-danger me-1"></i> Chính sách hủy/đổi/hoàn
                            </label>
                            <textarea name="chinh_sach" class="form-control" rows="3" placeholder="Quy định hoàn tiền, đổi vé..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-success shadow-sm px-4">
                        <i class="fas fa-save me-1"></i> Lưu Tour
                    </button>
                    <a href="index.php?controller=tour&action=index" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i> Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('anh_minh_hoa').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('imagePreview');
        const img = preview.querySelector('img');

        if (file) {
            // Kiểm tra kích thước (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('Ảnh quá lớn! Vui lòng chọn ảnh dưới 5MB.');
                e.target.value = '';
                preview.style.display = 'none';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(event) {
                img.src = event.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    });
</script>

<style>
    .card {
        border-radius: 14px;
        overflow: hidden;
    }
    .form-control, .form-select {
        border-radius: 8px;
    }
    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
    }
    .btn {
        border-radius: 8px;
    }
    label {
        font-size: 0.95rem;
    }
</style>

<?php require_once PATH_ROOT . '/views/footer.php'; ?>