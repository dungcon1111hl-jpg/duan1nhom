<?php require_once 'views/header.php'; ?>

<main>
    <div class="container-fluid px-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <h1 class="fw-bold text-dark">Dashboard Tổng Quan</h1>
            <div class="text-muted small">
                <i class="fas fa-clock me-1"></i> Cập nhật: <?= date('H:i d/m/Y') ?>
            </div>
        </div>

        <!-- Metric Cards Row -->
        <div class="row g-4">
            <!-- Doanh Thu Card -->
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white h-100 shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="small text-white-50 fw-bold text-uppercase">Tổng Doanh Thu</div>
                                <div class="fs-4 fw-bold"><?= number_format($metrics['doanh_thu']) ?> ₫</div>
                            </div>
                            <i class="fas fa-sack-dollar fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chi Phí Card -->
            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-white h-100 shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="small text-white-50 fw-bold text-uppercase">Tổng Chi Phí</div>
                                <div class="fs-4 fw-bold"><?= number_format($metrics['chi_phi']) ?> ₫</div>
                            </div>
                            <i class="fas fa-file-invoice-dollar fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lợi Nhuận Card -->
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white h-100 shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="small text-white-50 fw-bold text-uppercase">Lợi Nhuận Ròng</div>
                                <div class="fs-4 fw-bold"><?= number_format($metrics['loi_nhuan']) ?> ₫</div>
                            </div>
                            <i class="fas fa-chart-line fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hoạt Động Card -->
            <div class="col-xl-3 col-md-6">
                <div class="card bg-danger text-white h-100 shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="small text-white-50 fw-bold text-uppercase">Hoạt Động</div>
                                <div class="fs-5 fw-bold"><?= $metrics['so_khach'] ?> Khách / <?= $metrics['so_tour'] ?> Tour</div>
                            </div>
                            <i class="fas fa-users fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Lists Row -->
        <div class="row mt-4">
            <!-- Chart Column -->
            <div class="col-xl-5">
                <div class="card mb-4 shadow-sm border-0 h-100">
                    <div class="card-header bg-white fw-bold">
                        <i class="fas fa-chart-pie me-1 text-primary"></i> Tỷ lệ trạng thái Booking
                    </div>
                    <div class="card-body d-flex justify-content-center align-items-center">
                        <canvas id="bookingStatusChart" width="100%" height="50"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Bookings Column -->
            <div class="col-xl-7">
                <div class="card mb-4 shadow-sm border-0 h-100">
                    <div class="card-header bg-white fw-bold">
                        <i class="fas fa-list me-1 text-success"></i> Booking Mới Nhất
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Mã</th>
                                        <th>Khách hàng</th>
                                        <th>Tour</th>
                                        <th>Trạng thái</th>
                                        <th class="text-end">Tổng tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($recentBookings)): foreach($recentBookings as $b): ?>
                                    <tr>
                                        <td>
                                            <a href="index.php?act=booking-detail&id=<?= $b['id'] ?>" class="text-decoration-none fw-bold">
                                                #<?= $b['id'] ?>
                                            </a>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars($b['ten_khach'] ?? 'Khách lẻ') ?>
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 200px;" title="<?= htmlspecialchars($b['ten_tour']) ?>">
                                                <?= htmlspecialchars($b['ten_tour']) ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?php 
                                                $badgeColor = match($b['trang_thai']) {
                                                    'CHO_XU_LY' => 'bg-warning text-dark',
                                                    'DA_XAC_NHAN' => 'bg-info text-dark',
                                                    'DA_THANH_TOAN' => 'bg-primary',
                                                    'HOAN_THANH' => 'bg-success',
                                                    'HUY' => 'bg-danger',
                                                    default => 'bg-secondary'
                                                };
                                            ?>
                                            <span class="badge <?= $badgeColor ?>"><?= $b['trang_thai'] ?></span>
                                        </td>
                                        <td class="text-end fw-bold text-primary">
                                            <?= number_format($b['tong_tien']) ?> ₫
                                        </td>
                                    </tr>
                                    <?php endforeach; else: ?>
                                        <tr><td colspan="5" class="text-center py-3 text-muted">Chưa có booking nào.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Chart Script -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script>
    // Cấu hình và vẽ biểu đồ tròn
    var ctx = document.getElementById("bookingStatusChart");
    if (ctx) {
        var myPieChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: <?= json_encode($pieLabels ?? []) ?>, // Dữ liệu từ PHP
                datasets: [{
                    data: <?= json_encode($pieData ?? []) ?>, // Dữ liệu từ PHP
                    backgroundColor: <?= json_encode($pieColors ?? []) ?>, // Màu sắc từ PHP
                }],
            },
            options: {
                legend: {
                    position: 'bottom'
                },
                cutoutPercentage: 60,
            }
        });
    }
</script>

<?php require_once 'views/footer.php'; ?>