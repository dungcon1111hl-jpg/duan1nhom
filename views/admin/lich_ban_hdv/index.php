<?php require_once PATH_ROOT . '/views/header.php'; ?>

<main>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <h2 class="fw-bold text-danger"><i class="fas fa-calendar-times me-2"></i>Lịch Bận Hướng Dẫn Viên</h2>
            <a href="index.php?act=lich-ban-create" class="btn btn-danger shadow-sm">
                <i class="fas fa-plus me-1"></i> Đăng ký lịch bận
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Hướng dẫn viên</th>
                                <th>Thời gian nghỉ</th>
                                <th>Lý do / Loại</th>
                                <th>Trạng thái</th>
                                <th class="text-end">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($list_ban)): foreach ($list_ban as $lb): ?>
                            <tr>
                                <td>
                                    <div class="fw-bold"><?= htmlspecialchars($lb['ho_ten']) ?></div>
                                    <small class="text-muted"><?= $lb['so_dien_thoai'] ?></small>
                                </td>
                                <td>
                                    <div class="small">
                                        Từ: <strong><?= date('d/m/Y H:i', strtotime($lb['ngay_bat_dau'])) ?></strong><br>
                                        Đến: <strong><?= date('d/m/Y H:i', strtotime($lb['ngay_ket_thuc'])) ?></strong>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($lb['ly_do']) ?></div>
                                    <span class="badge bg-<?= $lb['loai_lich']=='CO_DINH'?'secondary':'info' ?>">
                                        <?= $lb['loai_lich']=='CO_DINH' ? 'Cố định' : 'Tạm thời' ?>
                                    </span>
                                </td>
                                <td>
                                    <?php 
                                        $stt = match($lb['trang_thai']) {
                                            'DA_XAC_NHAN' => ['success', 'Đã duyệt'],
                                            'TU_CHOI' => ['danger', 'Từ chối'],
                                            default => ['warning text-dark', 'Chờ duyệt']
                                        };
                                    ?>
                                    <span class="badge bg-<?= $stt[0] ?>"><?= $stt[1] ?></span>
                                </td>
                                <td class="text-end">
                                    <a href="index.php?act=lich-ban-edit&id=<?= $lb['id'] ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                    <a href="index.php?act=lich-ban-delete&id=<?= $lb['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Xóa lịch bận này?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                                <tr><td colspan="5" class="text-center py-4 text-muted">Không có dữ liệu lịch bận.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require_once PATH_ROOT . '/views/footer.php'; ?>