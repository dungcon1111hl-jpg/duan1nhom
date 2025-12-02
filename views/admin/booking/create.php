<?php require_once ROOT . '/views/header.php'; ?>

<div id="layoutSidenav_content">
<main>
<div class="container-fluid px-4">

<h1 class="mt-4 text-primary fw-bold">Tạo Booking Mới</h1>

<div class="card shadow border-0 rounded-3 mb-5">
<div class="card-body p-4">

<form action="<?= BASE_URL ?>?act=booking-store" method="POST" id="bookingForm">

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <label class="form-label fw-bold">Khách hàng <span class="text-danger">*</span></label>
        <select name="khach_hang_id" id="khach_hang_id" class="form-select" required>
            <option value="" disabled selected>-- Chọn khách hàng --</option>
            <?php foreach ($customers as $kh): ?>
                <option value="<?= $kh['id'] ?>"
                    data-ten="<?= htmlspecialchars($kh['ho_ten']) ?>"
                    data-sdt="<?= htmlspecialchars($kh['so_dien_thoai']) ?>"
                    data-email="<?= htmlspecialchars($kh['email']) ?>"
                    data-diachi="<?= htmlspecialchars($kh['dia_chi']) ?>">
                    <?= htmlspecialchars($kh['ho_ten']) ?> (<?= htmlspecialchars($kh['so_dien_thoai']) ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold">Chọn Tour <span class="text-danger">*</span></label>
        <select name="tour_id" id="tour_id" class="form-select" required onchange="updateTotal()">
            <option value="" disabled selected>-- Chọn tour --</option>
            <?php foreach ($tours as $t): ?>
                <?php 
                    // === FIX LỖI HIỂN THỊ GIÁ (QUAN TRỌNG) ===
                    
                    // 1. Ép kiểu float trực tiếp để lấy đúng giá trị số từ DB (100000.00 -> 100000)
                    // Không dùng str_replace xóa dấu chấm ở bước này vì sẽ làm sai lệch số thập phân
                    $db_gia_nl   = (float)$t['gia_nguoi_lon'];
                    $db_gia_te   = (float)$t['gia_tre_em'];
                    $db_gia_tour = (float)$t['gia_tour'];

                    // 2. Logic ưu tiên: Luôn lấy GIÁ NGƯỜI LỚN trước
                    // Chỉ khi giá NL bằng 0 thì mới lấy giá tour chung
                    $priceAdult = ($db_gia_nl > 0) ? $db_gia_nl : $db_gia_tour;

                    // 3. Logic giá trẻ em: Lấy giá trẻ em trong DB
                    // Nếu bằng 0 thì tự động tính = 75% giá người lớn (hoặc tùy chỉnh)
                    $priceChild = ($db_gia_te > 0) ? $db_gia_te : ($priceAdult * 0.75);
                ?>
                <option value="<?= $t['id'] ?>"
                    data-ten="<?= htmlspecialchars($t['ten_tour']) ?>"
                    data-price-adult="<?= $priceAdult ?>"
                    data-price-child="<?= $priceChild ?>">
                    <?= htmlspecialchars($t['ten_tour']) ?> 
                    (NL: <?= number_format($priceAdult, 0, ',', '.') ?> đ - TE: <?= number_format($priceChild, 0, ',', '.') ?> đ)
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <label class="form-label fw-bold">Người lớn</label>
        <input type="number" name="so_luong_nguoi_lon" id="so_luong_nguoi_lon" class="form-control" value="1" min="1" oninput="updateTotal()">
    </div>
    <div class="col-md-3">
        <label class="form-label fw-bold">Trẻ em</label>
        <input type="number" name="so_luong_tre_em" id="so_luong_tre_em" class="form-control" value="0" min="0" oninput="updateTotal()">
    </div>
    <div class="col-md-3">
        <label class="form-label fw-bold">Loại booking</label>
        <select name="loai_booking" class="form-select">
            <option value="DOAN">Đặt theo Đoàn</option>
            <option value="LE">Khách Lẻ</option>
            <option value="CHON_GOI">Gói cố định</option>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label fw-bold">Trạng thái</label>
        <select name="trang_thai" class="form-select">
            <option value="CHO_XU_LY">Chờ xử lý</option>
            <option value="DA_XAC_NHAN">Đã xác nhận</option>
            <option value="DA_THANH_TOAN">Đã thanh toán (Cọc)</option>
            <option value="HOAN_THANH">Hoàn thành</option>
            <option value="HUY">Hủy</option>
        </select>
    </div>
</div>

<div class="card bg-light border-success mb-4">
    <div class="card-header bg-success text-white fw-bold">
        <i class="fas fa-calculator me-2"></i>Thông tin Thanh toán
    </div>
    <div class="card-body">
        <div class="row g-3 align-items-center">
            <div class="col-md-4 text-center border-end">
                <label class="text-muted small fw-bold text-uppercase">Tổng tiền (Dự kiến)</label>
                <div class="fs-4 fw-bold text-primary" id="display_tong_tien">0 ₫</div>
                <input type="hidden" name="tong_tien" id="input_tong_tien" value="0">
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold text-success">Số tiền đã cọc/trả trước</label>
                <div class="input-group">
                    <input type="text" name="da_thanh_toan" id="input_da_thanh_toan" 
                           class="form-control fw-bold text-success" 
                           placeholder="0" oninput="updateRemaining()">
                    <span class="input-group-text">VNĐ</span>
                </div>
                <div class="form-text">Nhập số tiền thực tế khách đưa (Ví dụ: 500.000).</div>
            </div>

            <div class="col-md-4 text-center border-start">
                <label class="text-muted small fw-bold text-uppercase">Số tiền còn lại</label>
                <div class="fs-4 fw-bold text-danger" id="display_con_lai">0 ₫</div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" name="snapshot_kh_ho_ten" id="snapshot_kh_ho_ten">
<input type="hidden" name="snapshot_kh_so_dien_thoai" id="snapshot_kh_so_dien_thoai">
<input type="hidden" name="snapshot_kh_email" id="snapshot_kh_email">
<input type="hidden" name="snapshot_kh_dia_chi" id="snapshot_kh_dia_chi">
<input type="hidden" name="snapshot_ten_tour" id="snapshot_ten_tour">

<div class="d-flex justify-content-end gap-2">
    <a href="<?= BASE_URL ?>?act=booking-list" class="btn btn-secondary px-4">Hủy bỏ</a>
    <button type="submit" class="btn btn-primary px-4 fw-bold"><i class="fas fa-save me-1"></i> Lưu Booking</button>
</div>

</form>
</div></div>
</div>
</main>
</div>

<script>
    // Hàm format tiền tệ (Ví dụ: 1000000 => 1.000.000 ₫)
    const formatCurrency = (amount) => {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
    }

    // Tự động điền thông tin khách
    document.getElementById('khach_hang_id').addEventListener('change', function() {
        let opt = this.options[this.selectedIndex];
        document.getElementById('snapshot_kh_ho_ten').value = opt.getAttribute('data-ten');
        document.getElementById('snapshot_kh_so_dien_thoai').value = opt.getAttribute('data-sdt');
        document.getElementById('snapshot_kh_email').value = opt.getAttribute('data-email');
        document.getElementById('snapshot_kh_dia_chi').value = opt.getAttribute('data-diachi');
    });

    // Tự động điền tên tour
    document.getElementById('tour_id').addEventListener('change', function() {
        let opt = this.options[this.selectedIndex];
        document.getElementById('snapshot_ten_tour').value = opt.getAttribute('data-ten');
    });

    // Hàm tính tổng tiền (JS)
    function updateTotal() {
        let tourSelect = document.getElementById('tour_id');
        if(tourSelect.selectedIndex <= 0) return;
        
        let tourOpt = tourSelect.options[tourSelect.selectedIndex];
        
        // Lấy giá trị số thực từ thuộc tính data-price (đã được PHP xử lý chuẩn ở trên)
        let priceNL = parseFloat(tourOpt.getAttribute('data-price-adult')) || 0;
        let priceTE = parseFloat(tourOpt.getAttribute('data-price-child')) || 0;

        let slNL = parseInt(document.getElementById('so_luong_nguoi_lon').value) || 0;
        let slTE = parseInt(document.getElementById('so_luong_tre_em').value) || 0;

        let total = (slNL * priceNL) + (slTE * priceTE);

        document.getElementById('display_tong_tien').innerText = formatCurrency(total);
        document.getElementById('input_tong_tien').value = total;
        
        updateRemaining();
    }

    function updateRemaining() {
        let total = parseFloat(document.getElementById('input_tong_tien').value) || 0;
        
        // Lấy tiền cọc: Xóa tất cả ký tự không phải số (dấu chấm, phẩy, chữ) để tính toán
        let rawDeposit = document.getElementById('input_da_thanh_toan').value;
        let deposit = parseFloat(rawDeposit.replace(/[^0-9]/g, '')) || 0;

        let remaining = total - deposit;

        document.getElementById('display_con_lai').innerText = formatCurrency(remaining);
    }
</script>

<?php require_once ROOT . '/views/footer.php'; ?>