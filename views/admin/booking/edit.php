<?php require_once ROOT . '/views/header.php'; ?>

<div id="layoutSidenav_content">
<main>
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
        <h1 class="text-warning fw-bold">Cập nhật Booking #<?= $booking['id'] ?></h1>
        <a href="<?= BASE_URL ?>?act=booking-list" class="btn btn-secondary shadow-sm"><i class="fas fa-arrow-left"></i> Quay lại</a>
    </div>

    <div class="card shadow-lg border-0 rounded-lg mb-4">
    <div class="card-body p-4">
        <form action="<?= BASE_URL ?>?act=booking-update" method="POST">
            <input type="hidden" name="id" value="<?= $booking['id'] ?>">

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Khách Hàng</label>
                    <select name="khach_hang_id" class="form-select" required>
                        <?php foreach ($customers as $kh): ?>
                            <option value="<?= $kh['id'] ?>" <?= ($booking['khach_hang_id'] == $kh['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($kh['ho_ten']) ?> (<?= htmlspecialchars($kh['so_dien_thoai']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Tour</label>
                    <select name="tour_id" id="tour_id" class="form-select" required onchange="updateTotal()">
                        <?php foreach ($tours as $t): 
                            $giaNL = $t['gia_nguoi_lon'] > 0 ? $t['gia_nguoi_lon'] : $t['gia_tour'];
                            $giaTE = $t['gia_tre_em'] > 0 ? $t['gia_tre_em'] : ($giaNL * 0.7);
                        ?>
                            <option value="<?= $t['id'] ?>" 
                                data-price-adult="<?= $giaNL ?>" 
                                data-price-child="<?= $giaTE ?>"
                                <?= ($booking['tour_id'] == $t['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($t['ten_tour']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Người lớn</label>
                    <input type="number" name="so_luong_nguoi_lon" id="so_luong_nguoi_lon" 
                           class="form-control text-center fw-bold" 
                           value="<?= $booking['so_luong_nguoi_lon'] ?>" min="1" required oninput="updateTotal()">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Trẻ em</label>
                    <input type="number" name="so_luong_tre_em" id="so_luong_tre_em" 
                           class="form-control text-center" 
                           value="<?= $booking['so_luong_tre_em'] ?>" min="0" oninput="updateTotal()">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Loại Booking</label>
                    <select name="loai_booking" class="form-select">
                        <option value="DOAN" <?= $booking['loai_booking'] == 'DOAN' ? 'selected' : '' ?>>Đoàn</option>
                        <option value="LE" <?= $booking['loai_booking'] == 'LE' ? 'selected' : '' ?>>Khách Lẻ</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Trạng thái</label>
                    <select name="trang_thai" class="form-select">
                        <?php foreach(['CHO_XU_LY', 'DA_XAC_NHAN', 'DA_THANH_TOAN', 'HOAN_THANH', 'HUY'] as $st): ?>
                            <option value="<?= $st ?>" <?= $booking['trang_thai'] == $st ? 'selected' : '' ?>><?= $st ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="card bg-light border-warning mb-4">
                <div class="card-header bg-warning text-dark fw-bold">
                    <i class="fas fa-money-bill-wave me-2"></i>Cập nhật thanh toán
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4 border-end">
                            <label class="small text-muted fw-bold">TỔNG TIỀN (MỚI)</label>
                            <div class="fs-4 fw-bold text-primary" id="display_tong_tien">
                                <?= number_format($booking['tong_tien']) ?> ₫
                            </div>
                            <input type="hidden" name="tong_tien" id="input_tong_tien" value="<?= $booking['tong_tien'] ?>">
                        </div>
                        <div class="col-md-4 border-end">
                            <label class="small text-muted fw-bold">ĐÃ THANH TOÁN</label>
                            <div class="input-group mt-1 px-3">
                                <input type="text" name="da_thanh_toan" id="input_da_thanh_toan" 
                                       class="form-control text-center fw-bold text-success"
                                       value="<?= (int)$booking['da_thanh_toan'] ?>" 
                                       oninput="updateRemaining()">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="small text-muted fw-bold">CÒN LẠI</label>
                            <div class="fs-4 fw-bold text-danger" id="display_con_lai">
                                <?= number_format($booking['tong_tien'] - $booking['da_thanh_toan']) ?> ₫
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-warning px-4 fw-bold text-white"><i class="fas fa-save"></i> Cập nhật</button>
            </div>
        </form>
    </div>
    </div>
</div>
</main>
</div>

<script>
    const formatCurrency = (amount) => new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);

    function updateTotal() {
        let opt = document.getElementById('tour_id').options[document.getElementById('tour_id').selectedIndex];
        let priceNL = parseFloat(opt.getAttribute('data-price-adult')) || 0;
        let priceTE = parseFloat(opt.getAttribute('data-price-child')) || 0;
        
        let slNL = parseInt(document.getElementById('so_luong_nguoi_lon').value) || 0;
        let slTE = parseInt(document.getElementById('so_luong_tre_em').value) || 0;

        let total = (slNL * priceNL) + (slTE * priceTE);
        
        document.getElementById('display_tong_tien').innerText = formatCurrency(total);
        document.getElementById('input_tong_tien').value = total;
        
        updateRemaining();
    }

    function updateRemaining() {
        let total = parseFloat(document.getElementById('input_tong_tien').value) || 0;
        let paid = parseFloat(document.getElementById('input_da_thanh_toan').value.replace(/[^0-9]/g, '')) || 0;
        document.getElementById('display_con_lai').innerText = formatCurrency(total - paid);
    }
</script>

<?php require_once ROOT . '/views/footer.php'; ?>