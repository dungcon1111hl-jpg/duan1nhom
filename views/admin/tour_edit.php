<?php require_once PATH_ROOT . '/views/header.php'; ?>

<?php
    // Xử lý dữ liệu mặc định
    $tour = $tour ?? [];
    $list_anh = $list_anh ?? [];
    $list_lich_trinh = $list_lich_trinh ?? [];
    $dsNcc = $dsNcc ?? [];
    $selectedNcc = $selectedNcc ?? [];
    $dsHDV = $dsHDV ?? [];
    $dsDanhMuc = $dsDanhMuc ?? []; // Danh mục tour
?>

<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <div>
                <h2 class="fw-bold text-warning"><i class="fas fa-edit me-2"></i>Quản lý chi tiết Tour</h2>
                <div class="text-muted">Đang sửa: <strong><?= htmlspecialchars($tour['ten_tour'] ?? 'Không tên') ?></strong></div>
            </div>
            <a href="index.php?act=tours" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
            </a>
        </div>

        <ul class="nav nav-tabs mb-4" id="tourTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-bold" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab">
                    <i class="fas fa-info-circle me-2"></i>Thông tin chung
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold" id="images-tab" data-bs-toggle="tab" data-bs-target="#images" type="button" role="tab">
                    <i class="fas fa-images me-2"></i>Album Ảnh <span class="badge bg-secondary ms-1"><?= count($list_anh) ?></span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold" id="schedule-tab" data-bs-toggle="tab" data-bs-target="#schedule" type="button" role="tab">
                    <i class="fas fa-calendar-alt me-2"></i>Lịch trình <span class="badge bg-secondary ms-1"><?= count($list_lich_trinh) ?></span>
                </button>
            </li>
        </ul>

        <div class="tab-content" id="tourTabContent">
            
            <div class="tab-pane fade show active" id="info" role="tabpanel">
                <form action="index.php?act=tour-update" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $tour['id'] ?>">
                    <input type="hidden" name="anh_cu" value="<?= $tour['anh_minh_hoa'] ?>">
                    
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card mb-4 border-0 shadow-sm">
                                <div class="card-body">
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Tên Tour <span class="text-danger">*</span></label>
                                        <input type="text" name="ten_tour" class="form-control" value="<?= htmlspecialchars($tour['ten_tour'] ?? '') ?>" required>
                                    </div>
                                    
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-8">
                                            <label class="form-label fw-bold">Mã Tour</label>
                                            <input type="text" name="ma_tour" class="form-control" value="<?= htmlspecialchars($tour['ma_tour'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Danh mục Tour</label>
                                            <select name="loai_tour" class="form-select">
                                                <option value="">-- Chọn danh mục --</option>
                                                <?php foreach ($dsDanhMuc as $dm): ?>
                                                    <option value="<?= $dm['id'] ?>" 
                                                        <?= (isset($tour['loai_tour']) && $tour['loai_tour'] == $dm['id']) ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($dm['ten_danh_muc']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Phân loại nâng cao</label>
                                            <select name="loai_tour_nang_cao" class="form-select">
                                                <?php 
                                                    $advTypes = ['TRON_GOI' => 'Trọn gói', 'GHEP_DOAN' => 'Ghép đoàn', 'THEO_YEU_CAU' => 'Theo yêu cầu', 'VIP' => 'VIP/Cao cấp'];
                                                    foreach ($advTypes as $k => $v) {
                                                        $sel = ($tour['loai_tour_nang_cao'] ?? '') == $k ? 'selected' : '';
                                                        echo "<option value='$k' $sel>$v</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Đối tượng khách</label>
                                            <select name="doi_tuong_khach" class="form-select">
                                                <?php 
                                                    $clients = ['KHACH_LE' => 'Khách lẻ', 'KHACH_DOAN' => 'Khách đoàn', 'GIA_DINH' => 'Gia đình'];
                                                    foreach ($clients as $k => $v) {
                                                        $sel = ($tour['doi_tuong_khach'] ?? '') == $k ? 'selected' : '';
                                                        echo "<option value='$k' $sel>$v</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-3 bg-light p-3 rounded border">
                                        <label class="form-label fw-bold text-success"><i class="fas fa-tags me-1"></i> Bảng giá chi tiết (VNĐ)</label>
                                        <div class="row g-2">
                                            <div class="col-md-3">
                                                <label class="small text-muted fw-bold">Người lớn (>12t)</label>
                                                <input type="number" name="gia_nguoi_lon" class="form-control fw-bold text-primary" 
                                                       value="<?= $tour['gia_nguoi_lon'] ?? 0 ?>" oninput="syncPrice(this)">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="small text-muted fw-bold">Trẻ em (5-11t)</label>
                                                <input type="number" name="gia_tre_em" class="form-control" 
                                                       value="<?= $tour['gia_tre_em'] ?? 0 ?>">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="small text-muted fw-bold">Em bé (<5t)</label>
                                                <input type="number" name="gia_em_be" class="form-control" 
                                                       value="<?= $tour['gia_em_be'] ?? 0 ?>">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="small text-muted fw-bold">Phụ thu/Lễ tết</label>
                                                <input type="number" name="phu_thu" class="form-control" 
                                                       value="<?= $tour['phu_thu'] ?? 0 ?>">
                                            </div>
                                        </div>
                                        <div class="mt-2 d-flex align-items-center">
                                            <label class="small me-2 fw-bold text-secondary">* Giá hiển thị chính:</label>
                                            <input type="number" name="gia_tour" id="main_price" 
                                                   class="d-inline-block form-control form-control-sm w-25 fw-bold text-danger" 
                                                   value="<?= $tour['gia_tour'] ?? 0 ?>">
                                        </div>
                                    </div>

                                    <div class="row g-3 mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold text-primary">Điểm đi</label>
                                            <input type="text" name="dia_diem_bat_dau" class="form-control" value="<?= htmlspecialchars($tour['dia_diem_bat_dau'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold text-warning">Trung chuyển</label>
                                            <input type="text" name="diem_trung_chuyen" class="form-control" value="<?= htmlspecialchars($tour['diem_trung_chuyen'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold text-success">Điểm đến</label>
                                            <input type="text" name="dia_diem_ket_thuc" class="form-control" value="<?= htmlspecialchars($tour['dia_diem_ket_thuc'] ?? '') ?>">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-info"><i class="fas fa-handshake me-1"></i> Nhà cung cấp dịch vụ</label>
                                        <div class="border rounded p-3 bg-white" style="max-height: 150px; overflow-y: auto;">
                                            <?php if(!empty($dsNcc)): ?>
                                                <div class="row g-2">
                                                    <?php foreach ($dsNcc as $ncc): ?>
                                                        <?php $isChecked = in_array($ncc['id'], $selectedNcc) ? 'checked' : ''; ?>
                                                        <div class="col-md-6">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="ncc[]" value="<?= $ncc['id'] ?>" id="ncc_edit_<?= $ncc['id'] ?>" <?= $isChecked ?>>
                                                                <label class="form-check-label" for="ncc_edit_<?= $ncc['id'] ?>">
                                                                    <strong><?= htmlspecialchars($ncc['ten_don_vi']) ?></strong> 
                                                                    <small class="text-muted">(<?= htmlspecialchars($ncc['loai_dich_vu']) ?>)</small>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php else: ?><p class="text-muted small mb-0">Chưa có dữ liệu NCC.</p><?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Mô tả ngắn (SEO)</label>
                                        <textarea name="mo_ta_ngan" class="form-control" rows="2"><?= htmlspecialchars($tour['mo_ta_ngan'] ?? '') ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Chi tiết chương trình</label>
                                        <textarea name="mo_ta_chi_tiet" class="form-control" rows="4"><?= htmlspecialchars($tour['mo_ta_chi_tiet'] ?? '') ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card bg-light border-0 mb-3">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Ngày khởi hành</label>
                                        <input type="date" name="ngay_khoi_hanh" class="form-control" value="<?= $tour['ngay_khoi_hanh'] ?? '' ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Ngày kết thúc</label>
                                        <input type="date" name="ngay_ket_thuc" class="form-control" value="<?= $tour['ngay_ket_thuc'] ?? '' ?>">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold"><i class="fas fa-user-tie me-1"></i> Hướng dẫn viên</label>
                                        <select name="hdv_id" class="form-select">
                                            <option value="">-- Chọn HDV --</option>
                                            <?php if(!empty($dsHDV)): foreach($dsHDV as $hdv): ?>
                                                <option value="<?= $hdv['id'] ?>" <?= (isset($tour['hdv_id']) && $tour['hdv_id'] == $hdv['id']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($hdv['ho_ten']) ?>
                                                </option>
                                            <?php endforeach; endif; ?>
                                        </select>
                                    </div>

                                    <div class="mb-3 text-center">
                                        <label class="form-label fw-bold d-block text-start">Ảnh đại diện</label>
                                        <?php if (!empty($tour['anh_minh_hoa'])): ?>
                                            <img src="<?= BASE_URL ?>uploads/tours/<?= $tour['anh_minh_hoa'] ?>" class="img-fluid rounded mb-2 border" style="max-height: 160px">
                                        <?php endif; ?>
                                        <input type="file" name="anh_minh_hoa" class="form-control form-control-sm mt-2" onchange="previewImage(this)">
                                        <img id="imgPreview" class="img-fluid rounded mt-2 d-none" style="max-height: 160px">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Số vé (Min - Max)</label>
                                        <div class="input-group">
                                            <input type="number" name="so_khach_toithieu" class="form-control" value="<?= $tour['so_khach_toithieu'] ?? 10 ?>" placeholder="Min">
                                            <span class="input-group-text">-</span>
                                            <input type="number" name="so_luong_ve" class="form-control" value="<?= $tour['so_luong_ve'] ?? 30 ?>" placeholder="Max">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Số chỗ còn nhận</label>
                                        <input type="number" name="so_ve_con_lai" class="form-control" value="<?= $tour['so_ve_con_lai'] ?? 0 ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Trạng thái</label>
                                        <select name="trang_thai" class="form-select">
                                            <?php
                                            $stt = $tour['trang_thai'] ?? 'CON_VE';
                                            $opts = ['CON_VE'=>'Còn vé', 'HET_VE'=>'Hết vé', 'DA_KHOI_HANH'=>'Đã khởi hành', 'HUY'=>'Hủy', 'NGUNG_HOAT_DONG'=>'Ngừng hoạt động'];
                                            foreach($opts as $k=>$v) echo "<option value='$k' ".($stt == $k ? 'selected' : '').">$v</option>";
                                            ?>
                                        </select>
                                    </div>
                                    
                                    <div class="d-grid mt-4">
                                        <button type="submit" class="btn btn-warning fw-bold text-white btn-lg shadow-sm">
                                            <i class="fas fa-save me-1"></i> Cập nhật thông tin
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="tab-pane fade" id="images" role="tabpanel">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-success text-white fw-bold"><i class="fas fa-upload me-2"></i>Tải thêm ảnh</div>
                            <div class="card-body">
                                <form action="index.php?act=tour-images-store" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="tour_id" value="<?= $tour['id'] ?>">
                                    <input type="hidden" name="redirect_to" value="tour-edit"> 
                                    
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Chọn ảnh (Nhiều ảnh)</label>
                                        <input type="file" name="files[]" class="form-control" multiple required accept="image/*">
                                    </div>
                                    <button type="submit" class="btn btn-success w-100 fw-bold">
                                        <i class="fas fa-cloud-upload-alt me-1"></i> Tải lên
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white fw-bold">Thư viện ảnh</div>
                            <div class="card-body">
                                <?php if (empty($list_anh)): ?>
                                    <div class="text-center py-4 text-muted">Chưa có ảnh nào.</div>
                                <?php else: ?>
                                    <div class="row g-3">
                                        <?php foreach ($list_anh as $img): ?>
                                            <div class="col-6 col-md-4 col-lg-3 position-relative group-action">
                                                <div class="ratio ratio-1x1 border rounded overflow-hidden">
                                                    <img src="<?= BASE_URL . $img['duong_dan'] ?>" class="img-fluid object-fit-cover">
                                                </div>
                                                <a href="index.php?act=tour-images-delete&id=<?= $img['id'] ?>&tour_id=<?= $tour['id'] ?>&redirect_to=tour-edit" 
                                                   class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 rounded-circle shadow-sm btn-delete"
                                                   onclick="return confirm('Xóa ảnh này?')" title="Xóa">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="schedule" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-primary m-0">Chi tiết lịch trình</h5>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addScheduleModal">
                        <i class="fas fa-plus me-1"></i> Thêm ngày mới
                    </button>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 100px;">Ngày</th>
                                        <th style="width: 250px;">Tiêu đề</th>
                                        <th>Chi tiết (Giờ - Địa điểm - Nội dung)</th>
                                        <th class="text-end" style="width: 100px;">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($list_lich_trinh)): ?>
                                        <tr><td colspan="4" class="text-center py-4 text-muted">Chưa có lịch trình.</td></tr>
                                    <?php else: ?>
                                        <?php foreach ($list_lich_trinh as $lt): ?>
                                        <tr>
                                            <td class="fw-bold text-primary text-center">
                                                Ngày <?= $lt['ngay_thu'] ?? $lt['thu_tu_ngay'] ?>
                                            </td>
                                            <td class="fw-bold"><?= htmlspecialchars($lt['tieu_de']) ?></td>
                                            <td class="text-secondary">
                                                <?php if(!empty($lt['gio_bat_dau'])): ?>
                                                    <span class="badge bg-light text-dark border me-1">
                                                        <i class="far fa-clock"></i> <?= date('H:i', strtotime($lt['gio_bat_dau'])) ?>
                                                        <?= !empty($lt['gio_ket_thuc']) ? '- '.date('H:i', strtotime($lt['gio_ket_thuc'])) : '' ?>
                                                    </span>
                                                <?php endif; ?>

                                                <?php if(!empty($lt['dia_diem'])): ?>
                                                    <span class="badge bg-info bg-opacity-10 text-info border border-info me-1">
                                                        <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($lt['dia_diem']) ?>
                                                    </span>
                                                <?php endif; ?>
                                                
                                                <div class="mt-1 small text-muted">
                                                    <?= nl2br(htmlspecialchars($lt['noi_dung'] ?? $lt['hoat_dong'] ?? '')) ?>
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <a href="index.php?act=tour-schedule-delete&id=<?= $lt['id'] ?>&tour_id=<?= $tour['id'] ?>&redirect_to=tour-edit" 
                                                   class="btn btn-outline-danger btn-sm border-0" 
                                                   onclick="return confirm('Xóa lịch trình ngày này?')" title="Xóa">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

<div class="modal fade" id="addScheduleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="index.php?act=tour-schedule-store" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Thêm lịch trình mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="tour_id" value="<?= $tour['id'] ?>">
                    <input type="hidden" name="redirect_to" value="tour-edit">
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Ngày thứ</label>
                            <input type="number" name="ngay_thu" class="form-control" value="<?= count($list_lich_trinh) + 1 ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Giờ bắt đầu</label>
                            <input type="time" name="gio_bat_dau" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Giờ kết thúc</label>
                            <input type="time" name="gio_ket_thuc" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Tiêu đề</label>
                        <input type="text" name="tieu_de" class="form-control" required placeholder="VD: Tham quan Đại Nội">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Điểm tham quan</label>
                        <input type="text" name="dia_diem" class="form-control" placeholder="VD: Đại Nội, Chùa Thiên Mụ">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nội dung chi tiết</label>
                        <textarea name="noi_dung" class="form-control" rows="4" placeholder="Mô tả hoạt động..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Ghi chú</label>
                        <input type="text" name="ghi_chu" class="form-control" placeholder="Lưu ý nhỏ...">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary fw-bold">Lưu lịch trình</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Tự động điền giá chung khi nhập giá người lớn
function syncPrice(input) {
    var mainPrice = document.getElementById('main_price');
    if (mainPrice.value == '' || mainPrice.value == '0') {
        mainPrice.value = input.value;
    }
}

// Preview ảnh
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) { 
            var img = document.getElementById('imgPreview');
            img.src = e.target.result; 
            img.classList.remove('d-none');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<style>
    .btn-delete { opacity: 0; transition: opacity 0.2s; }
    .group-action:hover .btn-delete { opacity: 1; }
</style>

<?php require_once PATH_ROOT . '/views/footer.php'; ?>