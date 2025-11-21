<?php require_once PATH_ROOT . '/views/header.php'; ?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Sửa Tour</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.php?act=admin">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="index.php?controller=tour&action=index">Danh sách Tour</a></li>
        <li class="breadcrumb-item active">Chỉnh sửa</li>
    </ol>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-warning fw-bold">
            <i class="fas fa-edit me-2"></i> Thông tin Tour
        </h2>
        <a href="<?= BASE_URL ?>?act=tours" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <form action="index.php?controller=tour&action=update" method="POST" enctype="multipart/form-data" id="editTourForm">
                <input type="hidden" name="id" value="<?= $tour['id'] ?>">

                <div class="row g-3">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted">
                                <i class="fas fa-barcode me-1"></i> Mã tour <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="ma_tour" class="form-control"
                                   value="<?= htmlspecialchars($tour['ma_tour']) ?>" 
                                   placeholder="VD: TOUR-2025-001" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted">
                                <i class="fas fa-heading me-1"></i> Tên tour <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="ten_tour" class="form-control"
                                   value="<?= htmlspecialchars($tour['ten_tour']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted">
                                <i class="fas fa-align-left me-1"></i> Mô tả ngắn
                            </label>
                            <textarea name="mo_ta_ngan" class="form-control" rows="2"
                                      placeholder="Tóm tắt ngắn gọn..."><?= htmlspecialchars($tour['mo_ta_ngan'] ?? '') ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-muted">
                                    <i class="fas fa-map-marker-alt text-primary me-1"></i> Địa điểm bắt đầu
                                </label>
                                <input type="text" name="dia_diem_bat_dau" class="form-control"
                                       value="<?= htmlspecialchars($tour['dia_diem_bat_dau']) ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-muted">
                                    <i class="fas fa-flag-checkered text-success me-1"></i> Địa điểm kết thúc
                                </label>
                                <input type="text" name="dia_diem_ket_thuc" class="form-control"
                                       value="<?= htmlspecialchars($tour['dia_diem_ket_thuc']) ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-muted">
                                    <i class="far fa-calendar-alt me-1"></i> Ngày khởi hành
                                </label>
                                <input type="date" name="ngay_khoi_hanh" class="form-control"
                                       value="<?= htmlspecialchars($tour['ngay_khoi_hanh']) ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-muted">
                                    <i class="far fa-calendar-check me-1"></i> Ngày kết thúc
                                </label>
                                <input type="date" name="ngay_ket_thuc" class="form-control"
                                       value="<?= htmlspecialchars($tour['ngay_ket_thuc']) ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted">
                                <i class="fas fa-coins text-warning me-1"></i> Giá tour (VNĐ)
                            </label>
                            <input type="number" name="gia_tour" class="form-control" min="0"
                                   value="<?= htmlspecialchars($tour['gia_tour']) ?>">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted">
                                <i class="fas fa-file-alt me-1"></i> Mô tả chi tiết
                            </label>
                            <textarea name="mo_ta_chi_tiet" class="form-control" rows="4"
                                      placeholder="Lịch trình, dịch vụ..."><?= htmlspecialchars($tour['mo_ta_chi_tiet']) ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted">
                                <i class="fas fa-image text-info me-1"></i> Ảnh minh họa
                            </label>

                            <?php if (!empty($tour['anh_minh_hoa'])): ?>
                                <div class="mb-2 p-2 border rounded bg-light">
                                    <small class="text-success">
                                        <i class="fas fa-check-circle"></i> Ảnh hiện tại:
                                    </small><br>
                                    <img src="uploads/tours/<?= htmlspecialchars($tour['anh_minh_hoa']) ?>" 
                                         alt="Ảnh tour hiện tại" 
                                         class="img-thumbnail mt-1" 
                                         style="max-height: 120px; border-radius: 8px;">
                                    <div class="form-text">
                                        <small>Tên file: <code><?= htmlspecialchars($tour['anh_minh_hoa']) ?></code></small>
                                        <input type="hidden" name="anh_cu" value="<?= $tour['anh_minh_hoa'] ?>">
                                    </div>
                                </div>
                            <?php endif; ?>

                            <input type="file" name="anh_minh_hoa" id="anh_minh_hoa" class="form-control" accept="image/*">
                            
                            <div class="mt-2" id="imagePreview" style="display: none;">
                                <small class="text-primary">
                                    <i class="fas fa-eye"></i> Xem trước ảnh mới:
                                </small><br>
                                <img src="" alt="Preview" class="img-thumbnail" style="max-height: 180px; border-radius: 8px;">
                            </div>

                            <small class="text-muted d-block mt-1">
                                Để trống nếu không muốn thay đổi. Dung lượng tối đa: 5MB. JPG, PNG, GIF.
                            </small>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-muted">
                                    <i class="fas fa-ticket-alt me-1"></i> Số lượng vé
                                </label>
                                <input type="number" name="so_luong_ve" class="form-control" min="0"
                                       value="<?= (int)$tour['so_luong_ve'] ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-muted">
                                    <i class="fas fa-clipboard-check me-1"></i> Vé còn lại
                                </label>
                                <input type="number" name="so_ve_con_lai" class="form-control" min="0"
                                       value="<?= (int)$tour['so_ve_con_lai'] ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted">
                                <i class="fas fa-info-circle me-1"></i> Trạng thái
                            </label>
                            <select name="trang_thai" class="form-select">
                                <?php
                                $statuses = [
                                    'CON_VE' => 'Còn vé',
                                    'HET_VE' => 'Hết vé',
                                    'HUY' => 'Hủy',
                                    'DA_KHOI_HANH' => 'Đã khởi hành',
                                    'DA_KET_THUC' => 'Đã kết thúc'
                                ];
                                foreach ($statuses as $value => $label):
                                ?>
                                    <option value="<?= $value ?>" 
                                            <?= $tour['trang_thai'] === $value ? 'selected' : '' ?>>
                                        <?= $label ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted">
                                <i class="fas fa-shield-alt text-danger me-1"></i> Chính sách hủy/đổi/hoàn
                            </label>
                            <textarea name="chinh_sach" class="form-control" rows="3"
                                      placeholder="Quy định hoàn tiền..."><?= htmlspecialchars($tour['chinh_sach']) ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-warning shadow-sm px-4">
                        <i class="fas fa-save me-1"></i> Cập nhật
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
            // Kiểm tra dung lượng
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
        border-color: #ffc107;
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.15);
    }
    .btn {
        border-radius: 8px;
    }
    label {
        font-size: 0.95rem;
    }
    .img-thumbnail {
        transition: transform 0.2s;
    }
    .img-thumbnail:hover {
        transform: scale(1.05);
    }
</style>

<?php require_once PATH_ROOT . '/views/footer.php'; ?>