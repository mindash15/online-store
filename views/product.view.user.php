<?php
session_start();
// if (isset($_SESSION['admin'])) {////
//     session_unset();
//     session_destroy();
// }
// if (!isset($_SESSION['admin'])) {//////
//     header("Location: admin_login.view.php");
//     exit;
// }
$productId = isset($_GET['id']) ? $_GET['id'] : "";
 
require_once '../config/db.config.php';
require_once '../models/ProductModel.php';

$pdo = getDbConnection();
$productModel = new ProductModel($pdo);
$product = $productModel->getProductById($productId);



$_SESSION['product'] = $product;
$relatedProducts = $productModel->getRelatedProducts($product['category'], $product['id']);
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : "";
$availableSizes = $productModel->getAvailableSizesByModel($product['model']);


// unset($_SESSION['errors']);
if (!is_array($errors)) {
    $errors = [$errors]; // Преобразуем строку в массив
}
unset($_SESSION['errors']);

if(!$product)
{
    header('Location: dashboard.view.php');
    exit;
}
else{
    
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
<body class="bg-gray-100">
    <div class="container mx-auto py-6">
       
        <nav class="flex items-center py-3 justify-between px-4">
    <a href="../index.php" class="text-gray-500 hover:text-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
            </svg>
        </a>
   
            <!-- <h1 class="text-2xl text-gray-800">ADMIN DASHBOARD</h1> -->
            
               
    </nav>


        
        <section class="bg-white rounded-lg shadow-lg p-6 mt-6 flex flex-col md:flex-row items-start gap-10">
           
            <div class="w-full md:w-[40%] h-auto">
                <img src="../uploads/<?= $product['img'] ?>" alt="Product Image" class="w-full h-auto object-cover rounded-lg shadow-md">
            </div>

            
            <div class="flex flex-col gap-6 w-full md:w-[60%]">
                <h1 class="text-2xl font-bold text-gray-800"><?= $product['brand'] ?> <?= $product['model'] ?></h1>
                <p class="text-lg text-gray-600">
                    <span class="font-semibold">Size:</span> <?= $product['size'] ?>
                </p>
                <p class="text-lg text-gray-600">
                    <span class="font-semibold">Color:</span> <?= $product['color'] ?>
                </p>
                <p class="text-lg text-gray-600">
                    <span class="font-semibold">Material:</span> <?= $product['material'] ?>
                </p>
                <p class="text-lg text-gray-600">
                    <span class="font-semibold">Category:</span> <?= $product['category'] ?>
                </p>
                <p class="text-2xl text-emerald-500 font-bold">$<?= $product['price'] ?></p>

                <!-- Action Buttons -->
                <div class="flex gap-4 mt-4">
                <form action="../handlers/cart.handler.php" method="post">
    <input type="hidden" name="product_id" value="<?=$product['id']?>">

    <!-- Выбор размера -->
    <div class="flex flex-col space-y-2">
        <label class="text-gray-700 font-semibold">Choose Size:</label>
        <select name="size" class="border rounded-lg py-2 px-4 focus:ring-2 focus:ring-indigo-500">
            <?php foreach ($availableSizes as $size): ?>
                <option value="<?= $size ?>" <?= $size == $product['size'] ? 'selected' : '' ?>><?= $size ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Кнопка добавления в корзину -->
    <button type="submit" name="add_to_cart" 
            class="py-3 px-6 bg-blue-500 text-white rounded-lg text-lg font-semibold hover:bg-blue-600 mt-4">
        Buy Now
    </button>
</form>

                   
                    
                    <!-- <a href="#" 
                       class="py-3 px-6 bg-blue-500 text-white rounded-lg text-lg font-semibold hover:bg-blue-600">
                        Buy Now
                    </a> -->
                </div>
            </div>
        </section>

      <!-- Related Products Section -->
<section class="mt-12">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Related Products</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php if (!empty($relatedProducts)): ?>
            <?php foreach ($relatedProducts as $related): ?>
                <div class="bg-white border border-gray-300 rounded-lg shadow-md p-4">
                    <a href="./product.view.user.php?id=<?= $related['id']; ?>" class="block h-40 mx-auto mb-4">
                        <img src="../uploads/<?= $related['img'] ?>" class="h-full w-full object-cover rounded-lg" alt="Related product">
                    </a>
                    <p class="text-lg font-bold text-gray-700 truncate"><?= $related['brand'] ?> <?= $related['model'] ?></p>
                    <p class="text-lg font-semibold text-emerald-500 mt-2"><?= $related['price'] ?>$</p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-gray-500 text-center">No related products found.</p>
        <?php endif; ?>
    </div>
</section>

    </div>
</body>
</html>
