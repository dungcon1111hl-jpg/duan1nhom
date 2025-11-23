<?php require_once PATH_ROOT . '/views/header.php'; ?>

<main>
    <div class="container-fluid px-4">
        <!-- Header & Button -->
        <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
            <h2 class="fw-bold text-primary"><i class="fas fa-route me-2"></i>Quản lý Tour</h2>
            <a href="index.php?act=tour-create" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus me-1"></i> Thêm Tour Mới
            </a>
        </div>

        <!-- Bộ lọc tìm kiếm (Filter) -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body bg-light rounded">
                <form action="index.php" method="GET" class="row g-3 align-items-end">
                    <input type="hidden" name="act" value="tours">
                    
                    <!-- Tìm kiếm từ khóa -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold text-muted small">Từ khóa</label>
                        <input type="text" name="ten_tour" class="form-control" 
                               placeholder="Tên tour, mã tour..." 
                               value="<?= htmlspecialchars($_GET['ten_tour'] ?? '') ?>">
                    </div>

                    <!-- Lọc Loại Tour -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold text-muted small">Loại Tour</label>
                        <select name="loai_tour" class="form-select">
                            <option value="">-- Tất cả --</option>
                            <option value="TRONG_NUOC" <?= ($_GET['loai_tour'] ?? '') == 'TRONG_NUOC' ? 'selected' : '' ?>>Trong nước</option>
                            <option value="QUOC_TE" <?= ($_GET['loai_tour'] ?? '') == 'QUOC_TE' ? 'selected' : '' ?>>Quốc tế</option>
                        </select>
                    </div>

                    <!-- Lọc Trạng thái -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold text-muted small">Trạng thái</label>
                        <select name="trang_thai" class="form-select">
                            <option value="">-- Tất cả --</option>
                            <option value="CON_VE" <?= ($_GET['trang_thai'] ?? '') == 'CON_VE' ? 'selected' : '' ?>>Còn vé</option>
                            <option value="HET_VE" <?= ($_GET['trang_thai'] ?? '') == 'HET_VE' ? 'selected' : '' ?>>Hết vé</option>
                            <option value="DA_KHOI_HANH" <?= ($_GET['trang_thai'] ?? '') == 'DA_KHOI_HANH' ? 'selected' : '' ?>>Đã khởi hành</option>
                            <option value="HUY" <?= ($_GET['trang_thai'] ?? '') == 'HUY' ? 'selected' : '' ?>>Hủy</option>
                            <option value="NGUNG_HOAT_DONG" <?= ($_GET['trang_thai'] ?? '') == 'NGUNG_HOAT_DONG' ? 'selected' : '' ?>>Ngừng hoạt động</option>
                        </select>
                    </div>

                    <!-- Nút Lọc & Reset -->
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-dark w-100 fw-bold"><i class="fas fa-filter me-1"></i> Lọc</button>
                    </div>
                    <div class="col-md-1">
                        <a href="index.php?act=tours" class="btn btn-outline-secondary w-100" title="Làm mới"><i class="fas fa-sync-alt"></i></a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Bảng dữ liệu -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="text-uppercase small fw-bold text-muted">
                                <th class="ps-4">Tour</th>
                                <th>Hành trình</th>
                                <th>Loại & Ngày</th>
                                <th>Giá & Vé</th>
                                <th class="text-center">Trạng thái</th>
                                <th class="text-end pe-4">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($tours)): 
                                $data = is_array($tours) ? $tours : $tours->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($data as $tour): 
                            ?>
                            <tr>
                                <!-- 1. Thông tin Tour (Ảnh + Tên + Mã) -->
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <?php if (!empty($tour['anh_minh_hoa'])): ?>
                                            <img src="<?= BASE_URL ?>uploads/tours/<?= $tour['anh_minh_hoa'] ?>" 
                                                 class="rounded-3 border me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="rounded-3 bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center me-3 text-muted" 
                                                 style="width: 60px; height: 60px;"><i class="fas fa-image"></i></div>
                                        <?php endif; ?>
                                        <div>
                                            <div class="fw-bold text-dark text-truncate" style="max-width: 200px;" title="<?= htmlspecialchars($tour['ten_tour']) ?>">
                                                <?= htmlspecialchars($tour['ten_tour']) ?>
                                            </div>
                                            <small class="text-muted font-monospace">#<?= htmlspecialchars($tour['ma_tour'] ?? 'N/A') ?></small>
                                        </div>
                                    </div>
                                </td>

                                <!-- 2. Hành trình (Đi - Trung gian - Đến) -->
                                <td>
                                    <div class="small mb-1">
                                        <span class="fw-bold"><?= htmlspecialchars($tour['dia_diem_bat_dau']) ?></span>
                                        <i class="fas fa-arrow-right text-muted mx-1" style="font-size: 0.8em;"></i> 
                                        
                                        <?php if(!empty($tour['diem_trung_chuyen'])): ?>
                                            <span class="text-secondary"><?= htmlspecialchars($tour['diem_trung_chuyen']) ?></span>
                                            <i class="fas fa-arrow-right text-muted mx-1" style="font-size: 0.8em;"></i> 
                                        <?php endif; ?>

                                        <span class="fw-bold text-success"><?= htmlspecialchars($tour['dia_diem_ket_thuc']) ?></span>
                                    </div>
                                </td>

                                <!-- 3. Loại Tour & Thời gian -->
                                <td>
                                    <?php
                                        $typeLabel = [
                                            'TRONG_NUOC'   => ['bg-info-subtle text-info', 'Trong nước'],
                                            'QUOC_TE'      => ['bg-warning-subtle text-warning', 'Quốc tế'],
                                        ];
                                        $loai = $tour['loai_tour'] ?? 'TRONG_NUOC';
                                        $type = $typeLabel[$loai] ?? ['bg-light text-muted', 'Khác'];
                                    ?>
                                    <span class="badge <?= $type[0] ?> border border-0 fw-normal mb-1"><?= $type[1] ?></span>
                                    <div class="small text-muted">
                                        <i class="far fa-calendar-alt me-1"></i><?= date('d/m/Y', strtotime($tour['ngay_khoi_hanh'])) ?>
                                    </div>
                                </td>

                                <!-- 4. Giá & Vé -->
                                <td>
                                    <div class="fw-bold text-success">
                                        <?php 
                                            // Nếu giá tour = 0 thì lấy giá người lớn để hiển thị
                                            $price = ($tour['gia_tour'] > 0) ? $tour['gia_tour'] : ($tour['gia_nguoi_lon'] ?? 0);
                                            echo number_format($price) . ' ₫';
                                        ?>
                                    </div>
                                    <div class="small text-muted">
                                        Vé: <strong><?= $tour['so_ve_con_lai'] ?></strong> / <?= $tour['so_luong_ve'] ?>
                                    </div>
                                </td>

                                <!-- 5. Trạng thái -->
                                <td class="text-center">
                                    <?php 
                                        $statusInfo = match($tour['trang_thai']) {
                                            'CON_VE' => ['bg-success-subtle text-success', 'Còn vé'],
                                            'HET_VE' => ['bg-danger-subtle text-danger', 'Hết vé'],
                                            'HUY'    => ['bg-secondary-subtle text-secondary', 'Đã hủy'],
                                            'DA_KHOI_HANH' => ['bg-info-subtle text-info', 'Đã khởi hành'],
                                            'NGUNG_HOAT_DONG' => ['bg-dark text-white', 'Ngừng HĐ'],
                                            default  => ['bg-light text-dark', $tour['trang_thai']]
                                        };
                                    ?>
                                    <span class="badge rounded-pill <?= $statusInfo[0] ?> border-0 px-3 py-2 fw-normal">
                                        <?= $statusInfo[1] ?>
                                    </span>
                                </td>

                                <!-- 6. Hành động (Dropdown Menu) -->
                                <td class="text-end pe-4">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Thao tác
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                                            <li>
                                                <a class="dropdown-item" href="index.php?act=tour-detail&id=<?= $tour['id'] ?>">
                                                    <i class="fas fa-eye text-info me-2"></i>Chi tiết
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="index.php?act=tour-edit&id=<?= $tour['id'] ?>">
                                                    <i class="fas fa-edit text-warning me-2"></i>Chỉnh sửa
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item" href="index.php?act=tour-edit&id=<?= $tour['id'] ?>#schedule">
                                                    <i class="fas fa-calendar-day text-primary me-2"></i>Lịch trình
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="index.php?act=tour-edit&id=<?= $tour['id'] ?>#images">
                                                    <i class="fas fa-images text-success me-2"></i>Album ảnh
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            
                                            <!-- Nút Ngừng hoạt động (Soft Delete) -->
                                            <?php if ($tour['trang_thai'] !== 'NGUNG_HOAT_DONG'): ?>
                                                <li>
                                                    <a class="dropdown-item text-danger" href="index.php?act=tour-delete&id=<?= $tour['id'] ?>" 
                                                       onclick="return confirm('Bạn có chắc muốn NGỪNG HOẠT ĐỘNG tour này? Dữ liệu sẽ không bị xóa vĩnh viễn nhưng sẽ ẩn khỏi trang bán hàng.')">
                                                        <i class="fas fa-ban me-2"></i>Ngừng hoạt động
                                                    </a>
                                                </li>
                                            <?php else: ?>
                                                <li><span class="dropdown-item text-muted disabled"><i class="fas fa-ban me-2"></i>Đã dừng HĐ</span></li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white py-3">
                <small class="text-muted">Hiển thị danh sách tour mới nhất.</small>
            </div>
        </div>
    </div>
</main>

<?php require_once PATH_ROOT . '/views/footer.php'; ?>