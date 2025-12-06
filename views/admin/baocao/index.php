<?php require_once PATH_ROOT . '/views/header.php'; ?>

<main>
    <div class="container-fluid px-4">
        <h2 class="mt-4 fw-bold text-primary"><i class="fas fa-chart-line me-2"></i>Báo Cáo Hiệu Quả Kinh Doanh</h2>
        
        <form method="GET" class="row g-3 bg-white p-3 rounded shadow-sm my-4 border">
            <input type="hidden" name="act" value="baocao-tonghop">
            <div class="col-md-4">
                <label class="fw-bold small">Từ ngày</label>
                <input type="date" name="from" class="form-control" value="<?= $fromDate ?>">
            </div>
            <div class="col-md-4">
                <label class="fw-bold small">Đến ngày</label>
                <input type="date" name="to" class="form-control" value="<?= $toDate ?>">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100 fw-bold"><i class="fas fa-filter me-2"></i>Xem Báo Cáo</button>
            </div>
        </form>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white h-100">
                    <div class="card-body">
                        <div class="text-white-50 small text-uppercase fw-bold">Tổng Doanh Thu</div>
                        <div class="fs-2 fw-bold"><?= number_format($tong_doanh_thu_all) ?> ₫</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-warning text-dark h-100">
                    <div class="card-body">
                        <div class="text-dark-50 small text-uppercase fw-bold">Tổng Chi Phí</div>
                        <div class="fs-2 fw-bold"><?= number_format($tong_chi_phi_all) ?> ₫</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card <?= $tong_loi_nhuan_all >= 0 ? 'bg-success' : 'bg-danger' ?> text-white h-100">
                    <div class="card-body">
                        <div class="text-white-50 small text-uppercase fw-bold">Tổng Lợi Nhuận Thực Tế</div>
                        <div class="fs-2 fw-bold"><?= number_format($tong_loi_nhuan_all) ?> ₫</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">
                <i class="fas fa-chart-bar me-1"></i> Biểu đồ Top Tour (Doanh thu & Lợi nhuận)
            </div>
            <div class="card-body">
                <canvas id="revenueChart" width="100%" height="40"></canvas>
            </div>
        </div>

        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">
                <i class="fas fa-table me-1"></i> Chi tiết từng Tour
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tên Tour / Mã</th>
                                <th>Ngày đi</th>
                                <th class="text-end">Doanh Thu</th>
                                <th class="text-end text-muted">Chi Phí (NCC + Khác)</th>
                                <th class="text-end">Lợi Nhuận</th>
                                <th class="text-center">% Lãi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($reportData)): foreach($reportData as $row): ?>
                            <tr>
                                <td>
                                    <div class="fw-bold text-primary"><?= htmlspecialchars($row['ten_tour']) ?></div>
                                    <small class="text-muted">#<?= $row['ma_tour'] ?> (Lịch ID: <?= $row['lich_id'] ?>)</small>
                                </td>
                                <td><?= date('d/m/Y', strtotime($row['ngay_khoi_hanh'])) ?></td>
                                <td class="text-end fw-bold text-primary">
                                    <?= number_format($row['tong_doanh_thu']) ?>
                                </td>
                                <td class="text-end text-muted">
                                    <?= number_format($row['tong_chi_phi']) ?>
                                    <div class="small fst-italic" style="font-size: 0.75rem;">
                                        (DV: <?= number_format($row['tong_chi_phi_ncc']) ?> + Khác: <?= number_format($row['tong_chi_phi_khac']) ?>)
                                    </div>
                                </td>
                                <td class="text-end fw-bold <?= $row['loi_nhuan'] >= 0 ? 'text-success' : 'text-danger' ?>">
                                    <?= number_format($row['loi_nhuan']) ?>
                                </td>
                                <td class="text-center">
                                    <?php if($row['ty_suat'] > 20): ?>
                                        <span class="badge bg-success"><?= $row['ty_suat'] ?>%</span>
                                    <?php elseif($row['ty_suat'] > 0): ?>
                                        <span class="badge bg-warning text-dark"><?= $row['ty_suat'] ?>%</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger"><?= $row['ty_suat'] ?>%</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                                <tr><td colspan="6" class="text-center py-4">Không có dữ liệu trong khoảng thời gian này.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
<script>
    var ctx = document.getElementById("revenueChart");
    var myLineChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: <?= json_encode($chartLabels) ?>,
        datasets: [{
          label: "Doanh Thu",
          backgroundColor: "rgba(2,117,216,1)",
          borderColor: "rgba(2,117,216,1)",
          data: <?= json_encode($chartRevenue) ?>,
        }, {
          label: "Lợi Nhuận",
          backgroundColor: "rgba(25, 135, 84, 0.8)",
          borderColor: "rgba(25, 135, 84, 1)",
          data: <?= json_encode($chartProfit) ?>,
        }],
      },
      options: {
        scales: {
          xAxes: [{ gridLines: { display: false } }],
          yAxes: [{ gridLines: { display: true }, ticks: { beginAtZero: true } }],
        },
        legend: { display: true }
      }
    });
</script>

<?php require_once PATH_ROOT . '/views/footer.php'; ?>