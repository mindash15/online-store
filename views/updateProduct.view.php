<?php
session_start();
require_once '../config/db.config.php';
require_once '../models/ProductModel.php';

$pdo = getDbConnection();
$productModel = new ProductModel($pdo);

// Получаем ID продукта
$productId = isset($_GET['product_id']) ? $_GET['product_id'] : null;

if (!$productId) {
    header('Location: dashboard.view.php'); // Если нет ID, перенаправляем
    exit;
}

// Получаем данные о продукте
$product = $productModel->getProductById($productId);

if (!$product) {
    $_SESSION['errors'] = ['Product not found.'];
    header('Location: dashboard.view.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-100 to-teal-200 flex items-center justify-center min-h-screen">

<div class="bg-white shadow-2xl rounded-2xl p-10 max-w-xl w-full transform transition-transform duration-300 hover:scale-105">
    <h1 class="text-3xl font-semibold text-gray-800 text-center mb-6 tracking-wide">Update Product</h1>

    <form action="../handlers/manipulateProduct.handler.php" method="post" class="space-y-5">
        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

        <div class="flex flex-col space-y-2">
            <label class="text-gray-700 font-semibold" for="brand">Brand</label>
            <input type="text" id="brand" name="brand" value="<?= htmlspecialchars($product['brand']) ?>" 
                   class="w-full border-2 rounded-lg px-5 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm" required>
        </div>

        <div class="flex flex-col space-y-2">
            <label class="text-gray-700 font-semibold" for="model">Model</label>
            <input type="text" id="model" name="model" value="<?= htmlspecialchars($product['model']) ?>" 
                   class="w-full border-2 rounded-lg px-5 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm" required>
        </div>

        <div class="flex flex-col space-y-2">
            <label class="text-gray-700 font-semibold" for="size">Size</label>
            <input type="text" id="size" name="size" value="<?= htmlspecialchars($product['size']) ?>" 
                   class="w-full border-2 rounded-lg px-5 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm" required>
        </div>

        <div class="flex flex-col space-y-2">
            <label class="text-gray-700 font-semibold" for="color">Color</label>
            <input type="text" id="color" name="color" value="<?= htmlspecialchars($product['color']) ?>" 
                   class="w-full border-2 rounded-lg px-5 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm" required>
        </div>

        <div class="flex flex-col space-y-2">
            <label class="text-gray-700 font-semibold" for="material">Material</label>
            <input type="text" id="material" name="material" value="<?= htmlspecialchars($product['material']) ?>" 
                   class="w-full border-2 rounded-lg px-5 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm" required>
        </div>

        <div class="flex flex-col space-y-2">
            <label class="text-gray-700 font-semibold" for="category">Category</label>
            <input type="text" id="category" name="category" value="<?= htmlspecialchars($product['category']) ?>" 
                   class="w-full border-2 rounded-lg px-5 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm" required>
        </div>

        <div class="flex flex-col space-y-2">
            <label class="text-gray-700 font-semibold" for="stock">Stock</label>
            <input type="text" id="stock" name="stock" value="<?= htmlspecialchars($product['stock']) ?>" 
                   class="w-full border-2 rounded-lg px-5 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm" required>
        </div>

        <div class="flex flex-col space-y-2">
            <label class="text-gray-700 font-semibold" for="price">Price</label>
            <input type="number" id="price" name="price" value="<?= htmlspecialchars($product['price']) ?>" 
                   class="w-full border-2 rounded-lg px-5 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm" required>
        </div>

        <!-- Кнопки -->
        <div class="flex justify-between mt-6">
            <a href="dashboard.view.php" class="px-8 py-3 bg-gray-600 text-white rounded-lg font-medium transition duration-300 hover:bg-gray-700 focus:ring-2 focus:ring-gray-400">
                Cancel
            </a>
            <button type="submit" name="update_product" 
                    class="px-8 py-3 bg-indigo-600 text-white rounded-lg font-medium transition duration-300 hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-400">
                Save Changes
            </button>
        </div>
    </form>
</div>

</body>
</html>
