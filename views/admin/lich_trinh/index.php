<?php require ROOT."/views/header.php"; ?>

<div class="container-fluid px-4">
  <h1 class="mt-4">Lịch trình chuyến đi</h1>

  <a class="btn btn-primary my-3"
     href="index.php?act=lich-trinh-create&tour_id=<?= (int)($_GET['tour_id']??0) ?>">
    + Thêm ngày
  </a>

  <table class="table table-bordered">
    <thead class="table-dark">
      <tr>
        <th>Ngày thứ</th>
        <th>Tiêu đề</th>
        <th>Hoạt động</th>
        <th>Giờ BD</th>
        <th>Giờ KT</th>
        <th>Ghi chú</th>
        <th>Thao tác</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($list as $d): ?>
        <tr>
          <td><?= $d['thu_tu_ngay'] ?></td>
          <td><?= htmlspecialchars($d['tieu_de']) ?></td>
          <td><?= nl2br(htmlspecialchars($d['hoat_dong'])) ?></td>
          <td><?= $d['gio_bat_dau'] ?></td>
          <td><?= $d['gio_ket_thuc'] ?></td>
          <td><?= nl2br(htmlspecialchars($d['ghi_chu'])) ?></td>
          <td>
            <a class="btn btn-sm btn-warning"
               href="index.php?act=lich-trinh-edit&id=<?= $d['id'] ?>">
               Sửa
            </a>
            <a class="btn btn-sm btn-danger"
               onclick="return confirm('Xóa ngày này?')"
               href="index.php?act=lich-trinh-delete&id=<?= $d['id'] ?>&tour_id=<?= $d['tour_id'] ?>">
               Xóa
            </a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <a class="btn btn-secondary mt-3" href="index.php?act=lich-khoi-hanh">
    ← Quay lại lịch khởi hành
  </a>
</div>

<?php require ROOT."/views/footer.php"; ?>
