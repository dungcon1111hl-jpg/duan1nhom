<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết Tour - HDV</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .nav-pills .nav-link.active { background-color: #198754; }
        .nav-pills .nav-link { color: #198754; }
        .sticky-tabs { position: sticky; top: 56px; z-index: 99; background: #f0f2f5; padding-top: 10px; }
        .passenger-item { background: #fff; border-radius: 10px; padding: 15px; margin-bottom: 10px; border-left: 5px solid #ccc; }
        .passenger-item.checked-in { border-left-color: #198754; background: #e8f5e9; }
    </style>
</head>
<body style="background-color: #f0f2f5; padding-bottom: 60px;">

    <nav class="navbar navbar-dark bg-success sticky-top">
        <div class="container-fluid">
            <a href="index.php?act=guide-dashboard" class="text-white text-decoration-none"><i class="fas fa-arrow-left me-2"></i>Trở về</a>
            <span class="navbar-brand mb-0 h1 fs-6 text-truncate" style="max-width: 200px;"><?= $tour['ten_tour'] ?></span>
            <div></div>
        </div>
    </nav>

    <div class="container mt-2">
        <ul class="nav nav-pills nav-fill sticky-tabs shadow-sm bg-white rounded mb-3" id="pills-tab" role="tablist">
            <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill" data-bs-target="#schedule">Lịch trình</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#guests">Khách</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#diary">Nhật ký</button></li>
        </ul>

        <div class="tab-content" id="pills-tabContent">
            
            <div class="tab-pane fade show active" id="schedule">
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <h6 class="fw-bold text-success"><i class="fas fa-info-circle me-1"></i> Thông tin chung</h6>
                        <p class="small mb-1">Mã Tour: <strong><?= $tour['ma_tour'] ?></strong></p>
                        <p class="small mb-1">Điểm đón: <?= $tour['diem_tap_trung'] ?></p>
                        <p class="small mb-0">Giờ đi: <?= date('H:i d/m/Y', strtotime($tour['ngay_khoi_hanh'])) ?></p>
                    </div>
                </div>
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold text-primary mb-3">Lịch trình chi tiết</h6>
                        <div class="small">
                            <?= nl2br($tour['lich_trinh_mau']) ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="guests">
                <div class="d-flex justify-content-between mb-2 px-2">
                    <span class="fw-bold">Tổng: <?= count($passengers) ?> khách</span>
                    <span class="text-success"><i class="fas fa-check-circle"></i> Đã đến: <?= array_sum(array_column($passengers, 'trang_thai_checkin')) ?></span>
                </div>

                <?php foreach($passengers as $p): ?>
                <div class="passenger-item shadow-sm <?= $p['trang_thai_checkin'] ? 'checked-in' : '' ?>" data-bs-toggle="modal" data-bs-target="#modalGuest<?= $p['id'] ?>">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0 fw-bold"><?= htmlspecialchars($p['ho_ten']) ?></h6>
                            <small class="text-muted"><?= $p['loai_khach'] ?> | <?= $p['gioi_tinh'] ?></small>
                            <?php if($p['ghi_chu_dac_biet']): ?>
                                <div class="text-danger small mt-1"><i class="fas fa-exclamation-triangle"></i> <?= $p['ghi_chu_dac_biet'] ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="text-end">
                            <i class="fas fa-chevron-right text-muted"></i>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modalGuest<?= $p['id'] ?>" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form action="index.php?act=guide-update-guest" method="POST">
                                <input type="hidden" name="passenger_id" value="<?= $p['id'] ?>">
                                <input type="hidden" name="lich_id" value="<?= $tour['id'] ?>">
                                <div class="modal-header">
                                    <h5 class="modal-title"><?= htmlspecialchars($p['ho_ten']) ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>SĐT liên hệ:</strong> <a href="tel:<?= $p['sdt_lien_he'] ?>"><?= $p['sdt_lien_he'] ?></a></p>
                                    
                                    <div class="form-check form-switch mb-3 p-3 bg-light rounded">
                                        <input class="form-check-input" type="checkbox" name="checkin" id="check<?= $p['id'] ?>" <?= $p['trang_thai_checkin'] ? 'checked' : '' ?>>
                                        <label class="form-check-label fw-bold" for="check<?= $p['id'] ?>">Đã có mặt (Check-in)</label>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Yêu cầu đặc biệt (Ăn chay, dị ứng...)</label>
                                        <textarea name="note" class="form-control" rows="3"><?= $p['ghi_chu_dac_biet'] ?></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary w-100">Cập nhật</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="tab-pane fade" id="diary">
                <button class="btn btn-danger w-100 mb-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalDiary">
                    <i class="fas fa-pen-nib me-2"></i> Viết Nhật Ký / Báo Sự Cố
                </button>

                <?php if(empty($diaries)): ?>
                    <p class="text-center text-muted">Chưa có nhật ký nào.</p>
                <?php else: foreach($diaries as $d): ?>
                    <div class="card mb-3 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <h6 class="fw-bold mb-1 <?= $d['loai_nhat_ky'] == 'SU_CO' ? 'text-danger' : 'text-primary' ?>">
                                    <?= $d['loai_nhat_ky'] == 'SU_CO' ? '<i class="fas fa-exclamation-circle"></i> SỰ CỐ' : 'NHẬT KÝ' ?>: 
                                    <?= htmlspecialchars($d['tieu_de']) ?>
                                </h6>
                                <small class="text-muted"><?= date('H:i d/m', strtotime($d['ngay_ghi'])) ?></small>
                            </div>
                            <p class="card-text small text-secondary mt-2"><?= nl2br(htmlspecialchars($d['noi_dung'])) ?></p>
                            <?php if($d['hinh_anh']): ?>
                                <img src="<?= BASE_URL . $d['hinh_anh'] ?>" class="img-fluid rounded mt-2">
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; endif; ?>

                <div class="modal fade" id="modalDiary" tabindex="-1">
                    <div class="modal-dialog modal-fullscreen-sm-down">
                        <div class="modal-content">
                            <form action="index.php?act=guide-store-diary" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="lich_id" value="<?= $tour['id'] ?>">
                                <div class="modal-header bg-dark text-white">
                                    <h5 class="modal-title">Ghi Nhật Ký Tour</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Loại tin</label>
                                        <select name="loai_nhat_ky" class="form-select">
                                            <option value="THONG_THUONG">Nhật ký thông thường</option>
                                            <option value="SU_CO">Báo cáo Sự cố / Khẩn cấp</option>
                                            <option value="PHAN_HOI">Phản hồi của khách</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Tiêu đề</label>
                                        <input type="text" name="tieu_de" class="form-control" required placeholder="VD: Khách đến trễ, Xe hỏng...">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nội dung chi tiết</label>
                                        <textarea name="noi_dung" class="form-control" rows="4" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Hình ảnh (nếu có)</label>
                                        <input type="file" name="hinh_anh" class="form-control" accept="image/*">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary w-100">Gửi Báo Cáo</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>