<?php require_once PATH_ROOT . '/views/header.php'; ?>

<div id="layoutSidenav_content">
    <main>

        <div class="container-fluid px-4">

            <!-- TITLE + ACTION -->
            <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                <h2 class="fw-bold text-primary">
                    <i class="fas fa-route me-2"></i> Quản Lý Tour Du Lịch
                </h2>

                <a href="index.php?act=tour-create" class="btn btn-success px-4 shadow-sm">
                    <i class="fas fa-plus me-2"></i> Thêm Tour Mới
                </a>
            </div>

            <!-- SEARCH PANEL -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <strong><i class="fas fa-search me-2"></i> Tìm kiếm & Lọc Tour</strong>
                </div>

                <div class="card-body p-4">
                    <form method="GET" action="index.php">
                        <input type="hidden" name="act" value="tours">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <input type="text" name="ten_tour" class="form-control"
                                       placeholder="Nhập tên tour..."
                                       value="<?= htmlspecialchars($_GET['ten_tour'] ?? '') ?>">
                            </div>

                            <div class="col-md-4">
                                <select name="trang_thai" class="form-select">
                                    <option value="">-- Tất cả trạng thái --</option>
                                    <option value="CON_VE" <?= ($_GET['trang_thai'] ?? '') == 'CON_VE' ? 'selected' : '' ?>>
                                        Còn vé
                                    </option>
                                    <option value="HET_VE" <?= ($_GET['trang_thai'] ?? '') == 'HET_VE' ? 'selected' : '' ?>>
                                        Hết vé
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-2 d-grid">
                                <button class="btn btn-primary">
                                    <i class="fas fa-search me-2"></i> Tìm kiếm
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <?php
            $data = is_array($tours) ? $tours : ($tours instanceof PDOStatement ? $tours->fetchAll(PDO::FETCH_ASSOC) : []);
            $countTours = count($data);
            ?>

            <!-- MAIN TABLE -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="m-0 fw-bold text-primary">
                        <i class="fas fa-list-ul me-2"></i> Danh Sách Tour (<?= $countTours ?> tour)
                    </h5>
                </div>

                <div class="card-body p-0">
                    <?php if ($countTours == 0): ?>
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-box-open fa-3x mb-3"></i>
                            <h5 class="fw-bold">Chưa có tour nào</h5>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">

                                <thead class="table-light text-center">
                                <tr>
                                    <th>#</th>
                                    <th>Ảnh</th>
                                    <th>Tour & Hành Trình</th>
                                    <th>Thời Gian</th>
                                    <th>Giá & Vé</th>
                                    <th>Trạng Thái</th>
                                    <th>Thao Tác</th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php $i = 1;
                                foreach ($data as $tour): ?>
                                    <tr>

                                        <td class="text-center fw-bold"><?= $i++ ?></td>

                                        <td class="text-center">
                                            <?php if ($tour['anh_minh_hoa']): ?>
                                                <img src="<?= BASE_URL ?>uploads/tours/<?= $tour['anh_minh_hoa'] ?>"
                                                     style="width:90px;height:60px;object-fit:cover;"
                                                     class="rounded shadow-sm">
                                            <?php else: ?>
                                                <div class="bg-light border rounded text-muted d-flex align-items-center justify-content-center"
                                                     style="width:90px;height:60px;">
                                                    <small>Không ảnh</small>
                                                </div>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <div class="fw-bold text-primary"><?= $tour['ten_tour'] ?></div>
                                            <div class="small text-muted">
                                                <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                                <?= $tour['dia_diem_bat_dau'] ?> → <?= $tour['dia_diem_ket_thuc'] ?>
                                            </div>
                                        </td>

                                        <td class="text-center small">
                                            <div><strong>KH:</strong> <?= date('d/m/Y', strtotime($tour['ngay_khoi_hanh'])) ?></div>
                                            <div><strong>KT:</strong> <?= date('d/m/Y', strtotime($tour['ngay_ket_thuc'])) ?></div>
                                        </td>

                                        <td class="text-center">
                                            <div class="text-danger fw-bold fs-5">
                                                <?= number_format($tour['gia_tour']) ?>đ
                                            </div>
                                            <small class="text-success">
                                                Còn <?= $tour['so_ve_con_lai'] ?>/<?= $tour['so_luong_ve'] ?> vé
                                            </small>
                                        </td>

                                        <td class="text-center">
                                            <?php if ($tour['trang_thai'] == 'CON_VE'): ?>
                                                <span class="badge bg-success px-3 py-2">Còn vé</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger px-3 py-2">Hết vé</span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="text-center">
                                            <a href="index.php?act=tour-detail&id=<?= $tour['id'] ?>"
                                               class="btn btn-info btn-sm me-1">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="index.php?act=tour-edit&id=<?= $tour['id'] ?>"
                                               class="btn btn-warning btn-sm me-1">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="index.php?act=tour-schedule&tour_id=<?= $tour['id'] ?>"
                                               class="btn btn-secondary btn-sm me-1">
                                                <i class="fas fa-calendar-alt"></i>
                                            </a>
                                            <a href="index.php?act=tour-delete&id=<?= $tour['id'] ?>"
                                               onclick="return confirm('Bạn chắc chắn muốn xóa tour này?')"
                                               class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>

                                    </tr>
                                <?php endforeach; ?>
                                </tbody>

                            </table>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>

    </main>
</div>

<?php require_once PATH_ROOT . '/views/footer.php'; ?>
