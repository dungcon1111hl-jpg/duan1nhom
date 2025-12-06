<?php require_once PATH_ROOT . '/views/header.php'; ?>

<?php 
    // Nhận biến từ Controller, gán mặc định nếu chưa có để tránh lỗi
    $dsNcc = $dsNcc ?? []; 
    $dsHDV = $dsHDV ?? []; 
    $dsDanhMuc = $dsDanhMuc ?? []; 
?>

<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <h2 class="fw-bold text-success"><i class="fas fa-plus-circle me-2"></i>Thêm Tour Mới</h2>
            <a href="index.php?act=tours" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left me-1"></i> Quay lại
            </a>
        </div>

        <form action="index.php?act=tour-store" method="POST" enctype="multipart/form-data">
            <div class="row">
                
                <div class="col-lg-8">
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-header bg-white fw-bold text-primary py-3">
                            <i class="fas fa-info-circle me-1"></i> 1. Thông tin & Giá
                        </div>
                        <div class="card-body">
                            
                            <div class="row g-3 mb-3">
                                <div class="col-md-8">
                                    <label class="form-label fw-bold">Tên Tour <span class="text-danger">*</span></label>
                                    <input type="text" name="ten_tour" class="form-control" required placeholder="VD: Du lịch Đà Nẵng - Hội An 3N2Đ">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Mã Tour</label>
                                    <input type="text" name="ma_tour" class="form-control" placeholder="VD: DNHA01 (Tự động nếu để trống)">
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Danh mục Tour</label>
                                    <select name="loai_tour" class="form-select" required>
                                        <option value="">-- Chọn danh mục --</option>
                                        <?php foreach ($dsDanhMuc as $dm): ?>
                                            <option value="<?= $dm['id'] ?>">
                                                <?= htmlspecialchars($dm['ten_danh_muc']) ?>
                                            </option>
                                        <?php endforeach; ?>
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
                                        <option value="KHACH_DOAN">Khách đoàn</option>
                                        <option value="GIA_DINH">Gia đình</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3 bg-light p-3 rounded border">
                                <label class="form-label fw-bold text-success"><i class="fas fa-tags me-1"></i> Bảng giá chi tiết (VNĐ)</label>
                                <div class="row g-2">
                                    <div class="col-md-3">
                                        <label class="small text-muted fw-bold">Người lớn (>12t)</label>
                                        <input type="number" name="gia_nguoi_lon" class="form-control fw-bold text-primary" placeholder="0" oninput="syncPrice(this)">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="small text-muted fw-bold">Trẻ em (5-11t)</label>
                                        <input type="number" name="gia_tre_em" class="form-control" placeholder="0">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="small text-muted fw-bold">Em bé (<5t)</label>
                                        <input type="number" name="gia_em_be" class="form-control" placeholder="0">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="small text-muted fw-bold">Phụ thu/Lễ tết</label>
                                        <input type="number" name="phu_thu" class="form-control" placeholder="0">
                                    </div>
                                </div>
                                <div class="mt-2 d-flex align-items-center">
                                    <label class="small me-2 fw-bold text-secondary">* Giá hiển thị chính (trên web):</label>
                                    <input type="number" name="gia_tour" id="main_price" class="form-control form-control-sm w-25 fw-bold text-danger" placeholder="Tự động điền từ giá NL">
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-primary"><i class="fas fa-map-marker-alt me-1"></i> Điểm đi</label>
                                    <input type="text" name="dia_diem_bat_dau" class="form-control" placeholder="VD: Hà Nội">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-warning"><i class="fas fa-exchange-alt me-1"></i> Trung chuyển</label>
                                    <input type="text" name="diem_trung_chuyen" class="form-control" placeholder="VD: Huế (tùy chọn)">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-success"><i class="fas fa-flag-checkered me-1"></i> Điểm đến</label>
                                    <input type="text" name="dia_diem_ket_thuc" class="form-control" placeholder="VD: Đà Nẵng">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-info"><i class="fas fa-handshake me-1"></i> Chọn Nhà cung cấp dịch vụ</label>
                                <div class="border rounded p-3 bg-white shadow-sm" style="max-height: 180px; overflow-y: auto;">
                                    <?php if (!empty($dsNcc)): ?>
                                        <div class="row g-2">
                                            <?php foreach ($dsNcc as $ncc): ?>
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="ncc[]" value="<?= $ncc['id'] ?>" id="ncc_<?= $ncc['id'] ?>">
                                                        <label class="form-check-label cursor-pointer" for="ncc_<?= $ncc['id'] ?>">
                                                            <strong><?= htmlspecialchars($ncc['ten_don_vi']) ?></strong> 
                                                            <br>
                                                            <small class="text-muted"><i class="fas fa-tag me-1"></i><?= htmlspecialchars($ncc['loai_dich_vu']) ?></small>
                                                        </label>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-center text-muted py-3">
                                            <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                            Chưa có dữ liệu Nhà cung cấp. <a href="index.php?act=nha-cung-cap" target="_blank">Thêm ngay</a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Mô tả ngắn (SEO)</label>
                                <textarea name="mo_ta_ngan" class="form-control" rows="2" placeholder="Tóm tắt ngắn gọn về tour..."></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Chi tiết lịch trình (Tổng quan)</label>
                                <textarea name="mo_ta_chi_tiet" class="form-control" rows="5" placeholder="Nhập nội dung chi tiết chương trình tour..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-header bg-white fw-bold text-primary py-3">
                            <i class="fas fa-cogs me-1"></i> 2. Cấu hình & Nhân sự
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Ngày khởi hành <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="ngay_khoi_hanh" id="startDate" class="form-control" required onchange="checkHDV()">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Ngày kết thúc <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="ngay_ket_thuc" id="endDate" class="form-control" required onchange="checkHDV()">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold"><i class="fas fa-user-tie me-1"></i> Hướng dẫn viên</label>
                                <select name="hdv_id" id="hdvSelect" class="form-select">
                                    <option value="">-- Chọn HDV phụ trách --</option>
                                    <?php foreach ($dsHDV as $hdv): ?>
                                        <option value="<?= $hdv['id'] ?>" data-name="<?= htmlspecialchars($hdv['ho_ten']) ?>">
                                            <?= htmlspecialchars($hdv['ho_ten']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div id="hdvStatus" class="form-text mt-1 fw-bold"></div>
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
                                    <img id="imgPreview" src="https://via.placeholder.com/300x200?text=No+Image" 
                                         class="img-fluid rounded border" style="max-height: 180px; width: 100%; object-fit: cover;">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Trạng thái</label>
                                <select name="trang_thai" class="form-select">
                                    <option value="CON_VE">Còn vé (Mở bán)</option>
                                    <option value="HET_VE">Hết vé</option>
                                    <option value="DA_KHOI_HANH">Đã khởi hành</option>
                                    <option value="HUY">Tạm dừng/Hủy</option>
                                    <option value="NGUNG_HOAT_DONG">Ngừng hoạt động</option>
                                </select>
                            </div>
                            
                            <div class="d-grid gap-2 pt-3">
                                <button type="submit" class="btn btn-success btn-lg fw-bold shadow-sm">
                                    <i class="fas fa-save me-2"></i> LƯU TOUR MỚI
                                </button>
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
// 1. Đồng bộ giá
function syncPrice(input) {
    var mainPrice = document.getElementById('main_price');
    if (mainPrice.value == '' || mainPrice.value == '0') {
        mainPrice.value = input.value;
    }
}

// 2. Preview ảnh
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) { 
            document.getElementById('imgPreview').src = e.target.result; 
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// 3. [QUAN TRỌNG] Kiểm tra lịch bận HDV
async function checkHDV() {
    const start = document.getElementById('startDate').value;
    const end = document.getElementById('endDate').value;
    const select = document.getElementById('hdvSelect');
    const statusDiv = document.getElementById('hdvStatus');

    // Reset lại trạng thái
    for (let i = 0; i < select.options.length; i++) {
        let opt = select.options[i];
        opt.disabled = false;
        opt.innerText = opt.getAttribute('data-name') || opt.text; // Trả về tên gốc
        opt.style.color = "";
    }
    statusDiv.innerText = "";

    if (start && end) {
        statusDiv.innerHTML = '<span class="text-warning"><i class="fas fa-spinner fa-spin"></i> Đang kiểm tra lịch HDV...</span>';
        
        try {
            // Gọi API từ TourController (Đảm bảo route 'api-check-hdv' đã có trong index.php)
            const response = await fetch(`index.php?act=api-check-hdv&start=${start}&end=${end}`);
            const busyList = await response.json(); // Mảng ID các HDV bận { '1': 'Lý do', '3': 'Lý do' }

            let countBusy = 0;
            for (let i = 0; i < select.options.length; i++) {
                const opt = select.options[i];
                const hdvId = opt.value;

                if (busyList[hdvId]) {
                    opt.disabled = true; // Khóa không cho chọn
                    opt.innerText = `${opt.getAttribute('data-name')} (BẬN: ${busyList[hdvId]})`;
                    opt.style.color = "red";
                    countBusy++;
                    
                    // Nếu đang chọn người này thì bỏ chọn
                    if (select.value == hdvId) select.value = "";
                }
            }

            if (countBusy > 0) {
                statusDiv.innerHTML = `<span class="text-danger"><i class="fas fa-exclamation-triangle"></i> Có ${countBusy} HDV bận trong thời gian này.</span>`;
            } else {
                statusDiv.innerHTML = '<span class="text-success"><i class="fas fa-check-circle"></i> Tất cả HDV đều sẵn sàng.</span>';
            }

        } catch (error) {
            console.error(error);
            statusDiv.innerText = "Lỗi khi kết nối kiểm tra lịch.";
        }
    }
}
</script>

<?php require_once PATH_ROOT . '/views/footer.php'; ?>