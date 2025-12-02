<?php require ROOT."/views/header.php"; ?>

<div class="container-fluid px-4">
  <h1 class="mt-4">Sửa lịch khởi hành</h1>

  <form method="POST" action="index.php?act=lich-khoi-hanh-update">
    <input type="hidden" name="id" value="<?= $item['id'] ?>">

    <div class="mb-3">
      <label class="form-label">Tour</label>
      <select name="tour_id" class="form-select" required>
        <?php foreach($tours as $t): ?>
          <option value="<?= $t['id'] ?>"
            <?= $t['id']==$item['tour_id']?'selected':'' ?>>
            <?= htmlspecialchars($t['ten_tour']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Ngày bắt đầu</label>
        <input type="date" name="ngay_bat_dau" class="form-control"
               value="<?= $item['ngay_bat_dau'] ?>" required>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">Ngày kết thúc</label>
        <input type="date" name="ngay_ket_thuc" class="form-control"
               value="<?= $item['ngay_ket_thuc'] ?>" required>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label">Điểm tập trung</label>
      <input name="diem_tap_trung" class="form-control"
             value="<?= htmlspecialchars($item['diem_tap_trung']) ?>">
    </div>

    <div class="row">
      <div class="col-md-4 mb-3">
        <label class="form-label">Số chỗ tối đa</label>
        <input type="number" name="so_cho_toi_da" class="form-control"
               value="<?= $item['so_cho_toi_da'] ?>">
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label">Số chỗ đã đặt</label>
        <input type="number" name="so_cho_da_dat" class="form-control"
               value="<?= $item['so_cho_da_dat'] ?>">
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label">Giá người lớn</label>
        <input type="number" name="gia_nguoi_lon" class="form-control"
               value="<?= $item['gia_nguoi_lon'] ?>">
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label">Giá trẻ em</label>
      <input type="number" name="gia_tre_em" class="form-control"
             value="<?= $item['gia_tre_em'] ?>">
    </div>

    <div class="mb-3">
      <label class="form-label">Ghi chú</label>
      <textarea name="ghi_chu" class="form-control"><?= htmlspecialchars($item['ghi_chu']) ?></textarea>
    </div>

    <button class="btn btn-success">Cập nhật</button>
    <a class="btn btn-secondary" href="index.php?act=lich-khoi-hanh">Hủy</a>
  </form>
</div>

<?php require ROOT."/views/footer.php"; ?>
