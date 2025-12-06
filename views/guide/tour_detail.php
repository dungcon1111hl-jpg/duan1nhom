<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Điều hành Tour</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Mobile Optimization */
        .sticky-nav { position: sticky; top: 0; z-index: 1000; background: white; padding-top: 10px; border-bottom: 1px solid #eee; }
        .nav-pills .nav-link { border-radius: 20px; font-weight: 500; color: #6c757d; }
        .nav-pills .nav-link.active { background-color: #198754; color: white; }
        .passenger-card { border-left: 4px solid #dee2e6; transition: 0.3s; }
        .passenger-card.checked { border-left-color: #198754; background-color: #f1f8f5; }
        .btn-checkin { width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
        .special-note { background: #fff3cd; color: #856404; padding: 5px 10px; border-radius: 6px; font-size: 0.85rem; margin-top: 8px; display: inline-block; }
    </style>
</head>
<body class="bg-light" style="padding-bottom: 80px;">

    <div class="bg-success text-white p-3 shadow-sm">
        <div class="d-flex align-items-center">
            <a href="index.php?act=guide-dashboard" class="text-white me-3"><i class="fas fa-arrow-left fa-lg"></i></a>
            <div class="text-truncate">
                <h6 class="mb-0 fw-bold"><?= htmlspecialchars($tour['ten_tour']) ?></h6>
                <small class="opacity-75"><i class="fas fa-map-marker-alt"></i> <?= $tour['diem_tap_trung'] ?></small>
            </div>
        </div>
    </div>

    <div class="container px-0">
        <div class="sticky-nav px-3 pb-2 shadow-sm">
            <ul class="nav nav-pills nav-fill" id="pills-tab" role="tablist">
                <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill" data-bs-target="#tab-guests">Danh sách khách</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-schedule">Lịch trình</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-diary">Nhật ký</button></li>
            </ul>
        </div>

        <div class="tab-content px-3 pt-3">
            
            <div class="tab-pane fade show active" id="tab-guests">
                <div class="mb-3">
                    <input type="text" id="searchGuest" class="form-control rounded-pill" placeholder="🔍 Tìm tên hoặc SĐT khách...">
                </div>

                <div id="guestList">
                    <?php foreach($passengers as $p): ?>
                    <div class="card mb-2 border-0 shadow-sm passenger-card <?= $p['checked_in'] ? 'checked' : '' ?> guest-item">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div class="flex-grow-1" data-bs-toggle="collapse" href="#note-<?= $p['id'] ?>">
                                <h6 class="fw-bold mb-0 guest-name"><?= htmlspecialchars($p['ho_ten']) ?></h6>
                                <small class="text-muted"><i class="fas fa-phone-alt"></i> <?= $p['so_dien_thoai'] ?></small>
                                <?php if(!empty($p['yeu_cau_dac_biet'])): ?>
                                    <div class="special-note"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($p['yeu_cau_dac_biet']) ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <button class="btn btn-checkin <?= $p['checked_in'] ? 'btn-success' : 'btn-outline-secondary' ?>" 
                                    onclick="gpsCheckIn(<?= $p['id'] ?>, <?= $tour['id'] ?>, this)">
                                <i class="fas <?= $p['checked_in'] ? 'fa-check' : 'fa-qrcode' ?>"></i>
                            </button>
                        </div>
                        
                        <div class="collapse px-3 pb-3" id="note-<?= $p['id'] ?>">
                            <form action="index.php?act=guide-update-note" method="POST" class="mt-2 border-top pt-2">
                                <input type="hidden" name="passenger_id" value="<?= $p['id'] ?>">
                                <input type="hidden" name="lich_id" value="<?= $tour['id'] ?>">
                                <label class="small fw-bold text-secondary">Cập nhật yêu cầu đặc biệt:</label>
                                <div class="input-group">
                                    <input type="text" name="note" class="form-control form-control-sm" value="<?= htmlspecialchars($p['yeu_cau_dac_biet']) ?>">
                                    <button class="btn btn-sm btn-primary">Lưu</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="tab-pane fade" id="tab-schedule">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <?= nl2br($tour['lich_trinh_mau'] ?? 'Đang cập nhật lịch trình...') ?>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="tab-diary">
                <button class="btn btn-danger w-100 mb-3 shadow fw-bold" data-bs-toggle="modal" data-bs-target="#modalDiary">
                    <i class="fas fa-pen-nib me-2"></i> Viết Nhật Ký / Báo Sự Cố
                </button>

                <?php if(empty($diaries)): ?>
                    <p class="text-center text-muted small">Chưa có nhật ký nào.</p>
                <?php else: foreach($diaries as $log): ?>
                    <div class="card mb-3 border-0 shadow-sm">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between">
                                <span class="badge bg-<?= $log['loai_nhat_ky']=='SU_CO'?'danger':'info' ?> mb-2"><?= $log['loai_nhat_ky'] ?></span>
                                <small class="text-muted"><?= date('H:i d/m', strtotime($log['ngay_ghi'])) ?></small>
                            </div>
                            <h6 class="fw-bold"><?= htmlspecialchars($log['tieu_de']) ?></h6>
                            <p class="small text-secondary mb-0"><?= nl2br(htmlspecialchars($log['noi_dung'])) ?></p>
                            <?php if($log['hinh_anh']): ?>
                                <img src="<?= BASE_URL . $log['hinh_anh'] ?>" class="img-fluid rounded mt-2 border">
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; endif; ?>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDiary" tabindex="-1">
        <div class="modal-dialog modal-fullscreen-sm-down">
            <div class="modal-content">
                <form action="index.php?act=guide-store-diary" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="lich_id" value="<?= $tour['id'] ?>">
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title">Ghi Nhật Ký</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Loại tin</label>
                            <select name="loai_nhat_ky" class="form-select">
                                <option value="THONG_THUONG">Thông thường</option>
                                <option value="SU_CO">⚠️ Sự cố khẩn cấp</option>
                                <option value="PHAN_HOI">💬 Phản hồi khách</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="tieu_de" class="form-control" placeholder="Tiêu đề..." required>
                        </div>
                        <div class="mb-3">
                            <textarea name="noi_dung" class="form-control" rows="4" placeholder="Nội dung chi tiết..." required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small">Hình ảnh/Video minh chứng</label>
                            <input type="file" name="hinh_anh" class="form-control" accept="image/*,video/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary w-100 fw-bold">Gửi Báo Cáo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // --- 1. TÌM KIẾM KHÁCH HÀNG ---
        document.getElementById('searchGuest').addEventListener('keyup', function() {
            let val = this.value.toLowerCase();
            document.querySelectorAll('.guest-item').forEach(el => {
                let name = el.querySelector('.guest-name').innerText.toLowerCase();
                el.style.display = name.includes(val) ? 'block' : 'none';
            });
        });

        // --- 2. GPS CHECK-IN LOGIC ---
        function gpsCheckIn(passengerId, tourId, btn) {
            if (!confirm("Xác nhận Check-in cho khách này?")) return;

            // Loading state
            let originalContent = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            btn.disabled = true;

            // Hàm gửi dữ liệu
            const sendData = (lat, long) => {
                fetch('index.php?act=api-checkin', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ passenger_id: passengerId, status: 1, lat: lat, long: long })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        btn.className = 'btn btn-checkin btn-success';
                        btn.innerHTML = '<i class="fas fa-check"></i>';
                        btn.closest('.passenger-card').classList.add('checked');
                    } else {
                        alert('Lỗi: ' + data.msg);
                        btn.innerHTML = originalContent;
                        btn.disabled = false;
                    }
                })
                .catch(err => {
                    console.error(err);
                    btn.innerHTML = originalContent;
                    btn.disabled = false;
                });
            };

            // Lấy tọa độ GPS
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (pos) => sendData(pos.coords.latitude, pos.coords.longitude),
                    (err) => {
                        console.warn("GPS Error:", err.message);
                        sendData(null, null); // Không lấy được GPS vẫn cho check-in
                    }
                );
            } else {
                sendData(null, null);
            }
        }
    </script>
</body>
</html>