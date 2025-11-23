<?php require_once PATH_ROOT . '/views/header.php'; ?>
<?php 
    $dsNcc = $dsNcc ?? []; 
    $dsHDV = $dsHDV ?? []; // Khởi tạo biến để tránh lỗi nếu rỗng
?>

<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <h2 class="fw-bold text-success"><i class="fas fa-plus-circle me-2"></i>Thêm Tour Mới</h2>
            <a href="index.php?act=tours" class="btn btn-secondary shadow-sm"><i class="fas fa-arrow-left me-1"></i> Quay lại</a>
        </div>

        <form action="index.php?act=tour-store" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-header bg-white fw-bold text-primary py-3">1. Thông tin & Giá</div>
                        <div class="card-body">
                            <div class="row g-3 mb-3">
                                <div class="col-md-8">
                                    <label class="form-label fw-bold">Tên Tour <span class="text-danger">*</span></label>
                                    <input type="text" name="ten_tour" class="form-control" required placeholder="VD: Du lịch Đà Nẵng - Hội An 3N2Đ">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Mã Tour</label>
                                    <input type="text" name="ma_tour" class="form-control" placeholder="VD: DNHA01">
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Loại hình</label>
                                    <select name="loai_tour" class="form-select">
                                        <option value="TRONG_NUOC">Trong nước</option>
                                        <option value="QUOC_TE">Quốc tế</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Phân loại nâng cao</label>
                                    <select name="loai_tour_nang_cao" class="form-select">
                                        <option value="TRON_GOI">Tour Trọn gói</option>
                                        <option value="GHEP_DOAN">Tour Ghép đoàn</option>
                                        <option value="THEO_YEU_CAU">Tour Theo yêu cầu</option>
                                        <option value="VIP">Tour VIP/Cao cấp</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Đối tượng khách</label>
                                    <select name="doi_tuong_khach" class="form-select">
                                        <option value="KHACH_LE">Khách lẻ</option>
                                        <option value="KHACH_DOAN">Khách đoàn (Doanh nghiệp)</option>
                                        <option value="GIA_DINH">Gia đình</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-success"><i class="fas fa-tags me-1"></i> Bảng giá chi tiết (VNĐ)</label>
                                <div class="row g-2">
                                    <div class="col-md-3">
                                        <label class="small text-muted">Người lớn (>12t)</label>
                                        <input type="number" name="gia_nguoi_lon" class="form-control fw-bold" placeholder="0" oninput="syncPrice(this)">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="small text-muted">Trẻ em (5-11t)</label>
                                        <input type="number" name="gia_tre_em" class="form-control" placeholder="0">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="small text-muted">Em bé (<5t)</label>
                                        <input type="number" name="gia_em_be" class="form-control" placeholder="0">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="small text-muted">Phụ thu/Lễ tết</label>
                                        <input type="number" name="phu_thu" class="form-control" placeholder="0">
                                    </div>
                                </div>
                                <div class="form-text small">* Giá hiển thị chính: <input type="number" name="gia_tour" id="main_price" class="d-inline-block form-control form-control-sm w-25" placeholder="Giá chung"></div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-primary">Điểm đi</label>
                                    <input type="text" name="dia_diem_bat_dau" class="form-control" placeholder="VD: Hà Nội">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-warning">Trung chuyển</label>
                                    <input type="text" name="diem_trung_chuyen" class="form-control" placeholder="VD: Huế (tùy chọn)">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-success">Điểm đến</label>
                                    <input type="text" name="dia_diem_ket_thuc" class="form-control" placeholder="VD: Đà Nẵng">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-info"><i class="fas fa-handshake me-1"></i> Nhà cung cấp dịch vụ</label>
                                <div class="border rounded p-3 bg-white shadow-sm" style="max-height: 150px; overflow-y: auto;">
                                    <?php if(!empty($dsNcc)): ?>
                                        <div class="row g-2">
                                            <?php foreach ($dsNcc as $ncc): ?>
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="ncc[]" value="<?= $ncc['id'] ?>" id="ncc_<?= $ncc['id'] ?>">
                                                        <label class="form-check-label" for="ncc_<?= $ncc['id'] ?>">
                                                            <strong><?= htmlspecialchars($ncc['ten_don_vi']) ?></strong> 
                                                            <small class="text-muted">(<?= htmlspecialchars($ncc['loai_dich_vu']) ?>)</small>
                                                        </label>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-muted small mb-0">Chưa có NCC. <a href="index.php?act=nha-cung-cap">Thêm ngay</a></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold"><i class="fas fa-user-tie me-1"></i> Hướng dẫn viên (Dự kiến)</label>
                                <select name="hdv_id" class="form-select">
                                    <option value="">-- Chọn Hướng dẫn viên --</option>
                                    <?php if (!empty($dsHDV)): foreach ($dsHDV as $hdv): ?>
                                        <option value="<?= $hdv['id'] ?>">
                                            <?= htmlspecialchars($hdv['ho_ten']) ?> 
                                            (<?= !empty($hdv['ngon_ngu']) ? $hdv['ngon_ngu'] : 'Chưa cập nhật' ?>)
                                        </option>
                                    <?php endforeach; endif; ?>
                                </select>
                                <div class="form-text">Chọn HDV phụ trách chính cho tour này (nếu có).</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Mô tả chi tiết</label>
                                <textarea name="mo_ta_chi_tiet" class="form-control" rows="4"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-header bg-white fw-bold text-primary py-3">2. Cấu hình Tour</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Ngày khởi hành</label>
                                <input type="date" name="ngay_khoi_hanh" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Ngày kết thúc</label>
                                <input type="date" name="ngay_ket_thuc" class="form-control" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Số lượng khách (Min - Max)</label>
                                <div class="input-group">
                                    <input type="number" name="so_khach_toithieu" class="form-control" value="10" placeholder="Min">
                                    <span class="input-group-text">-</span>
                                    <input type="number" name="so_luong_ve" class="form-control" value="30" placeholder="Max">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Số chỗ còn nhận</label>
                                <input type="number" name="so_ve_con_lai" class="form-control" value="30">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Ảnh đại diện</label>
                                <input type="file" name="anh_minh_hoa" class="form-control" accept="image/*" onchange="previewImage(this)">
                                <div class="mt-2 text-center">
                                    <img id="imgPreview" src="https://via.placeholder.com/300x200?text=No+Image" class="img-fluid rounded border" style="max-height: 150px;">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Trạng thái</label>
                                <select name="trang_thai" class="form-select">
                                    <option value="CON_VE">Còn vé (Mở bán)</option>
                                    <option value="HET_VE">Hết vé</option>
                                    <option value="DA_KHOI_HANH">Đã khởi hành</option>
                                    <option value="HUY">Tạm dừng/Hủy</option>
                                </select>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success fw-bold py-2">LƯU TOUR MỚI</button>
                                <a href="index.php?act=tours" class="btn btn-outline-secondary">Hủy bỏ</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

<script>
function syncPrice(input) {
    var mainPrice = document.getElementById('main_price');
    if (mainPrice.value == '' || mainPrice.value == '0') {
        mainPrice.value = input.value;
    }
}

function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) { document.getElementById('imgPreview').src = e.target.result; };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
<?php require_once PATH_ROOT . '/views/footer.php'; ?>