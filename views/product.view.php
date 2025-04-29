<?php
session_start();
$productId = isset($_GET['id']) ? $_GET['id'] : "";

require_once '../config/db.config.php';
require_once '../models/ProductModel.php';

$pdo = getDbConnection();
$productModel = new ProductModel($pdo);
$product = $productModel->getProductById($productId);

$_SESSION['product'] = $product;
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : "";
if (!is_array($errors)) {
    $errors = [$errors]; // Convert string to array
}
unset($_SESSION['errors']);

if (!$product) {
    header('Location: dashboard.view.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product View</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

<!-- Container -->
<div class="container mx-auto px-4 py-8">

    <!-- Navigation Bar -->
    <nav class="flex items-center py-3 justify-between px-6 bg-white shadow-lg mb-6 rounded-lg">
        <a href="dashboard.view.php" class="text-gray-500 hover:text-gray-700 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
            </svg>
            <span class="ml-2">Back to Dashboard</span>
        </a>
        <div class="flex items-center gap-6 text-lg">
            <a href="addProduct.view.php" class="text-stone-600 hover:text-black">Add Product</a>
        </div>
    </nav>

    <!-- Product Info Section -->
    <section class="flex flex-col md:flex-row gap-12 items-center">

        <!-- Product Image -->
        <div class="w-full md:w-[45%]">
            <img src="../uploads/<?= $product['img']?>" alt="Product Image" class="w-full h-auto object-cover rounded-xl shadow-xl hover:scale-105 transition-transform duration-300">
        </div>

        <!-- Product Details -->
        <div class="w-full md:w-[50%]">
            <h1 class="text-3xl font-semibold text-gray-800 mb-6"><?= $product['model'] ?></h1>

            <div class="space-y-4">
                <p class="text-lg text-gray-700">
                    <span class="font-semibold">Brand:</span> <?= $product['brand'] ?>
                </p>
                <p class="text-lg text-gray-700">
                    <span class="font-semibold">Size:</span> <?= $product['size'] ?>
                </p>
                <p class="text-lg text-gray-700">
                    <span class="font-semibold">Color:</span> <?= $product['color'] ?>
                </p>
                <p class="text-lg text-gray-700">
                    <span class="font-semibold">Material:</span> <?= $product['material'] ?>
                </p>
                <p class="text-lg text-gray-700">
                    <span class="font-semibold">Category:</span> <?= $product['category'] ?>

                </p>

                <p class="text-lg text-gray-700">
                    <span class="font-semibold">In stock:</span> <?= $product['stock'] ?>
                </p>
                <p class="text-xl font-bold text-emerald-500">
                    <span class="font-semibold">Price:</span> $<?= number_format($product['price'], 2) ?>
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-6 mt-8">
                <a href="updateProduct.view.php?product_id=<?= $product['id'] ?>" 
                   class="py-3 px-6 bg-yellow-500 text-white rounded-lg text-lg font-semibold hover:bg-yellow-600 transition-colors">
                    Update
                </a>

                <form action="../handlers/manipulateProduct.handler.php" method="post">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <button class="py-3 px-6 bg-rose-500 text-white rounded-lg text-lg font-semibold hover:bg-rose-600 transition-colors" name="delete_product" 
                            onClick="return confirm('Are you sure you want to delete this product?');">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Error Messages -->
    <?php if (!empty($errors)) : ?>
        <div class="mt-6">
            <?php foreach ($errors as $error) : ?>
                <p class="text-rose-500 font-bold"><?= $error ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

</body>
</html>
