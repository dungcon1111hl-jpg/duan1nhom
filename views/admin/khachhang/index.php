<?php require_once ROOT . '/views/header.php'; ?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                <div>
                    <h1 class="m-0 text-primary font-weight-bold">Quản Lý Khách Hàng</h1>
                    <ol class="breadcrumb mb-0 mt-1">
                        <!-- SỬA: act=admin -->
                        <li class="breadcrumb-item"><a href="index.php?act=admin" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active">Khách hàng</li>
                    </ol>
                </div>
                <!-- Link thêm mới đã đúng với index.php -->
                <a href="index.php?act=khachhang-create" class="btn btn-primary shadow-sm">
                    <i class="fas fa-user-plus me-2"></i> Thêm khách hàng
                </a>
            </div>
            
            <!-- Hiển thị thông báo nếu có -->
            <?php if(isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> <?= $_SESSION['success'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i> <?= $_SESSION['error'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <div class="card shadow-lg border-0 rounded-lg mb-4">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="fas fa-users me-2"></i>Danh sách thông tin khách hàng</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatablesSimple" class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 50px;">ID</th>
                                    <th>Họ tên</th>
                                    <th>Liên hệ (SĐT/Email)</th>
                                    <th>Địa chỉ</th> 
                                    <th class="text-center">Ngày sinh</th>
                                    <th class="text-center">Giới tính</th>
                                    <th class="text-center" style="width: 100px;">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($customers) && count($customers) > 0): ?>
                                    <?php foreach ($customers as $kh): ?>
                                        <tr>
                                            <td class="text-center fw-bold text-secondary"><?= $kh['id'] ?></td>
                                            
                                            <td>
                                                <span class="fw-bold text-dark"><?= htmlspecialchars($kh['ho_ten']) ?></span>
                                            </td>
                                            
                                            <td>
                                                <div class="small text-muted"><i class="fas fa-envelope me-1"></i> <?= htmlspecialchars($kh['email'] ?? '---') ?></div>
                                                <div class="small text-success fw-bold"><i class="fas fa-phone me-1"></i> <?= htmlspecialchars($kh['so_dien_thoai']) ?></div>
                                            </td>
                                            
                                            <td>
                                                <div class="text-truncate" style="max-width: 200px;" title="<?= htmlspecialchars($kh['dia_chi'] ?? '') ?>">
                                                    <?= htmlspecialchars($kh['dia_chi'] ?? 'Chưa cập nhật') ?>
                                                </div>
                                            </td>
                                            
                                            <td class="text-center">
                                                <?= !empty($kh['ngay_sinh']) ? date('d/m/Y', strtotime($kh['ngay_sinh'])) : '<span class="text-muted small">---</span>' ?>
                                            </td>
                                            
                                            <td class="text-center">
                                                <?php 
    $gender = $kh['gioi_tinh'];  // Lấy giá trị giới tính từ cơ sở dữ liệu

if ($gender === null || $gender === '') {
    // Trường hợp không có thông tin giới tính (NULL hoặc rỗng)
    echo '<span class="badge bg-secondary">---</span>';
} elseif ($gender === 'NAM') {
    // Trường hợp giới tính Nam (nếu giá trị là 'NAM')
    echo '<span class="badge bg-info text-dark"><i class="fas fa-mars me-1"></i> Nam</span>';
} elseif ($gender === 'NU') {
    // Trường hợp giới tính Nữ (nếu giá trị là 'NU')
    echo '<span class="badge bg-danger bg-opacity-75"><i class="fas fa-venus me-1"></i> Nữ</span>';
} else {
    // Trường hợp giới tính không xác định (có thể là 'KHAC' hoặc giá trị khác)
    echo '<span class="badge bg-warning text-dark">Giới tính không xác định</span>';
}

?>
                                            </td>
                                            
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <!-- SỬA: act=khachhang-edit (Trang form sửa) thay vì act=khachhang-update (Hành động xử lý) -->
                                                    <a href="index.php?act=khachhang-edit&id=<?= $kh['id'] ?>" 
                                                       class="btn btn-sm btn-outline-warning" 
                                                       data-bs-toggle="tooltip" title="Sửa thông tin">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    
                                                    <!-- Link xóa -->
                                                    <a href="index.php?act=khachhang-delete&id=<?= $kh['id'] ?>" 
                                                       class="btn btn-sm btn-outline-danger" 
                                                       onclick="return confirm('Bạn có chắc chắn muốn xóa khách hàng <?= htmlspecialchars($kh['ho_ten']) ?> không?');" 
                                                       data-bs-toggle="tooltip" title="Xóa khách hàng">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">
                                            <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                            Chưa có dữ liệu khách hàng.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<style>
    .card {
        border-radius: 12px;
        overflow: hidden;
    }
    .table thead th {
        background-color: #f8f9fa;
        text-transform: uppercase;
        font-size: 0.85rem;
        color: #6c757d;
        vertical-align: middle;
        border-bottom: 2px solid #dee2e6;
    }
    .btn-group .btn {
        border-radius: 4px !important;
        margin: 0 2px;
    }
    .badge {
        font-weight: 500;
        padding: 6px 10px;
        border-radius: 6px;
    }
</style>

<?php require_once ROOT . '/views/footer.php'; ?>