<?php
session_start();
require_once '../config/db.config.php';
require_once '../models/OrderModel.php';
require_once '../models/ProductModel.php';

// Проверяем, есть ли товары в корзине
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    $_SESSION['errors'] = "Your cart is empty. Add items before checking out.";
    header('Location: ../views/cart.view.php');
    exit;
}

// Подключаем БД
$pdo = getDbConnection();
$orderModel = new OrderModel($pdo);
$productModel = new ProductModel($pdo);

$cart = $_SESSION['cart'];
$totalPrice = 0;

// Считаем общую сумму заказа
foreach ($cart as $item) {
    $totalPrice += $item['price'] * $item['quantity'];
}

// Симулируем user_id (замени на реальную авторизацию)
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;

try {
    $pdo->beginTransaction(); // Начинаем транзакцию

    // Создаем заказ
    $orderId = $orderModel->createOrder($userId, $totalPrice);

    // Обрабатываем каждый товар в корзине
    foreach ($cart as $item) {
        // Проверяем наличие товара на складе
        $product = $productModel->getProductById($item['id']);
        if (!$product || (isset($product['stock']) && $product['stock'] < $item['quantity'])) {
            throw new Exception("Not enough stock for product: {$item['brand']} {$item['model']}");
        }

        // Уменьшаем количество на складе
        $newStock = $product['stock'] - $item['quantity'];
        $productModel->updateStock($item['id'], $newStock);

        // Добавляем товар в `order_items`
        $orderModel->addOrderItem($orderId, $item['id'], $item['quantity'], $item['price']);
    }

    $pdo->commit(); // Фиксируем изменения

    // Очищаем корзину
    unset($_SESSION['cart']);
    $_SESSION['success'] = "Checkout successful! Your order has been placed.";
    header('Location: ../views/checkout.success.php');
    exit;

} catch (Exception $e) {
    $pdo->rollBack(); // Откатываем изменения, если есть ошибка
    $_SESSION['errors'] = "Checkout failed: " . $e->getMessage();
    header('Location: ../views/cart.view.php');
    exit;
}

// // 👉 **Добавим отладку перед редиректом**
// print_r($_SESSION);
// echo "<br>Redirecting to cart.view.php...";
// die(); // Остановим выполнение, чтобы увидеть ошибку

// header('Location: ../views/cart.view.php');
?>

 