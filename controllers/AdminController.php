<?php
class AdminController
{
    public function Home()
    {
        // Prepare code contents to display in the admin view
        $productControllerSource = @file_get_contents('./controllers/ProductController.php') ?: '';
        $productModelSource = @file_get_contents('./models/ProductModel.php') ?: '';
        $indexSource = @file_get_contents('./index.php') ?: '';

        // Try to extract only the routing match block from index.php
        $routingSource = $indexSource;
        if (preg_match('/match\\s*\\(\\s*\\$act\\s*\\)\\s*\\{[\\s\\S]*?\\};/m', $indexSource, $matches)) {
            $routingSource = $matches[0];
        }

        // Render the view
        require_once './views/index.php';
    }
}