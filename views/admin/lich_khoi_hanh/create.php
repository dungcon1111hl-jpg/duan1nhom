<?php require_once PATH_ROOT . '/views/header.php'; ?>

<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <h2 class="fw-bold text-success">Lập Kế Hoạch Khởi Hành</h2>
            <a href="index.php?act=lich-khoi-hanh" class="btn btn-secondary">Quay lại</a>
        </div>

        <form action="index.php?act=lich-khoi-hanh-store" method="POST" id="planForm">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white fw-bold">1. Thông tin Chuyến đi</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Chọn Tour</label>
                            <select name="tour_id" class="form-select" required>
                                <?php foreach ($tours as $t): ?>
                                    <option value="<?= $t['id'] ?>"><?= $t['ten_tour'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Khởi hành <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="ngay_khoi_hanh" id="startDate" class="form-control" required onchange="checkHDV()">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Kết thúc <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="ngay_ket_thuc" id="endDate" class="form-control" required onchange="checkHDV()">
                        </div>
                        
                        </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white fw-bold">2. Phân Bổ Nhân Sự</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Hướng dẫn viên</label>
                            <select name="nhan_su[0][hdv_id]" id="hdvSelect" class="form-select" onchange="fillHDVName(this)">
                                <option value="">-- Chọn HDV --</option>
                                <?php foreach ($hdvs as $h): ?>
                                    <option value="<?= $h['id'] ?>" data-name="<?= $h['ho_ten'] ?>" data-phone="<?= $h['so_dien_thoai'] ?>">
                                        <?= $h['ho_ten'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div id="hdvStatus" class="form-text text-danger fw-bold mt-1"></div>
                            
                            <input type="hidden" name="nhan_su[0][ho_ten]" id="hdvName">
                            <input type="hidden" name="nhan_su[0][sdt]" id="hdvPhone">
                            <input type="hidden" name="nhan_su[0][vai_tro]" value="HUONG_DAN_VIEN">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Tài xế</label>
                            <input type="text" name="nhan_su[1][ho_ten]" class="form-control" placeholder="Tên tài xế">
                            <input type="hidden" name="nhan_su[1][vai_tro]" value="TAI_XE">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Điều hành</label>
                            <input type="text" name="nhan_su[2][ho_ten]" class="form-control" placeholder="Tên nhân viên">
                            <input type="hidden" name="nhan_su[2][vai_tro]" value="HAU_CAN">
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-grid mb-5">
                <button type="submit" class="btn btn-primary btn-lg fw-bold">LƯU KẾ HOẠCH</button>
            </div>
        </form>
    </div>
</main>

<script>
function fillHDVName(select) {
    // Tự động điền tên và SĐT vào input ẩn khi chọn dropdown
    var option = select.options[select.selectedIndex];
    if (option.value) {
        document.getElementById('hdvName').value = option.getAttribute('data-name');
        document.getElementById('hdvPhone').value = option.getAttribute('data-phone');
    } else {
        document.getElementById('hdvName').value = '';
    }
}

async function checkHDV() {
    const start = document.getElementById('startDate').value;
    const end = document.getElementById('endDate').value;
    const select = document.getElementById('hdvSelect');
    const statusDiv = document.getElementById('hdvStatus');

    // Reset lại trạng thái các option
    for (let i = 0; i < select.options.length; i++) {
        select.options[i].disabled = false;
        select.options[i].text = select.options[i].text.replace(' (Đang bận)', '');
    }
    statusDiv.innerText = '';

    if (start && end) {
        // Gọi API kiểm tra
        try {
            const response = await fetch(`index.php?act=api-check-hdv&start=${start}&end=${end}`);
            const busyIds = await response.json();

            // Duyệt qua danh sách bận và khóa option tương ứng
            if (busyIds.length > 0) {
                let countBusy = 0;
                for (let i = 0; i < select.options.length; i++) {
                    const opt = select.options[i];
                    // Nếu ID của option nằm trong danh sách bận (ép kiểu về string để so sánh)
                    if (busyIds.includes(opt.value) || busyIds.includes(parseInt(opt.value))) {
                        opt.disabled = true;
                        opt.text = opt.text + ' (Đang bận)';
                        countBusy++;
                    }
                }
                statusDiv.innerText = `⚠️ Có ${countBusy} HDV bị trùng lịch trong thời gian này.`;
            }
        } catch (error) {
            console.error('Lỗi kiểm tra lịch:', error);
        }
    }
}
</script>

<?php require_once PATH_ROOT . '/views/footer.php'; ?>