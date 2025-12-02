-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 02, 2025 at 09:58 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tour_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `id` int NOT NULL,
  `tour_id` int NOT NULL,
  `lich_id` int DEFAULT NULL,
  `khach_hang_id` int DEFAULT NULL,
  `snapshot_kh_ho_ten` varchar(255) DEFAULT NULL,
  `snapshot_kh_so_dien_thoai` varchar(50) DEFAULT NULL,
  `snapshot_kh_email` varchar(255) DEFAULT NULL,
  `snapshot_kh_dia_chi` varchar(255) DEFAULT NULL,
  `loai_booking` enum('LE','DOAN') NOT NULL DEFAULT 'LE',
  `so_luong_nguoi_lon` int NOT NULL DEFAULT '1',
  `so_luong_tre_em` int NOT NULL DEFAULT '0',
  `so_luong_em_be` int NOT NULL DEFAULT '0',
  `yeu_cau_dac_biet` text,
  `ngay_dat` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `trang_thai` enum('CHO_XAC_NHAN','DA_COC','HOAN_TAT','DA_HUY') NOT NULL DEFAULT 'CHO_XAC_NHAN',
  `don_gia_nguoi_lon` decimal(15,2) DEFAULT NULL,
  `don_gia_tre_em` decimal(15,2) DEFAULT NULL,
  `don_gia_em_be` decimal(15,2) DEFAULT NULL,
  `tong_tien` decimal(15,2) DEFAULT NULL,
  `so_tien_coc` decimal(15,2) DEFAULT NULL,
  `da_thanh_toan` decimal(15,2) DEFAULT NULL,
  `con_no` decimal(15,2) DEFAULT NULL,
  `nguon_booking` varchar(100) DEFAULT NULL,
  `nhan_vien_id` int DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `snapshot_ten_tour` varchar(2500) NOT NULL,
  `so_luong_khach` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`id`, `tour_id`, `lich_id`, `khach_hang_id`, `snapshot_kh_ho_ten`, `snapshot_kh_so_dien_thoai`, `snapshot_kh_email`, `snapshot_kh_dia_chi`, `loai_booking`, `so_luong_nguoi_lon`, `so_luong_tre_em`, `so_luong_em_be`, `yeu_cau_dac_biet`, `ngay_dat`, `trang_thai`, `don_gia_nguoi_lon`, `don_gia_tre_em`, `don_gia_em_be`, `tong_tien`, `so_tien_coc`, `da_thanh_toan`, `con_no`, `nguon_booking`, `nhan_vien_id`, `updated_at`, `created_at`, `snapshot_ten_tour`, `so_luong_khach`) VALUES
