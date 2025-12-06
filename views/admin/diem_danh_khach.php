    <?php require_once PATH_ROOT . '/views/header.php'; ?>

    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
            <h1>Điểm danh khách hàng</h1>
            <a href="<?= BASE_URL . "?act=diem-danh-list" ?>" class="btn btn-secondary">
                <i class="fas fa-history"></i> Xem lịch sử / Báo cáo
            </a>
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white p-3 text-center shadow-sm">
                    <h6>Tổng khách</h6>
                    <h2 id="stat-total" class="fw-bold">0</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white p-3 text-center shadow-sm">
                    <h6>Có mặt</h6>
                    <h2 id="stat-present" class="fw-bold">0</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white p-3 text-center shadow-sm">
                    <h6>Vắng mặt</h6>
                    <h2 id="stat-absent" class="fw-bold">0</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark p-3 text-center shadow-sm">
                    <h6>Đến trễ / Chưa điểm</h6>
                    <h2 id="stat-late" class="fw-bold">0</h2>
                </div>
            </div>
        </div>

        <?php if (!empty($_SESSION['flash_success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL . "?act=diem-danh-store" ?>" method="POST" enctype="multipart/form-data">

            <div class="card shadow">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-list"></i> Danh sách khách cần điểm danh</span>
                    <button type="submit" class="btn btn-primary btn-sm fw-bold">
                        <i class="fas fa-save"></i> LƯU TẤT CẢ
                    </button>
                </div>
                
                <div class="card-body p-0">
                    <table class="table table-striped table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" width="50">#</th>
                                <th>Thông tin khách</th>
                                <th>Tour / Lịch trình</th>
                                <th width="15%">Ảnh minh chứng</th> 
                                <th width="30%">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $totalGuests = count($bookings);
                            foreach ($bookings as $index => $b): 
                                $bId = $b['id'];
                                $st = $b['status_checkin'] ?? '';
                                // SỬA: Ưu tiên lấy khach_ho_ten (tên đầy đủ từ bảng khach_hang)
                                $displayName = $b['khach_ho_ten'] 
                                            ?? ($b['snapshot_kh_ho_ten'] 
                                            ?? 'Khách (ID: ' . $b['khach_hang_id'] . ')');
                            ?>
                                <tr>
                                    <td class="text-center fw-bold"><?= $index + 1 ?></td>
                                    <td>
                                        <div class="fw-bold text-primary"><?= htmlspecialchars($displayName) ?></div> 
                                        <small class="text-muted"><i class="fas fa-id-badge"></i> ID: <?= $b['khach_hang_id'] ?></small>
                                    </td>
                                    <td>
                                        <div class="fw-bold"><?= htmlspecialchars($b['ten_tour'] ?? 'Tour tham quan') ?></div>
                                        <small class="text-muted"><i class="far fa-calendar-alt"></i> Lịch ID: <?= $b['lich_id'] ?></small>
                                    </td>
                                    
                                    <td>
                                        <input type="file" 
                                            name="proof_photo[<?= $bId ?>]" 
                                            class="form-control form-control-sm" 
                                            accept="image/*">
                                    </td>
                                    
                                    <td>
                                        <div class="btn-group w-100" role="group">
                                            
                                            <input type="radio" class="btn-check checkin-radio" 
                                                name="attendance[<?= $bId ?>]" id="present_<?= $bId ?>" 
                                                value="CO_MAT" <?= $st == 'CO_MAT' ? 'checked' : '' ?>>
                                            <label class="btn btn-outline-success" for="present_<?= $bId ?>">Có mặt</label>

                                            <input type="radio" class="btn-check checkin-radio" 
                                                name="attendance[<?= $bId ?>]" id="absent_<?= $bId ?>" 
                                                value="VANG_MAT" <?= $st == 'VANG_MAT' ? 'checked' : '' ?>>
                                            <label class="btn btn-outline-danger" for="absent_<?= $bId ?>">Vắng</label>

                                            <input type="radio" class="btn-check checkin-radio" 
                                                name="attendance[<?= $bId ?>]" id="late_<?= $bId ?>" 
                                                value="DEN_TRE" <?= $st == 'DEN_TRE' ? 'checked' : '' ?>>
                                            <label class="btn btn-outline-warning text-dark" for="late_<?= $bId ?>">Trễ</label>

                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-light text-end">
                    <button type="submit" class="btn btn-primary btn-lg px-4"><i class="fas fa-save"></i> Lưu Thay Đổi</button>
                </div>
            </div>
        </form>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const totalGuests = <?= $totalGuests ?>; 
        document.getElementById('stat-total').innerText = totalGuests;

        // --- LOGIC THỐNG KÊ (Giữ nguyên) ---
        function updateStats() {
            let present = 0, absent = 0, late = 0;

            document.querySelectorAll('.checkin-radio:checked').forEach(radio => {
                if (radio.value === 'CO_MAT') present++;
                else if (radio.value === 'VANG_MAT') absent++;
                else if (radio.value === 'DEN_TRE') late++;
            });

            const checkedCount = present + absent + late;
            let remaining = totalGuests - checkedCount;
            
            document.getElementById('stat-present').innerText = present; 
            document.getElementById('stat-absent').innerText = absent;
            document.getElementById('stat-late').innerText = `${late} (Chưa: ${remaining > 0 ? remaining : 0})`;
            
            const presentCard = document.querySelector('#stat-present').closest('.card');
            const absentCard = document.querySelector('#stat-absent').closest('.card');
            
            if (remaining > 0) {
                presentCard.classList.remove('bg-success');
                presentCard.classList.add('bg-secondary');
            } else if (present === totalGuests) {
                presentCard.classList.remove('bg-secondary');
                presentCard.classList.add('bg-success');
            }
            
            if (absent > 0) {
                absentCard.classList.remove('bg-danger');
                absentCard.classList.add('bg-danger'); 
            }
        }

        document.querySelectorAll('.checkin-radio').forEach(radio => {
            radio.addEventListener('change', updateStats);
        });

        updateStats();
    });
    </script>

    <?php require_once PATH_ROOT . '/views/footer.php'; ?>