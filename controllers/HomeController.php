<?php

class HomeController {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Trang chủ
    public function index() {
        include "views/trangchu.php";
    }
}
