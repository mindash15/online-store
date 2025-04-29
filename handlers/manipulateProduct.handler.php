<?php

session_start();
require_once '../config/db.config.php';
require_once '../models/ProductModel.php';
$pdo = getDbConnection();
$productModel = new ProductModel($pdo);
$productId = isset($_SESSION['productId'])? $_SESSION['productId']: "";
unset($_SESSION['productId']);







    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_product'])) {
        $productId = isset($_POST['product_id']) ? $_POST['product_id'] : null;
    
        if ($productId) {
            $result = $productModel->deleteProductById($productId);
    
            if ($result) {
                // Successful deletion
                header('Location: ../views/dashboard.view.php');
                exit;
            } else {
                // Failed to delete
                $_SESSION['errors'] = ["Failed to delete product with ID: $productId"];
                header('Location: ../views/dashboard.view.php');
                exit;
            }
        } else {
            // No product ID provided
            $_SESSION['errors'] = ["Invalid product ID"];
            header('Location: ../views/dashboard.view.php');
            exit;
        }
    } 


   
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_product'])) {
        // Retrieve POST data
        $productId = $_POST['product_id'];
        $brand = $_POST['brand'];
        $model = $_POST['model'];
        $size = $_POST['size'];
        $color = $_POST['color'];
        $material = $_POST['material'];
        $category = $_POST['category'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
    
        // Validate required fields
        if (empty($productId) || empty($brand) || empty($model) || empty($size) || empty($color) || empty($material)||empty($category) || empty($price)) {
            $_SESSION['errors'] = ['All fields are required.'];
            header("Location: ../views/updateProduct.view.php?product_id=" . $productId);
            exit;
        }
    
        // Perform the update
        $updated = $productModel->updateProduct($productId, $brand, $model, $size, $color, $material,$category, $price,$stock);
    
        if ($updated) {
            header("Location: ../views/dashboard.view.php");
            exit;
        } else {
            $_SESSION['errors'] = ['Failed to update the product.'];
            header("Location: ../views/updateProduct.view.php?product_id=" . $productId);
            exit;
        }
    } else {
        header("Location: ../views/dashboard.view.php");
        exit;
    }



?>