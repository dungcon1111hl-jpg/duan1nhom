<?php
require_once 'models/BaoCaoModel.php';

class BaoCaoController {
    private $model;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->model = new BaoCaoModel($db);
    }

    public function index() {
        // 1. Nhận filter ngày (Mặc định là tháng hiện tại)
        $fromDate = $_GET['from'] ?? date('Y-m-01');
        $toDate = $_GET['to'] ?? date('Y-m-t'); // Ngày cuối tháng

        // 2. Lấy dữ liệu thô
        $dataRaw = $this->model->getThongKeTongHop($fromDate, $toDate);

        // 3. Tính toán Lợi nhuận & Tổng kết toàn cục
        $tong_doanh_thu_all = 0;
        $tong_chi_phi_all = 0;
        $tong_loi_nhuan_all = 0;
        $reportData = [];

        foreach ($dataRaw as $row) {
            $doanh_thu = (float)$row['tong_doanh_thu'];
            $chi_phi = (float)$row['tong_chi_phi_ncc'] + (float)$row['tong_chi_phi_khac'];
            $loi_nhuan = $doanh_thu - $chi_phi;
            
            // Tính % lợi nhuận (Margin)
            $ty_suat = ($doanh_thu > 0) ? round(($loi_nhuan / $doanh_thu) * 100, 1) : 0;

            // Cộng dồn tổng cục
            $tong_doanh_thu_all += $doanh_thu;
            $tong_chi_phi_all += $chi_phi;
            $tong_loi_nhuan_all += $loi_nhuan;

            // Thêm dữ liệu đã xử lý vào mảng
            $row['tong_chi_phi'] = $chi_phi;
            $row['loi_nhuan'] = $loi_nhuan;
            $row['ty_suat'] = $ty_suat;
            $reportData[] = $row;
        }

        // 4. Chuẩn bị dữ liệu cho Biểu đồ (ChartJS)
        $chartLabels = [];
        $chartRevenue = [];
        $chartProfit = [];
        
        // Lấy 5 tour mới nhất để vẽ biểu đồ cho đỡ rối
        $chartData = array_slice($reportData, 0, 10); 
        foreach ($chartData as $item) {
            $chartLabels[] = substr($item['ten_tour'], 0, 20) . '...'; // Cắt tên cho ngắn
            $chartRevenue[] = $item['tong_doanh_thu'];
            $chartProfit[] = $item['loi_nhuan'];
        }

        require ROOT . "/views/admin/baocao/index.php";
    }
}
?>