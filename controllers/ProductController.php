<?php

class ProductController
{
    public $modelProduct;

    // Constructor to initialize ProductModel
    public function __construct()
    {
        $this->modelProduct = new ProductModel();
    }

    // Home page
    public function Home()
    {
        try {
            // Fetch data from the ProductModel
            $products = $this->modelProduct->getAllProduct();
            $title = "Đây là trang chủ nhé hahaa";
            $thoiTiet = "Hôm nay trời có vẻ là mưa";
            
            // Require the view
            require_once './views/trangchu.php'; 
        } catch (Exception $e) {
            // Handle error if model fails
            echo "Error: " . $e->getMessage();
        }
    }

    // Tour Management page
    public function TourManagement()
    {
        try {
            // Fetch tour data from the ProductModel
            $tours = $this->modelProduct->getAllTours();
            $title = "Quản lý tour";
            
            // Require the view
            require_once './views/tour_management.php';
        } catch (Exception $e) {
            // Handle error if model fails
            echo "Error: " . $e->getMessage();
        }
    }

    // Handle create tour (POST)
    public function CreateTour()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '?act=tour_management');
            return;
        }

        $data = [
            'ma_tour'    => $_POST['ma_tour']    ?? null,
            'ten_tour'   => $_POST['ten_tour']   ?? null,
            'loai_tour'  => $_POST['loai_tour']  ?? null,
            'mo_ta'      => $_POST['mo_ta']      ?? null,
            'chinh_sach' => $_POST['chinh_sach'] ?? null,
        ];

        try {
            $this->modelProduct->createTour($data);
            header('Location: ' . BASE_URL . '?act=tour_management');
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Handle delete tour (GET id)
    public function DeleteTour()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) {
            header('Location: ' . BASE_URL . '?act=tour_management');
            return;
        }
        try {
            $this->modelProduct->deleteTour($id);
            header('Location: ' . BASE_URL . '?act=tour_management');
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>