(1, 1, 10, 3, 'Nguyen Van A', '0909123456', 'a.nguyen@example.com', 'Hà Nội', 'LE', 2, 1, 0, 'Xe gần cửa sổ', '2025-01-10 09:00:00', 'CHO_XAC_NHAN', 2000000.00, 1500000.00, 0.00, 5500000.00, 1000000.00, 1000000.00, 3500000.00, 'Facebook Ads', 5, NULL, '2025-01-10 09:00:00', '', 0),
(2, 2, 12, 5, 'Tran Thi B', '0912345678', 'b.tran@example.com', 'TP.HCM', 'DOAN', 4, 2, 1, 'Ăn chay cho 1 người', '2025-01-15 14:30:00', 'DA_COC', 1800000.00, 1200000.00, 800000.00, 8600000.00, 3000000.00, 3000000.00, 5600000.00, 'Website', 3, NULL, '2025-01-15 14:30:00', '', 0),
(3, 3, NULL, 2, 'Le Van C', '0988111222', 'c.le@example.com', 'Đà Nẵng', 'LE', 1, 0, 0, NULL, '2025-01-20 08:00:00', 'HOAN_TAT', 2500000.00, NULL, NULL, 2500000.00, 2500000.00, 2500000.00, 0.00, 'Hotline', 1, NULL, '2025-01-20 08:00:00', '', 0),
(4, 1, 11, 6, 'Pham Thi D', '0977222333', 'd.pham@example.com', 'Hải Phòng', 'LE', 2, 0, 1, 'Ghế gần hướng dẫn viên', '2025-01-22 10:20:00', 'DA_HUY', 2200000.00, NULL, 500000.00, 4900000.00, 500000.00, 0.00, 4400000.00, 'TikTok', 2, NULL, '2025-01-22 10:20:00', '', 0),
(5, 4, 20, 4, 'Hoang Van E', '0909333444', 'e.hoang@example.com', 'Cần Thơ', 'DOAN', 3, 1, 0, 'Không ăn hải sản', '2025-01-25 16:45:00', 'DA_COC', 3000000.00, 1800000.00, 0.00, 10800000.00, 4000000.00, 4000000.00, 6800000.00, 'Zalo OA', 4, NULL, '2025-01-25 16:45:00', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `booking_khach`
--

CREATE TABLE `booking_khach` (
  `id` int NOT NULL,
  `booking_id` int NOT NULL,
  `ho_ten` varchar(255) NOT NULL,
  `loai_khach` varchar(20) DEFAULT 'NGUOI_LON' COMMENT 'NGUOI_LON, TRE_EM, EM_BE',
  `gioi_tinh` varchar(10) DEFAULT 'Nam',
  `ngay_sinh` date DEFAULT NULL,
  `ghi_chu_dac_biet` text COMMENT 'Ăn chay, dị ứng...',
  `trang_thai_checkin` tinyint(1) DEFAULT '0' COMMENT '1: Đã đến, 0: Vắng'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chi_phi_khac`
--

CREATE TABLE `chi_phi_khac` (
  `id` int NOT NULL,
  `lich_id` int NOT NULL,
  `noi_dung_chi` varchar(255) NOT NULL,
  `so_tien` decimal(15,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `danh_muc_tour`
--

CREATE TABLE `danh_muc_tour` (
  `id` int NOT NULL,
  `ten_danh_muc` varchar(255) NOT NULL,
  `ma_danh_muc` varchar(50) NOT NULL COMMENT 'Dùng làm mã code trong ứng dụng',
  `mo_ta` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `danh_muc_tour`
--

INSERT INTO `danh_muc_tour` (`id`, `ten_danh_muc`, `ma_danh_muc`, `mo_ta`) VALUES
(1, 'Tour Trong Nước', 'TRONG_NUOC', 'Các tour du lịch nội địa.'),
(2, 'Tour Quốc Tế', 'QUOC_TE', 'Các tour du lịch nước ngoài.'),
(3, 'Tour Theo Yêu Cầu', 'THEO_YEU_CAU', 'Tour thiết kế riêng cho khách đoàn.'),
(7, 'tour quốc tế ', 'QUOC_TE1', 'đi du lịch pháp');

-- --------------------------------------------------------

--
-- Table structure for table `dat_dich_vu`
--

CREATE TABLE `dat_dich_vu` (
  `id` int NOT NULL,
  `booking_id` int NOT NULL COMMENT 'ID của đơn đặt tour',
  `ten_dich_vu` varchar(255) NOT NULL,
  `so_luong` int DEFAULT '1',
  `don_gia` decimal(15,2) DEFAULT '0.00',
  `thanh_tien` decimal(15,2) DEFAULT '0.00',
  `ghi_chu` text,
  `ngay_tao` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `diem_danh_khach`
--

CREATE TABLE `diem_danh_khach` (
  `id` int NOT NULL,
  `lich_id` int DEFAULT NULL,
  `khach_id` int DEFAULT NULL,
  `trang_thai` enum('CO_MAT','VANG_MAT','DEN_TRE') DEFAULT NULL,
  `thoi_gian` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hinh_anh_tour`
--

CREATE TABLE `hinh_anh_tour` (
  `id` int NOT NULL,
  `tour_id` int NOT NULL,
  `duong_dan` varchar(255) NOT NULL,
  `mo_ta_anh` varchar(255) DEFAULT NULL,
  `thu_tu_hien_thi` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `hinh_anh_tour`
--

INSERT INTO `hinh_anh_tour` (`id`, `tour_id`, `duong_dan`, `mo_ta_anh`, `thu_tu_hien_thi`) VALUES
(1, 2, 'uploads/tour_images/1763806678_69218dd6dbae0.png', '', 1),
(2, 3, 'uploads/tour_images/1763807161_69218fb9b3220.png', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `huong_dan_vien`
--

CREATE TABLE `huong_dan_vien` (
  `id` int NOT NULL,
  `ho_ten` varchar(255) NOT NULL,
  `ngay_sinh` date DEFAULT NULL,
  `so_dien_thoai` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `ngon_ngu` varchar(255) DEFAULT NULL COMMENT 'VD: vi,en,zh',
  `chung_chi` text,
  `kinh_nghiem` text,
  `suc_khoe` varchar(255) DEFAULT NULL,
  `anh_dai_dien` varchar(500) DEFAULT NULL,
  `trang_thai` varchar(50) DEFAULT NULL,
  `gioi_tinh` varchar(255) NOT NULL,
  `dia_chi` varchar(255) NOT NULL,
  `user_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `huong_dan_vien`
--

INSERT INTO `huong_dan_vien` (`id`, `ho_ten`, `ngay_sinh`, `so_dien_thoai`, `email`, `ngon_ngu`, `chung_chi`, `kinh_nghiem`, `suc_khoe`, `anh_dai_dien`, `trang_thai`, `gioi_tinh`, `dia_chi`, `user_id`) VALUES
(1, 'Nguyễn Văn A', '1999-05-15', '0123456789', 'nguyenvana@gmail.com', 'vi,en', 'Chứng chỉ A, B', '3 năm', 'Tốt', 'uploads/hdv/1763377336_Screenshot 2025-11-11 070437.png', 'ACTIVE', '', '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `khach_hang`
--

CREATE TABLE `khach_hang` (
  `id` int NOT NULL,
  `ho_ten` varchar(255) DEFAULT NULL,
  `gioi_tinh` enum('NAM','NU','KHAC') DEFAULT NULL,
  `ngay_sinh` date DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `so_dien_thoai` varchar(50) DEFAULT NULL,
  `dia_chi` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `khach_hang`
--

INSERT INTO `khach_hang` (`id`, `ho_ten`, `gioi_tinh`, `ngay_sinh`, `email`, `so_dien_thoai`, `dia_chi`) VALUES
(34, 'Nguyễn Văn An', 'NAM', '1985-03-15', 'nguyenvana@gmail.com', '0903123456', '123 Lê Lợi, Quận 1, TP.HCM'),
(35, 'Trần Thị Bích Ngọc', 'NU', '1992-07-22', 'bichngoc92@yahoo.com', '0918234567', '45 Nguyễn Huệ, Đà Nẵng'),
(36, 'Lê Hoàng Minh', 'NAM', '1978-11-30', 'lehoangminh@gmail.com', '0935123876', '56 Trần Phú, Hà Nội'),
(37, 'Phạm Thị Hồng Nhung', 'NU', '1995-01-08', 'hongnhung95@gmail.com', '0987654321', '89 Hùng Vương, Huế'),
(38, 'Vũ Văn Tuấn', 'NAM', '1980-09-12', 'vuvantuan@gmail.com', '0909112233', '12 Nguyễn Văn Cừ, Cần Thơ'),
(39, 'Đỗ Thị Lan Anh', 'NU', '1990-05-25', 'lananh.do@gmail.com', '0977888999', '78 Pasteur, Quận 1, TP.HCM'),
(40, 'Hoàng Minh Quân', 'NAM', '1987-12-03', 'minhquan87@gmail.com', '0922333444', 'Khu đô thị Sala, Quận 2, TP.HCM'),
(41, 'Nguyễn Thị Minh Thư', 'NU', '1993-04-18', 'minhthu93@gmail.com', '0905123456', '15 Lý Thường Kiệt, Hà Nội'),
(42, 'Trần Văn Hoàng', 'NAM', '1983-08-20', 'hoangtran83@gmail.com', '0918234567', '234 Hai Bà Trưng, Đà Nẵng'),
(43, 'Lê Thị Kim Oanh', 'NU', '1989-06-14', 'kimoanh89@gmail.com', '02839123456', 'Tầng 5, Tòa nhà ABC, Quận 3, TP.HCM'),
(44, 'Phạm Quốc Hưng', 'NAM', '1991-10-05', 'hungpham91@gmail.com', '0935123876', '56 Trần Phú, Hà Nội'),
(45, 'Vũ Thị Lan', 'NU', '1986-02-27', 'lanvuthi@gmail.com', '0987654321', '89 Hùng Vương, Huế'),
(46, 'Đỗ Văn Nam', 'NAM', '1994-11-11', 'namdo94@gmail.com', '0909112233', '12 Nguyễn Văn Cừ, Cần Thơ'),
(47, 'Hoàng Minh Tuấn', 'NAM', '1981-07-19', 'tuanhm81@gmail.com', '0922333444', '78 Pasteur, Quận 1, TP.HCM'),
(48, 'Nguyễn Ngọc Ánh', 'NU', '1996-03-30', 'ngocanh96@gmail.com', '0977888999', 'Khu đô thị Sala, Quận 2, TP.HCM'),
(49, 'Trần Quốc Bảo', 'NAM', '1988-09-08', 'bao.tran@gmail.com', '0912345678', '45 Nguyễn Thị Minh Khai, Bình Dương'),
(50, 'Lê Thị Thu Hà', 'NU', '1990-12-25', 'thuha90@gmail.com', '0908765432', '67 Lê Văn Sỹ, Quận 3, TP.HCM'),
(51, 'Phan Văn Dũng', 'NAM', '1975-05-17', 'dungphan75@gmail.com', '0938777666', '89 Trần Hưng Đạo, Hà Nội'),
(52, 'Mai Thị Hồng', 'NU', '1997-08-14', 'hongmai97@gmail.com', '0919888777', '234 Lê Hồng Phong, Nha Trang'),
(53, 'Ngô Văn Hùng', 'NAM', '1984-01-29', 'hungo84@gmail.com', '0905777888', '56 Nguyễn Trãi, Quy Nhơn'),
(54, 'Bùi Thị Kim Chi', 'NU', '1992-10-10', 'kimchi92@gmail.com', '0988666777', '78 Cách Mạng Tháng 8, Đà Lạt'),
(55, 'Đặng Văn Long', 'NAM', '1980-04-05', 'longdang80@gmail.com', '0922555666', '123 Phạm Ngũ Lão, Quận 1, TP.HCM'),
(56, 'Hà Thị Mai', 'NU', '1995-06-20', 'maihat95@gmail.com', '0913777888', '45 Bạch Đằng, Hải Phòng'),
(57, 'Trương Quốc Khánh', 'NAM', '1987-11-11', 'khanh.truong@gmail.com', '0933999888', '89 Nguyễn Văn Linh, Đà Nẵng'),
(58, 'Lý Thị Thanh', 'NU', '1993-02-28', 'thanhly93@gmail.com', '0904888999', '12 Võ Văn Tần, Quận 3, TP.HCM'),
(59, 'Tô Văn Đức', 'NAM', '1982-07-07', 'ducto82@gmail.com', '0977666555', '234 Nguyễn Huệ, Cần Thơ'),
(60, 'Huỳnh Thị Mỹ Linh', 'NU', '1998-09-15', 'mylinh98@gmail.com', '0912555666', '56 Lê Lợi, Vũng Tàu'),
(61, 'Dương Văn Tâm', 'NAM', '1979-12-01', 'tamduong79@gmail.com', '0903444555', '78 Hùng Vương, Phú Quốc'),
(62, 'Võ Thị Diễm', 'NU', '1991-03-22', 'diemvo91@gmail.com', '0988777666', '123 Trần Phú, Nha Trang'),
(63, 'Cao Văn Hậu', 'NAM', '1985-05-18', 'haocao85@gmail.com', '0922666777', '45 Lý Tự Trọng, Hà Nội');

-- --------------------------------------------------------

--
-- Table structure for table `khach_tham_gia`
--

CREATE TABLE `khach_tham_gia` (
  `id` int NOT NULL,
  `booking_id` int NOT NULL,
  `ho_ten` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gioi_tinh` enum('NAM','NU','KHAC') COLLATE utf8mb4_unicode_ci DEFAULT 'KHAC',
  `ngay_sinh` date DEFAULT NULL,
  `so_giay_to` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'CCCD hoặc Passport',
  `ghi_chu` text COLLATE utf8mb4_unicode_ci,
  `yeu_cau_dac_biet` text COLLATE utf8mb4_unicode_ci COMMENT 'Ăn chay, dị ứng, xe lăn...',
  `trang_thai_diem_danh` enum('CHUA_DIEM_DANH','CO_MAT','VANG','TRE') COLLATE utf8mb4_unicode_ci DEFAULT 'CHUA_DIEM_DANH',
  `ngay_cap_nhat_diem_danh` date DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lich_ban_hdv`
--

CREATE TABLE `lich_ban_hdv` (
  `id` int NOT NULL,
  `hdv_id` int NOT NULL COMMENT 'Link tới bảng huong_dan_vien',
  `ngay_bat_dau` datetime NOT NULL,
  `ngay_ket_thuc` datetime NOT NULL,
  `ly_do` varchar(255) NOT NULL COMMENT 'Bận việc riêng, Đi tour khác...',
  `loai_lich` varchar(50) DEFAULT 'TAM_THOI' COMMENT 'CO_DINH, TAM_THOI',
  `trang_thai` varchar(50) DEFAULT 'CHO_XAC_NHAN' COMMENT 'CHO_XAC_NHAN, DA_XAC_NHAN, TU_CHOI',
  `ghi_chu` text,
  `ngay_tao` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lich_ban_hdv`
--

INSERT INTO `lich_ban_hdv` (`id`, `hdv_id`, `ngay_bat_dau`, `ngay_ket_thuc`, `ly_do`, `loai_lich`, `trang_thai`, `ghi_chu`, `ngay_tao`) VALUES
(1, 1, '2025-11-06 02:55:00', '2025-11-30 02:55:00', 'đi tour khác', 'TAM_THOI', 'DA_XAC_NHAN', '', '2025-11-28 02:55:29');

-- --------------------------------------------------------

--
-- Table structure for table `lich_khoi_hanh`
--

CREATE TABLE `lich_khoi_hanh` (
  `id` int NOT NULL,
  `tour_id` int NOT NULL,
  `ngay_khoi_hanh` datetime NOT NULL,
  `ngay_ket_thuc` datetime NOT NULL,
  `diem_tap_trung` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `so_cho_toi_da` int DEFAULT '20',
  `so_cho_da_dat` int DEFAULT '0',
  `gia_nguoi_lon` decimal(15,2) DEFAULT '0.00',
  `gia_tre_em` decimal(15,2) DEFAULT '0.00',
  `trang_thai` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'DU_KIEN' COMMENT 'DU_KIEN, CHOT_SO, DANG_CHAY, HOAN_THANH, HUY',
  `ghi_chu` text COLLATE utf8mb4_general_ci,
  `ngay_tao` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lich_khoi_hanh`
--

INSERT INTO `lich_khoi_hanh` (`id`, `tour_id`, `ngay_khoi_hanh`, `ngay_ket_thuc`, `diem_tap_trung`, `so_cho_toi_da`, `so_cho_da_dat`, `gia_nguoi_lon`, `gia_tre_em`, `trang_thai`, `ghi_chu`, `ngay_tao`) VALUES
(1, 1, '2025-11-27 19:10:58', '2025-11-27 19:10:58', 'ddd', 20, 2, 1000.00, 1000.00, 'DU_KIEN', 'dddd', '2025-11-19 02:10:58'),
(16, 2, '2025-11-08 05:15:00', '2025-11-09 02:13:00', 'Cổng rạp xiếc ', 20, 0, 10000.00, 0.00, 'NHAN_KHACH', 'Ghi chú nội bộ', '2025-11-28 02:13:57'),
(17, 2, '2025-11-06 02:36:00', '2025-11-19 02:36:00', 'Cổng rạp xiếc a', 20, 0, 20000.00, 0.00, 'DU_KIEN', 'Cổng rạp xiếc a', '2025-11-28 02:37:57');

-- --------------------------------------------------------

--
-- Table structure for table `lich_su_booking`
--

CREATE TABLE `lich_su_booking` (
  `id` int NOT NULL,
  `booking_id` int NOT NULL,
  `trang_thai_moi` enum('CHO_XAC_NHAN','DA_COC','HOAN_TAT','HUY') NOT NULL,
  `nguoi_thay_doi_id` int DEFAULT NULL COMMENT 'ID của admin/user thay đổi',
  `thoi_gian` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lich_trinh_tour`
--

CREATE TABLE `lich_trinh_tour` (
  `id` int NOT NULL,
  `tour_id` int NOT NULL,
  `ngay_thu` int DEFAULT NULL COMMENT 'Ngày 1, Ngày 2...',
  `thu_tu_ngay` int DEFAULT NULL COMMENT 'Để sắp xếp',
  `tieu_de` varchar(255) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'VD: Sáng - Tham quan Đại Nội',
  `dia_diem` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Điểm tham quan cụ thể',
  `noi_dung` text COLLATE utf8mb4_general_ci COMMENT 'Mô tả chi tiết',
  `hoat_dong` text COLLATE utf8mb4_general_ci,
  `gio_bat_dau` time DEFAULT NULL,
  `gio_ket_thuc` time DEFAULT NULL,
  `ghi_chu` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lich_trinh_tour`
--

INSERT INTO `lich_trinh_tour` (`id`, `tour_id`, `ngay_thu`, `thu_tu_ngay`, `tieu_de`, `dia_diem`, `noi_dung`, `hoat_dong`, `gio_bat_dau`, `gio_ket_thuc`, `ghi_chu`) VALUES
(1, 2, NULL, NULL, 'z', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 2, NULL, NULL, 'xxx', NULL, NULL, NULL, '07:02:00', '20:02:00', NULL),
(3, 3, NULL, NULL, 'sabd', NULL, NULL, NULL, '17:29:00', '17:28:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lien_ket_tour_ncc`
--

CREATE TABLE `lien_ket_tour_ncc` (
  `id` int NOT NULL,
  `tour_id` int NOT NULL,
  `ncc_id` int NOT NULL,
  `ghi_chu` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nhat_ky_tour`
--

CREATE TABLE `nhat_ky_tour` (
  `id` int NOT NULL,
  `lich_id` int NOT NULL,
  `hdv_id` int NOT NULL,
  `ngay_ghi` datetime DEFAULT CURRENT_TIMESTAMP,
  `tieu_de` varchar(255) NOT NULL,
  `noi_dung` text NOT NULL,
  `loai_nhat_ky` varchar(50) DEFAULT 'THONG_THUONG' COMMENT 'THONG_THUONG, SU_CO, PHAN_HOI',
  `hinh_anh` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nha_cung_cap`
--

CREATE TABLE `nha_cung_cap` (
  `id` int NOT NULL,
  `ten_don_vi` varchar(255) NOT NULL,
  `loai_dich_vu` enum('KHACH_SAN','VAN_CHUYEN','NHA_HANG','VE_THAM_QUAN','KHAC') NOT NULL,
  `nguoi_lien_he` varchar(255) DEFAULT NULL,
  `so_dien_thoai` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `dia_chi` varchar(255) DEFAULT NULL,
  `ghi_chu` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `nha_cung_cap`
--

INSERT INTO `nha_cung_cap` (`id`, `ten_don_vi`, `loai_dich_vu`, `nguoi_lien_he`, `so_dien_thoai`, `email`, `dia_chi`, `ghi_chu`) VALUES
(1, 'Limosine Hà nội - Đà Nẵng', 'VAN_CHUYEN', 'Zungti', '0899999999', 'Zungti@gmail.com', 'Nam từ liêm hà nội', 'Đối tác vận chuyển lâu năm'),
(2, 'Khách sạn Biển Xanh', 'KHACH_SAN', 'Trần Minh Hòa', '0901122334', 'contact@bienxanh.vn', 'Sơn Trà, Đà Nẵng', 'Hợp tác theo đoàn'),
(3, 'Nhà hàng Hải Sản Phố Cổ', 'NHA_HANG', 'Lê Thị Cẩm', '0934221122', 'phoco.haisan@gmail.com', 'Hội An, Quảng Nam', 'Ưu đãi khách theo tour'),
(4, 'Tour Ngũ Hành Sơn', 'VE_THAM_QUAN', 'Phạm Hoàng Khôi', '0912345678', 'khoi.tour@gmail.com', 'Ngũ Hành Sơn, Đà Nẵng', 'Bán vé số lượng lớn'),
(5, 'Dịch vụ Xe Điện CityGo', 'VAN_CHUYEN', 'Hoàng Tiến', '0888888888', 'tien@citygo.vn', 'Hải Châu, Đà Nẵng', 'Giá tốt cuối tuần'),
(6, 'Khách sạn Hoa Sen', 'KHACH_SAN', 'Nguyễn Thị Lan', '0944556677', 'hoasenhotel@gmail.com', 'Trung tâm Huế', 'Phục vụ khách VIP'),
(7, 'Nhà hàng Tre Việt', 'NHA_HANG', 'Võ Thành Long', '0966332211', 'nhahangtreviet@gmail.com', 'Hải Châu, Đà Nẵng', 'Đối tác ăn uống đoàn'),
(8, 'Vé tham quan Bà Nà Hills', 'VE_THAM_QUAN', 'Duy Quân', '0911998877', 'banahills.ticket.vn@gmail.com', 'Đà Nẵng', 'Chiết khấu theo số lượng'),
(9, 'Dịch vụ Media TravelShot', 'KHAC', 'Bảo Lâm', '0977554433', 'lam.travelshot@gmail.com', 'Hội An, Quảng Nam', 'Quay chụp media cho tour'),
(10, 'Taxi Sông Hàn', 'VAN_CHUYEN', 'Hữu Trọng', '0905566778', 'taxisonghan@gmail.com', 'Đà Nẵng', 'Hỗ trợ đưa đón sân bay'),
(11, 'Limosine Hà Nội - Đà Nẵng', 'VAN_CHUYEN', 'Zungti', '0899999999', 'Zungti@gmail.com', 'Nam Từ Liêm, Hà Nội', 'Đối tác vận chuyển lâu năm');

-- --------------------------------------------------------

--
-- Table structure for table `phan_bo_dich_vu`
--

CREATE TABLE `phan_bo_dich_vu` (
  `id` int NOT NULL,
  `lich_id` int NOT NULL,
  `ncc_id` int NOT NULL COMMENT 'Link tới bảng nha_cung_cap',
  `loai_dich_vu` varchar(50) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'XE, KHACH_SAN, NHA_HANG, VE_THAM_QUAN, MAY_BAY',
  `chi_tiet` text COLLATE utf8mb4_general_ci COMMENT 'VD: 10 phòng đôi, Xe 29 chỗ...',
  `don_gia` decimal(15,2) DEFAULT '0.00',
  `so_luong` int DEFAULT '1',
  `thanh_tien` decimal(15,2) DEFAULT '0.00',
  `trang_thai_dat` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'CHO_XAC_NHAN',
  `ghi_chu` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `phan_bo_dich_vu`
--

INSERT INTO `phan_bo_dich_vu` (`id`, `lich_id`, `ncc_id`, `loai_dich_vu`, `chi_tiet`, `don_gia`, `so_luong`, `thanh_tien`, `trang_thai_dat`, `ghi_chu`) VALUES
(1, 16, 11, 'XE', '29', 1000.00, 1, 1000.00, 'CHO_XAC_NHAN', ''),
(2, 17, 7, 'XE', '29', 10000.00, 1, 10000.00, 'CHO_XAC_NHAN', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `phan_cong_nhan_su`
--

CREATE TABLE `phan_cong_nhan_su` (
  `id` int NOT NULL,
  `lich_id` int NOT NULL,
  `hdv_id` int DEFAULT NULL,
  `ho_ten` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `vai_tro` varchar(50) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'HUONG_DAN_VIEN, TAI_XE, HAU_CAN, DIEU_HANH',
  `so_dien_thoai` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ngay_nhan_viec` date DEFAULT NULL,
  `ghi_chu` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `phan_cong_nhan_su`
--

INSERT INTO `phan_cong_nhan_su` (`id`, `lich_id`, `hdv_id`, `ho_ten`, `vai_tro`, `so_dien_thoai`, `ngay_nhan_viec`, `ghi_chu`) VALUES
(1, 1, NULL, 'Nguyễn Văn A', 'HUONG_DAN_VIEN', '0123456789', NULL, ''),
(2, 17, NULL, 'Nguyễn Văn A', 'HUONG_DAN_VIEN', '', NULL, NULL),
(3, 17, NULL, 'nguyễn anh nam', 'TAI_XE', '08899455454', NULL, NULL),
(4, 17, NULL, 'aaaa khánh', 'HAU_CAN', '', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `phan_phong_khach`
--

CREATE TABLE `phan_phong_khach` (
  `id` int NOT NULL,
  `lich_id` int DEFAULT NULL,
  `khach_id` int DEFAULT NULL,
  `so_phong` varchar(50) DEFAULT NULL,
  `ghi_chu` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `thanh_toan`
--

CREATE TABLE `thanh_toan` (
  `id` int NOT NULL,
  `booking_id` int NOT NULL COMMENT 'Link tới đơn hàng',
  `so_tien` decimal(15,2) NOT NULL,
  `ngay_thanh_toan` datetime DEFAULT CURRENT_TIMESTAMP,
  `phuong_thuc` varchar(50) DEFAULT 'TIEN_MAT' COMMENT 'TIEN_MAT, CHUYEN_KHOAN, THE',
  `nhan_vien_id` int DEFAULT NULL COMMENT 'Ai là người thu tiền',
  `ghi_chu` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `thanh_toan`
--

INSERT INTO `thanh_toan` (`id`, `booking_id`, `so_tien`, `ngay_thanh_toan`, `phuong_thuc`, `nhan_vien_id`, `ghi_chu`) VALUES
(1, 10, 1000000.00, '2025-11-23 17:54:27', 'CHUYEN_KHOAN', 1, 'thu tiền cọc');

-- --------------------------------------------------------

--
-- Table structure for table `tour`
--

CREATE TABLE `tour` (
  `id` int NOT NULL,
  `ma_tour` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ten_tour` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `loai_tour` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'TRONG_NUOC' COMMENT 'TRONG_NUOC, QUOC_TE',
  `loai_tour_nang_cao` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'TRON_GOI' COMMENT 'TRON_GOI, GHEP_DOAN, THEO_YEU_CAU, VIP',
  `doi_tuong_khach` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'KHACH_LE' COMMENT 'KHACH_LE, KHACH_DOAN, GIA_DINH',
  `mo_ta_ngan` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mo_ta_chi_tiet` text COLLATE utf8mb4_general_ci,
  `anh_minh_hoa` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `chinh_sach` text COLLATE utf8mb4_general_ci,
  `dia_diem_bat_dau` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `diem_trung_chuyen` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `dia_diem_ket_thuc` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ngay_khoi_hanh` date DEFAULT NULL,
  `ngay_ket_thuc` date DEFAULT NULL,
  `gia_tour` decimal(15,2) DEFAULT '0.00' COMMENT 'Giá hiển thị chung',
  `gia_nguoi_lon` decimal(15,2) DEFAULT '0.00',
  `gia_tre_em` decimal(15,2) DEFAULT '0.00',
  `gia_em_be` decimal(15,2) DEFAULT '0.00',
  `phu_thu` decimal(15,2) DEFAULT '0.00' COMMENT 'Phụ thu lễ tết, phòng đơn',
  `so_luong_ve` int DEFAULT '0' COMMENT 'Tổng chỗ tối đa',
  `so_ve_con_lai` int DEFAULT '0',
  `hdv_id` int DEFAULT NULL,
  `so_khach_toithieu` int DEFAULT '1' COMMENT 'Số khách tối thiểu để khởi hành',
  `thong_tin_hdv` text COLLATE utf8mb4_general_ci COMMENT 'Thông tin HDV dự kiến',
  `trang_thai` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'CON_VE' COMMENT 'CON_VE, HET_VE, HUY, DA_KHOI_HANH, NGUNG_HOAT_DONG',
  `ngay_tao` datetime DEFAULT CURRENT_TIMESTAMP,
  `ngay_cap_nhat` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tour`
--

INSERT INTO `tour` (`id`, `ma_tour`, `ten_tour`, `loai_tour`, `loai_tour_nang_cao`, `doi_tuong_khach`, `mo_ta_ngan`, `mo_ta_chi_tiet`, `anh_minh_hoa`, `chinh_sach`, `dia_diem_bat_dau`, `diem_trung_chuyen`, `dia_diem_ket_thuc`, `ngay_khoi_hanh`, `ngay_ket_thuc`, `gia_tour`, `gia_nguoi_lon`, `gia_tre_em`, `gia_em_be`, `phu_thu`, `so_luong_ve`, `so_ve_con_lai`, `hdv_id`, `so_khach_toithieu`, `thong_tin_hdv`, `trang_thai`, `ngay_tao`, `ngay_cap_nhat`) VALUES
(1, 'Tour2025aa', 'Ha noi a', 'TRONG_NUOC', 'GHEP_DOAN', 'KHACH_LE', NULL, 'Chính sách nợ hoàn', 'tour_1763804523_9622.png', NULL, 'Hà nội', 'huế', 'Đà nẵng', '2025-11-12', '2025-11-15', 0.00, 100000.00, 10000.00, 1400.00, 10000.00, 30, 30, NULL, 10, '', 'CON_VE', '2025-11-22 16:42:03', '2025-11-22 16:42:03'),
(2, 'Tour2025aa', 'Ha noi a', 'TRONG_NUOC', 'GHEP_DOAN', 'KHACH_LE', '', 'Chính sách nợ hoàn', 'tour_1763804667_7901.png', NULL, 'Hà nội', 'huế', 'Đà nẵng', '2025-11-12', '2025-11-15', 0.00, 100000.00, 10000.00, 1400.00, 10000.00, 30, 30, 1, 1, '', 'NGUNG_HOAT_DONG', '2025-11-22 16:44:27', '2025-11-22 17:18:20'),
(3, '', 'Ha noi a', 'QUOC_TE', 'GHEP_DOAN', 'KHACH_LE', 'dsdasdasdasdasdasdasdasdasdasd', 'dsdsdadasdasdasdasdasdasd', 'tour_1763807008_2123.png', NULL, 'Hà nội', 'huế', 'Đà nẵng', '2025-11-12', '2025-11-22', 1000000.00, 10000.00, 1000.00, 100.00, 50000.00, 30, 30, 1, 1, NULL, 'CON_VE', '2025-11-22 17:23:28', '2025-11-22 17:37:32');

-- --------------------------------------------------------

--
-- Table structure for table `tour_ncc`
--

CREATE TABLE `tour_ncc` (
  `tour_id` int NOT NULL,
  `ncc_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tour_ncc`
--

INSERT INTO `tour_ncc` (`tour_id`, `ncc_id`) VALUES
(3, 6),
(2, 8),
(3, 8);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `so_dien_thoai` varchar(20) DEFAULT NULL,
  `role` varchar(20) DEFAULT 'staff' COMMENT 'admin, guide, staff',
  `trang_thai` tinyint(1) DEFAULT '1' COMMENT '1: Hoạt động, 0: Khóa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password_hash`, `full_name`, `email`, `so_dien_thoai`, `role`, `trang_thai`) VALUES
(1, 'admin', '123456', 'Quản Trị Viên', 'admin@gmail.com', NULL, 'admin', 1),
(2, 'dangconghau', '123456789', 'Đặng Công Hậu', 'alikallar04@gmail.com', '0123456789', 'guide', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booking_khach`
--
ALTER TABLE `booking_khach`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `chi_phi_khac`
--
ALTER TABLE `chi_phi_khac`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lich_id` (`lich_id`);

--
-- Indexes for table `danh_muc_tour`
--
ALTER TABLE `danh_muc_tour`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ma_danh_muc` (`ma_danh_muc`);

--
-- Indexes for table `dat_dich_vu`
--
ALTER TABLE `dat_dich_vu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `diem_danh_khach`
--
ALTER TABLE `diem_danh_khach`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lich_id` (`lich_id`),
  ADD KEY `khach_id` (`khach_id`);

--
-- Indexes for table `hinh_anh_tour`
--
ALTER TABLE `hinh_anh_tour`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Indexes for table `huong_dan_vien`
--
ALTER TABLE `huong_dan_vien`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `khach_hang`
--
ALTER TABLE `khach_hang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `khach_tham_gia`
--
ALTER TABLE `khach_tham_gia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_booking` (`booking_id`),
  ADD KEY `idx_ho_ten` (`ho_ten`);

--
-- Indexes for table `lich_ban_hdv`
--
ALTER TABLE `lich_ban_hdv`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hdv_id` (`hdv_id`);

--
-- Indexes for table `lich_khoi_hanh`
--
ALTER TABLE `lich_khoi_hanh`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Indexes for table `lich_su_booking`
--
ALTER TABLE `lich_su_booking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `lich_trinh_tour`
--
ALTER TABLE `lich_trinh_tour`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Indexes for table `lien_ket_tour_ncc`
--
ALTER TABLE `lien_ket_tour_ncc`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_tour_ncc` (`tour_id`,`ncc_id`),
  ADD KEY `ncc_id` (`ncc_id`);

--
-- Indexes for table `nhat_ky_tour`
--
ALTER TABLE `nhat_ky_tour`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_lich_id` (`lich_id`);

--
-- Indexes for table `nha_cung_cap`
--
ALTER TABLE `nha_cung_cap`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `phan_bo_dich_vu`
--
ALTER TABLE `phan_bo_dich_vu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lich_id` (`lich_id`),
  ADD KEY `ncc_id` (`ncc_id`);

--
-- Indexes for table `phan_cong_nhan_su`
--
ALTER TABLE `phan_cong_nhan_su`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lich_id` (`lich_id`);

--
-- Indexes for table `phan_phong_khach`
--
ALTER TABLE `phan_phong_khach`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lich_id` (`lich_id`),
  ADD KEY `khach_id` (`khach_id`);

--
-- Indexes for table `thanh_toan`
--
ALTER TABLE `thanh_toan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tour`
--
ALTER TABLE `tour`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tour_hdv` (`hdv_id`);

--
-- Indexes for table `tour_ncc`
--
ALTER TABLE `tour_ncc`
  ADD PRIMARY KEY (`tour_id`,`ncc_id`),
  ADD KEY `ncc_id` (`ncc_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `booking_khach`
--
ALTER TABLE `booking_khach`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `danh_muc_tour`
--
ALTER TABLE `danh_muc_tour`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `dat_dich_vu`
--
ALTER TABLE `dat_dich_vu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hinh_anh_tour`
--
ALTER TABLE `hinh_anh_tour`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `huong_dan_vien`
--
ALTER TABLE `huong_dan_vien`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `khach_hang`
--
ALTER TABLE `khach_hang`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `khach_tham_gia`
--
ALTER TABLE `khach_tham_gia`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `lich_ban_hdv`
--
ALTER TABLE `lich_ban_hdv`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `lich_khoi_hanh`
--
ALTER TABLE `lich_khoi_hanh`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `lich_trinh_tour`
--
ALTER TABLE `lich_trinh_tour`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `nhat_ky_tour`
--
ALTER TABLE `nhat_ky_tour`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phan_bo_dich_vu`
--
ALTER TABLE `phan_bo_dich_vu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `phan_cong_nhan_su`
--
ALTER TABLE `phan_cong_nhan_su`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `thanh_toan`
--
ALTER TABLE `thanh_toan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tour`
--
ALTER TABLE `tour`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking_khach`
--
ALTER TABLE `booking_khach`
  ADD CONSTRAINT `booking_khach_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hinh_anh_tour`
--
ALTER TABLE `hinh_anh_tour`
  ADD CONSTRAINT `hinh_anh_tour_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lich_ban_hdv`
--
ALTER TABLE `lich_ban_hdv`
  ADD CONSTRAINT `lich_ban_hdv_ibfk_1` FOREIGN KEY (`hdv_id`) REFERENCES `huong_dan_vien` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lich_khoi_hanh`
--
ALTER TABLE `lich_khoi_hanh`
  ADD CONSTRAINT `lich_khoi_hanh_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lich_trinh_tour`
--
ALTER TABLE `lich_trinh_tour`
  ADD CONSTRAINT `lich_trinh_tour_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `nhat_ky_tour`
--
ALTER TABLE `nhat_ky_tour`
  ADD CONSTRAINT `fk_lich_id` FOREIGN KEY (`lich_id`) REFERENCES `lich_khoi_hanh` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `phan_bo_dich_vu`
--
ALTER TABLE `phan_bo_dich_vu`
  ADD CONSTRAINT `phan_bo_dich_vu_ibfk_1` FOREIGN KEY (`lich_id`) REFERENCES `lich_khoi_hanh` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `phan_bo_dich_vu_ibfk_2` FOREIGN KEY (`ncc_id`) REFERENCES `nha_cung_cap` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `phan_cong_nhan_su`
--
ALTER TABLE `phan_cong_nhan_su`
  ADD CONSTRAINT `phan_cong_nhan_su_ibfk_1` FOREIGN KEY (`lich_id`) REFERENCES `lich_khoi_hanh` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tour`
--
ALTER TABLE `tour`
  ADD CONSTRAINT `fk_tour_hdv` FOREIGN KEY (`hdv_id`) REFERENCES `huong_dan_vien` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tour_ncc`
--
ALTER TABLE `tour_ncc`
  ADD CONSTRAINT `tour_ncc_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tour_ncc_ibfk_2` FOREIGN KEY (`ncc_id`) REFERENCES `nha_cung_cap` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
