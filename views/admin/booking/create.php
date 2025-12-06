<?php require_once PATH_ROOT . '/views/header.php'; ?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">

            <h1 class="mt-4 text-primary fw-bold">Tạo Booking Mới</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="index.php?act=bookings">Quản lý Booking</a></li>
                <li class="breadcrumb-item active">Tạo mới</li>
            </ol>

            <div class="card shadow border-0 rounded-3 mb-5">
                <div class="card-body p-4">

                    <form action="index.php?act=booking-store" method="POST" id="bookingForm">

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Khách hàng <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select name="khach_hang_id" id="khach_hang_id" class="form-select" required>
                                        <option value="" disabled selected>-- Chọn khách hàng --</option>
                                        <?php foreach ($khachhangs as $kh): ?>
                                            <option value="<?= $kh['id'] ?>"
                                                data-ten="<?= htmlspecialchars($kh['ho_ten']) ?>"
                                                data-sdt="<?= htmlspecialchars($kh['so_dien_thoai']) ?>"
                                                data-email="<?= htmlspecialchars($kh['email']) ?>"
                                                data-diachi="<?= htmlspecialchars($kh['dia_chi']) ?>">
                                                <?= htmlspecialchars($kh['ho_ten']) ?> (<?= htmlspecialchars($kh['so_dien_thoai']) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addCustomerModal" title="Thêm khách hàng mới">
                                        <i class="fas fa-user-plus"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Chọn Tour <span class="text-danger">*</span></label>
                                <select name="tour_id" id="tour_id" class="form-select" required onchange="updateTotal()">
                                    <option value="" disabled selected>-- Chọn tour --</option>
                                    <?php foreach ($tours as $t): ?>
                                        <?php 
                                            $db_gia_nl   = (float)$t['gia_nguoi_lon'];
                                            $db_gia_te   = (float)$t['gia_tre_em'];
                                            $db_gia_tour = (float)$t['gia_tour'];

                                            // Logic ưu tiên giá
                                            $priceAdult = ($db_gia_nl > 0) ? $db_gia_nl : $db_gia_tour;
                                            $priceChild = ($db_gia_te > 0) ? $db_gia_te : ($priceAdult * 0.75);
                                        ?>
                                        <option value="<?= $t['id'] ?>"
                                            data-ten="<?= htmlspecialchars($t['ten_tour']) ?>"
                                            data-price-adult="<?= $priceAdult ?>"
                                            data-price-child="<?= $priceChild ?>">
                                            <?= htmlspecialchars($t['ten_tour']) ?> 
                                            (NL: <?= number_format($priceAdult, 0, ',', '.') ?> đ)
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
                                    <option value="LE">Khách Lẻ</option>
                                    <option value="DOAN">Đặt theo Đoàn</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Trạng thái</label>
                                <select name="trang_thai" class="form-select">
                                    <option value="CHO_XU_LY">Chờ xử lý</option>
                                    <option value="DA_XAC_NHAN">Đã xác nhận</option>
                                    <option value="DA_THANH_TOAN">Đã thanh toán (Cọc)</option>
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

                        <hr>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="text-success fw-bold mb-0"><i class="fas fa-users"></i> Danh sách Hành khách đi cùng</h5>
                            <button type="button" class="btn btn-sm btn-primary" onclick="addPassengerRow()">
                                <i class="fas fa-plus"></i> Thêm hành khách
                            </button>
                        </div>

                        <div class="table-responsive mb-3">
                            <table class="table table-bordered table-hover" id="passengerTable">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">STT</th>
                                        <th width="25%">Họ và tên</th>
                                        <th width="15%">Giới tính</th>
                                        <th width="15%">Ngày sinh</th>
                                        <th>Ghi chú</th>
                                        <th width="5%">Xóa</th>
                                    </tr>
                                </thead>
                                <tbody id="passengerList">
                                    <tr id="row_0">
                                        <td class="text-center fw-bold">1</td>
                                        <td><input type="text" name="passengers[0][ho_ten]" class="form-control form-control-sm" placeholder="Nhập họ tên"></td>
                                        <td>
                                            <select name="passengers[0][gioi_tinh]" class="form-select form-select-sm">
                                                <option value="NAM">Nam</option>
                                                <option value="NU">Nữ</option>
                                            </select>
                                        </td>
                                        <td><input type="date" name="passengers[0][ngay_sinh]" class="form-control form-control-sm"></td>
                                        <td><input type="text" name="passengers[0][ghi_chu]" class="form-control form-control-sm"></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(0)"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="index.php?act=bookings" class="btn btn-secondary px-4">Hủy</a>
                            <button type="submit" class="btn btn-primary px-4 fw-bold"><i class="fas fa-save me-1"></i> Lưu Booking</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

<div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addCustomerLabel"><i class="fas fa-user-plus me-2"></i>Thêm Khách Hàng Nhanh</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="quickCustomerForm">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Họ và tên <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="quick_ho_ten" required placeholder="VD: Nguyễn Văn A">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="quick_so_dien_thoai" required placeholder="09...">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Giới tính</label>
                            <select class="form-select" id="quick_gioi_tinh">
                                <option value="NAM">Nam</option>
                                <option value="NU">Nữ</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="quick_email" placeholder="email@example.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Địa chỉ</label>
                        <input type="text" class="form-control" id="quick_dia_chi" placeholder="Địa chỉ liên hệ">
                    </div>
                    <div id="quick_alert" class="alert alert-danger d-none small p-2"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" onclick="saveQuickCustomer()">Lưu ngay</button>
            </div>
        </div>
    </div>
</div>

<script>
    const formatCurrency = (amount) => new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);

    // 1. Xử lý thêm khách hàng nhanh bằng AJAX
    function saveQuickCustomer() {
        const data = {
            ho_ten: document.getElementById('quick_ho_ten').value,
            so_dien_thoai: document.getElementById('quick_so_dien_thoai').value,
            email: document.getElementById('quick_email').value,
            dia_chi: document.getElementById('quick_dia_chi').value,
            gioi_tinh: document.getElementById('quick_gioi_tinh').value
        };

        if (!data.ho_ten || !data.so_dien_thoai) {
            document.getElementById('quick_alert').innerText = "Vui lòng nhập Họ tên và SĐT!";
            document.getElementById('quick_alert').classList.remove('d-none');
            return;
        }

        // Gửi dữ liệu về Controller
        fetch('index.php?act=khachhang-store-api', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.status === 'success') {
                // Tạo option mới và chọn nó
                const select = document.getElementById('khach_hang_id');
                const newOption = document.createElement('option');
                newOption.value = result.data.id;
                newOption.text = `${result.data.ho_ten} (${result.data.so_dien_thoai})`;
                
                // Gán data attribute để điền snapshot
                newOption.setAttribute('data-ten', result.data.ho_ten);
                newOption.setAttribute('data-sdt', result.data.so_dien_thoai);
                newOption.setAttribute('data-email', result.data.email);
                newOption.setAttribute('data-diachi', result.data.dia_chi);

                select.add(newOption);
                select.value = result.data.id;

                // Kích hoạt event change để điền snapshot
                select.dispatchEvent(new Event('change'));

                // Đóng modal
                const modalEl = document.getElementById('addCustomerModal');
                const modalInstance = bootstrap.Modal.getInstance(modalEl);
                modalInstance.hide();
                
                // Reset form
                document.getElementById('quickCustomerForm').reset();
                alert("Thêm khách hàng thành công!");
            } else {
                document.getElementById('quick_alert').innerText = result.message;
                document.getElementById('quick_alert').classList.remove('d-none');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("Có lỗi xảy ra.");
        });
    }

    // 2. Tự động điền thông tin snapshot
    document.getElementById('khach_hang_id').addEventListener('change', function() {
        let opt = this.options[this.selectedIndex];
        if (opt.value) {
            document.getElementById('snapshot_kh_ho_ten').value = opt.getAttribute('data-ten') || '';
            document.getElementById('snapshot_kh_so_dien_thoai').value = opt.getAttribute('data-sdt') || '';
            document.getElementById('snapshot_kh_email').value = opt.getAttribute('data-email') || '';
            document.getElementById('snapshot_kh_dia_chi').value = opt.getAttribute('data-diachi') || '';
        }
    });

    // 3. Tính tiền
    function updateTotal() {
        let tourSelect = document.getElementById('tour_id');
        if(tourSelect.selectedIndex <= 0) return;
        
        let tourOpt = tourSelect.options[tourSelect.selectedIndex];
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
        let rawDeposit = document.getElementById('input_da_thanh_toan').value;
        let deposit = parseFloat(rawDeposit.replace(/[^0-9]/g, '')) || 0;

        let remaining = total - deposit;
        let el = document.getElementById('display_con_lai');
        
        el.innerText = formatCurrency(remaining);
        el.className = remaining <= 0 ? "fs-4 fw-bold text-success" : "fs-4 fw-bold text-danger";
    }

    // 4. Thêm dòng hành khách (JS thuần)
    let rowIndex = 1;
    function addPassengerRow() {
        const table = document.getElementById('passengerList');
        const rowId = `row_${rowIndex}`;
        const html = `
            <tr id="${rowId}">
                <td class="text-center fw-bold">${rowIndex + 1}</td>
                <td><input type="text" name="passengers[${rowIndex}][ho_ten]" class="form-control form-control-sm" placeholder="Nhập họ tên"></td>
                <td>
                    <select name="passengers[${rowIndex}][gioi_tinh]" class="form-select form-select-sm">
                        <option value="NAM">Nam</option>
                        <option value="NU">Nữ</option>
                    </select>
                </td>
                <td><input type="date" name="passengers[${rowIndex}][ngay_sinh]" class="form-control form-control-sm"></td>
                <td><input type="text" name="passengers[${rowIndex}][ghi_chu]" class="form-control form-control-sm"></td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeRow('${rowId}')"><i class="fas fa-trash"></i></button>
                </td>
            </tr>
        `;
        table.insertAdjacentHTML('beforeend', html);
        rowIndex++;
    }

    function removeRow(rowId) {
        const row = document.getElementById(rowId);
        if (row) row.remove();
    }
</script>

<?php require_once ROOT . '/views/footer.php'; ?>