<?php require_once PATH_ROOT . '/views/header.php'; ?>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
        <div>
            <h1><i class="fas fa-book-open"></i> Nhật Ký Tour</h1>
            <p class="text-muted mb-0">
                Tour: <span class="fw-bold text-primary"><?= htmlspecialchars($tour['ten_tour'] ?? 'Chưa xác định') ?></span>
                (ID: <?= $tour_id ?>)
            </p>
        </div>
        <a href="<?= BASE_URL . "?act=tours" ?>" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>
    </div>

    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Tổng ghi chép</h6>
                        <h2 class="fw-bold mb-0"><?= $stats['total'] ?? 0 ?></h2>
                    </div>
                    <i class="fas fa-clipboard-list fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Sự cố phát sinh</h6>
                        <h2 class="fw-bold mb-0"><?= $stats['su_co'] ?? 0 ?></h2>
                    </div>
                    <i class="fas fa-exclamation-triangle fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Phản hồi khách</h6>
                        <h2 class="fw-bold mb-0"><?= $stats['phan_hoi'] ?? 0 ?></h2>
                    </div>
                    <i class="fas fa-comments fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-dark mb-4 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Đánh giá TB</h6>
                        <h2 class="fw-bold mb-0">
                            <?= $stats['danh_gia_avg'] ?? 0 ?><small class="fs-6">/5</small>
                        </h2>
                    </div>
                    <i class="fas fa-star fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($_SESSION['flash_success'])): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> <?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        
        <div class="col-lg-4 mb-4">
            <div class="card shadow border-0">
                <div class="card-header bg-dark text-white fw-bold">
                    <i class="fas fa-pen-square me-2"></i> Ghi nhận diễn biến mới
                </div>
                <div class="card-body bg-light">
                    <form action="<?= BASE_URL . "?act=nhat-ky-store" ?>" method="POST">
                        <input type="hidden" name="lich_id" value="<?= $tour_id ?>">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary">Loại ghi chép</label>
                            <select name="loai_ghi_chep" class="form-select shadow-sm" id="loai_log" onchange="toggleFields()">
                                <option value="THONG_TIN">ℹ️ Thông tin chung (Thời tiết, Sức khỏe...)</option>
                                <option value="SU_CO">⚠️ Sự cố / Vấn đề phát sinh</option>
                                <option value="PHAN_HOI">💬 Phản hồi từ khách hàng</option>
                                <option value="DANH_GIA">⭐ Đánh giá chất lượng HDV</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary">Tiêu đề ngắn gọn</label>
                            <input type="text" name="tieu_de" class="form-control shadow-sm" required 
                                   placeholder="VD: Xe hỏng điều hòa, Khách khen bữa trưa...">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary">Nội dung chi tiết</label>
                            <textarea name="noi_dung" class="form-control shadow-sm" rows="5" required 
                                      placeholder="Mô tả chi tiết sự việc..."></textarea>
                        </div>

                        <div class="mb-3 p-3 bg-white border border-danger rounded shadow-sm" id="field_xu_ly" style="display:none;">
                            <label class="form-label fw-bold text-danger"><i class="fas fa-tools"></i> Cách xử lý / Rút kinh nghiệm</label>
                            <textarea name="cach_xu_ly" class="form-control" rows="3" 
                                      placeholder="Đã giải quyết vấn đề như thế nào?"></textarea>
                        </div>

                        <div class="mb-3 p-3 bg-white border border-warning rounded shadow-sm" id="field_danh_gia" style="display:none;">
                            <label class="form-label fw-bold text-warning"><i class="fas fa-star"></i> Chấm điểm (1-5 sao)</label>
                            <div class="d-flex justify-content-between px-2">
                                <?php for($i=1; $i<=5; $i++): ?>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="danh_gia_sao" id="star<?= $i ?>" value="<?= $i ?>" <?= $i==5 ? 'checked' : '' ?>>
                                        <label class="form-check-label fw-bold" for="star<?= $i ?>"><?= $i ?></label>
                                    </div>
                                <?php endfor; ?>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success fw-bold py-2 shadow-sm">
                                <i class="fas fa-save me-2"></i> LƯU NHẬT KÝ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-history me-2"></i> Lịch sử ghi nhận</h5>
                    <span class="badge bg-secondary"><?= count($logs) ?> bản ghi</span>
                </div>
                
                <div class="card-body p-0">
                    <?php if (empty($logs)): ?>
                        <div class="text-center py-5">
                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486747.png" width="80" class="mb-3 opacity-50" alt="Empty">
                            <p class="text-muted fw-bold">Chưa có nhật ký nào được ghi lại.</p>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($logs as $log): ?>
                                <div class="list-group-item p-3 list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between align-items-start mb-2">
                                        <div>
                                            <?php 
                                                $badgeClass = 'bg-secondary';
                                                $iconClass = 'fa-info-circle';
                                                if ($log['loai_ghi_chep'] == 'SU_CO') { $badgeClass = 'bg-danger'; $iconClass = 'fa-exclamation-circle'; }
                                                if ($log['loai_ghi_chep'] == 'PHAN_HOI') { $badgeClass = 'bg-info text-dark'; $iconClass = 'fa-comment-dots'; }
                                                if ($log['loai_ghi_chep'] == 'DANH_GIA') { $badgeClass = 'bg-warning text-dark'; $iconClass = 'fa-star'; }
                                            ?>
                                            <span class="badge <?= $badgeClass ?> me-1">
                                                <i class="fas <?= $iconClass ?>"></i> 
                                                <?php 
                                                    $labels = ['THONG_TIN' => 'Thông tin', 'SU_CO' => 'Sự cố', 'PHAN_HOI' => 'Phản hồi', 'DANH_GIA' => 'Đánh giá'];
                                                    echo $labels[$log['loai_ghi_chep']] ?? $log['loai_ghi_chep'];
                                                ?>
                                            </span>
                                            <span class="fw-bold fs-5 align-middle"><?= htmlspecialchars($log['tieu_de']) ?></span>
                                        </div>
                                        <small class="text-muted fw-bold">
                                            <i class="far fa-clock"></i> <?= date('H:i d/m/Y', strtotime($log['ngay_ghi'])) ?>
                                        </small>
                                    </div>

                                    <p class="mb-2 text-dark" style="white-space: pre-line;"><?= htmlspecialchars($log['noi_dung']) ?></p>

                                    <?php if ($log['loai_ghi_chep'] == 'SU_CO' && !empty($log['cach_xu_ly'])): ?>
                                        <div class="alert alert-light border-start border-4 border-danger py-2 ps-3 mb-2 small">
                                            <strong class="text-danger"><i class="fas fa-tools"></i> Đã xử lý:</strong> 
                                            <?= nl2br(htmlspecialchars($log['cach_xu_ly'])) ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($log['loai_ghi_chep'] == 'DANH_GIA'): ?>
                                        <div class="mb-2">
                                            <?php for($k=1; $k<=5; $k++): ?>
                                                <i class="fas fa-star <?= $k <= $log['danh_gia_sao'] ? 'text-warning' : 'text-secondary opacity-25' ?>"></i>
                                            <?php endfor; ?>
                                            <span class="fw-bold ms-2">(<?= $log['danh_gia_sao'] ?>/5)</span>
                                        </div>
                                    <?php endif; ?>

                                    <div class="text-end mt-2">
                                        <a href="<?= BASE_URL . "?act=nhat-ky-delete&id=" . $log['id'] . "&tour_id=" . $tour_id ?>" 
                                           class="btn btn-sm btn-outline-danger border-0 rounded-pill"
                                           onclick="return confirm('Bạn có chắc chắn muốn xóa bản ghi này không?');">
                                            <i class="fas fa-trash-alt"></i> Xóa bản ghi
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-light py-2"></div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleFields() {
        const type = document.getElementById('loai_log').value;
        const divXuLy = document.getElementById('field_xu_ly');
        const divDanhGia = document.getElementById('field_danh_gia');

        // Reset display
        divXuLy.style.display = 'none';
        divDanhGia.style.display = 'none';

        // Logic hiển thị
        if (type === 'SU_CO') {
            divXuLy.style.display = 'block';
            // Animation nhẹ (nếu muốn)
            divXuLy.style.opacity = 0;
            setTimeout(() => divXuLy.style.opacity = 1, 50);
        } else if (type === 'DANH_GIA') {
            divDanhGia.style.display = 'block';
            divDanhGia.style.opacity = 0;
            setTimeout(() => divDanhGia.style.opacity = 1, 50);
        }
    }
    
    // Chạy 1 lần khi load trang để đảm bảo đúng trạng thái (nếu form lưu lại giá trị cũ)
    document.addEventListener("DOMContentLoaded", function() {
        toggleFields();
    });
</script>

<?php require_once PATH_ROOT . '/views/footer.php'; ?>