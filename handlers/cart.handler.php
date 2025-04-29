<?php
session_start();
require_once '../config/db.config.php';
require_once '../models/ProductModel.php';

$pdo = getDbConnection();
$productModel = new ProductModel($pdo);

// Добавление товара в корзину
if (isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];
    $selectedSize = $_POST['size']; // Получаем выбранный размер
    $product = $productModel->getProductById($productId);
    
    if (!$product) {
        $_SESSION['errors'] = "Product not found!";
        header('Location: ../views/product.view.php?id=' . $productId);
        exit;
    }

    if ($product['stock'] < 1) {
        $_SESSION['errors'] = "Product is out of stock!";
        header('Location: ../views/product.view.php?id=' . $productId);
        exit();
    }

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $cartKey = $productId . "_" . $selectedSize; // Уникальный ключ (ID + размер)

    if (isset($_SESSION['cart'][$cartKey])) {
        $_SESSION['cart'][$cartKey]['quantity'] += 1;
    } else {
        $_SESSION['cart'][$cartKey] = [
            'id' => $product['id'],
            'img' => $product['img'],
            'brand' => $product['brand'],
            'model' => $product['model'],
            'size' => $selectedSize, // Сохраняем размер
            'color' => $product['color'],
            'price' => $product['price'],
            'quantity' => 1
        ];
    }

    header('Location: ../views/cart.view.php');
    exit;
}

// Увеличение количества товара
if (isset($_POST['increase_quantity'])) {
    $cartKey = $_POST['product_id'] . "_" . $_POST['size']; // Формируем правильный ключ

    if (isset($_SESSION['cart'][$cartKey])) {
        $_SESSION['cart'][$cartKey]['quantity'] += 1;
    }

    header('Location: ../views/cart.view.php');
    exit;
}

// Уменьшение количества товара
if (isset($_POST['decrease_quantity'])) {
    $cartKey = $_POST['product_id'] . "_" . $_POST['size'];

    if (isset($_SESSION['cart'][$cartKey])) {
        if ($_SESSION['cart'][$cartKey]['quantity'] > 1) {
            $_SESSION['cart'][$cartKey]['quantity'] -= 1;
        } else {
            unset($_SESSION['cart'][$cartKey]); // Если количество стало 0, удаляем товар
        }
    }

    header('Location: ../views/cart.view.php');
    exit;
}

// Удаление одного товара из корзины
if (isset($_POST['remove_item'])) {
    $cartKey = $_POST['product_id'] . "_" . $_POST['size'];

    if (isset($_SESSION['cart'][$cartKey])) {
        unset($_SESSION['cart'][$cartKey]);
    }

    header('Location: ../views/cart.view.php');
    exit;
}

// Очистка всей корзины
if (isset($_POST['clear_cart'])) {
    unset($_SESSION['cart']);
    header('Location: ../views/cart.view.php');
    exit;
}
