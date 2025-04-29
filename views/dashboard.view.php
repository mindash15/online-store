<?php
require_once '../config/db.config.php';
require_once '../models/ProductModel.php';

$pdo = getDbConnection();
$productModel = new ProductModel($pdo);

$filters = [];
$params = [];




// Фильтрация по бренду
if (!empty($_GET['brand'])) {
    $filters[] = "brand LIKE ?";
    $params[] = "%{$_GET['brand']}%";
}

if (!empty($_GET['size'])) {
    $filters[] = "size LIKE ?";
    $params[] = "%{$_GET['size']}%";
}

// Фильтрация по модели
if (!empty($_GET['model'])) {
    $filters[] = "model LIKE ?";
    $params[] = "%{$_GET['model']}%";
}

// Фильтрация по категории
if (!empty($_GET['category'])) {
    $filters[] = "category LIKE ?";
    $params[] = "%{$_GET['category']}%";
}

// Фильтрация по цене
if (!empty($_GET['min_price'])) {
    $filters[] = "price >= ?";
    $params[] = $_GET['min_price'];
}
if (!empty($_GET['max_price'])) {
    $filters[] = "price <= ?";
    $params[] = $_GET['max_price'];
}

// Формирование SQL-запроса
$query = "SELECT * FROM products";
if (!empty($filters)) {
    $query .= " WHERE " . implode(" AND ", $filters);
}
$query .= " ORDER BY id DESC"; // Сортировка по дате добавления (по убыванию)

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);


session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.view.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-screen-xl mx-auto px-4 py-6">

        <!-- Navbar -->
        <nav class="flex items-center justify-between py-4 bg-white shadow-md px-4 sm:px-6">
            <h1 class="text-xl sm:text-3xl text-gray-800">Admin Dashboard</h1>
            <div class="flex items-center gap-4 sm:gap-8 text-sm sm:text-lg">
                <a href="addProduct.view.php" class="text-indigo-600 hover:text-indigo-800 transition">Add Product</a>
                <a href="logout.php" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700 transition">Logout</a>

            </div>
        </nav>

        <!-- Форма поиска -->
        <div class="bg-white p-4 shadow-md rounded-lg mt-6">
            <form method="GET" action="" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                <input type="text" name="brand" placeholder="Brand" class="border p-2 rounded" value="<?= $_GET['brand'] ?? '' ?>">
                <input type="text" name="model" placeholder="Model" class="border p-2 rounded" value="<?= $_GET['model'] ?? '' ?>">
                <input type="text" name="category" placeholder="Category" class="border p-2 rounded" value="<?= $_GET['category'] ?? '' ?>">
                <input type="text" name="size" placeholder="Size" class="border p-2 rounded" value="<?= $_GET['size'] ?? '' ?>">
                <input type="number" name="min_price" placeholder="Min Price" class="border p-2 rounded" value="<?= $_GET['min_price'] ?? '' ?>">
                <input type="number" name="max_price" placeholder="Max Price" class="border p-2 rounded" value="<?= $_GET['max_price'] ?? '' ?>">
                <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded">Filter</button>
                <a href="dashboard.view.php" class="bg-gray-300 text-black px-4 py-2 rounded text-center">Reset</a>
            </form>
        </div>

        <!-- Product Table(прокрутка) -->
        <div class="overflow-x-auto mt-6">
            <table class="w-full min-w-[600px] border-collapse bg-white shadow-md rounded-lg">
                <thead>
                    <tr class="bg-gray-800 text-white text-sm sm:text-base">
                        <th class="p-3">Image</th>
                        <th class="p-3">Brand</th>
                        <th class="p-3 hidden sm:table-cell">Model</th>
                        <th class="p-3 hidden md:table-cell">Size</th>
                        <th class="p-3 hidden md:table-cell">Color</th>
                        <th class="p-3 hidden md:table-cell">Material</th>
                        <th class="p-3 hidden lg:table-cell">Category</th>
                        <th class="p-3">Price</th>
                        <th class="p-3 hidden sm:table-cell">Stock</th>
                        <th class="p-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($products): ?>
                        <?php foreach($products as $product): ?>
                            <tr class="border-t hover:bg-gray-50 text-center">
                                <td class="p-3">
                                    <a href="product.view.php?id=<?=$product['id'];?>">
                                        <img src="../uploads/<?=$product['img'] ?>" alt="<?=$product['brand']?>" class="w-16 h-16 object-cover rounded-lg shadow-md">
                                    </a>
                                </td>
                                <td class="p-3"><?=$product['brand']?></td>
                                <td class="p-3 hidden sm:table-cell"><?=$product['model']?></td>
                                <td class="p-3 hidden md:table-cell"><?=$product['size']?></td>
                                <td class="p-3 hidden md:table-cell"><?=$product['color']?></td>
                                <td class="p-3 hidden md:table-cell"><?=$product['material']?></td>
                                <td class="p-3 hidden lg:table-cell"><?=$product['category']?></td>
                                <td class="p-3 text-green-500 font-semibold"><?=$product['price']?>$</td>
                                <td class="p-3 hidden sm:table-cell"><?= $product['stock'] ?></td>
                                <td class="p-3">
                                    <div class="flex gap-3 justify-center items-center">
                                        <a href="updateProduct.view.php?product_id=<?= $product['id'] ?>" class="text-blue-500 hover:text-blue-700">
                                            Edit
                                        </a>
                                        <form action="../handlers/manipulateProduct.handler.php" method="post" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                            <input type="hidden" name="product_id" value="<?= $product['id']; ?>">
                                            <button type="submit" name="delete_product" class="text-red-500 hover:text-red-700">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="p-4 text-center text-rose-500 font-bold">No products found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
