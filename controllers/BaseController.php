<?php
// controllers/BaseController.php
class BaseController
{
    protected function render($view, $data = [])
    {
        extract($data);
        $viewPath = PATH_ROOT . "/views/" . $view . ".php";

        if (file_exists($viewPath)) {
            require_once PATH_ROOT . "/views/header.php";
            require_once $viewPath;
            require_once PATH_ROOT . "/views/footer.php";
        } else {
            echo "Không tìm thấy view: " . $viewPath;
        }
    }
}
