<?php
session_start();
require_once '../controllers/product.controller.php';
require_once '../config/db.config.php';
require_once '../models/ProductModel.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $brand = sanitizeInput($_POST['brand']);
    $model = sanitizeInput($_POST['model']);
    $size = sanitizeInput($_POST['size']);
    $color = sanitizeInput($_POST['color']);
    $material = sanitizeInput($_POST['material']);
    $category = sanitizeInput($_POST['category']);
    $price = sanitizeInput($_POST['price']);
    $stock = isset($_POST['stock']) ? (int)sanitizeInput($_POST['stock']) : 0;
    $img = $_FILES['img'];

    $errors = [];

    $errors = array_merge($errors, validateBrand($brand));
    $errors = array_merge($errors, validateModel($model));
    $errors = array_merge($errors, validateSize($size));
    $errors = array_merge($errors, validateColor($color));
    $errors = array_merge($errors, validateMaterial($material));
    $errors = array_merge($errors, validateCategory($category));
    $errors = array_merge($errors, validatePrice($price));

    $imgPath = handleUploadImage($img, $errors);

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header('Location: ../views/addProduct.view.php');
        exit();
    } else {
        $pdo = getDbConnection();
        $productModel = new ProductModel($pdo);
        $productModel->insertProduct($brand, $model, $size, $color, $material, $category, $price, $imgPath, $stock);

        $_SESSION['success'] = "Product added successfully";
        header('Location: ../views/dashboard.view.php');
        exit();
    }
} else {
    header('Location: ../index.php');
    exit();
}
?>
