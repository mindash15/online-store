<?php
require_once './config/db.config.php';
require_once './models/ProductModel.php';

$pdo = getDbConnection();
$productModel = new ProductModel($pdo);

$filters = [];
$params = [];

// Фильтрация
if (!empty($_GET['brand'])) {
    $filters[] = "brand LIKE ?";
    $params[] = "%{$_GET['brand']}%";
}
if (!empty($_GET['model'])) {
    $filters[] = "model LIKE ?";
    $params[] = "%{$_GET['model']}%";
}
if (!empty($_GET['color'])) {
    $filters[] = "color LIKE ?";
    $params[] = "%{$_GET['color']}%";
}
if (!empty($_GET['material'])) {
    $filters[] = "material LIKE ?";
    $params[] = "%{$_GET['material']}%";
}
if (!empty($_GET['category'])) {
    $filters[] = "category LIKE ?";
    $params[] = "%{$_GET['category']}%";
}
if (!empty($_GET['min_price'])) {
    $filters[] = "price >= ?";
    $params[] = $_GET['min_price'];
}
if (!empty($_GET['max_price'])) {
    $filters[] = "price <= ?";
    $params[] = $_GET['max_price'];
}

// Определяет сортировку
$orderBy = "id DESC"; // По умолчанию сортировка по ID
if (!empty($_GET['sort'])) {
    if ($_GET['sort'] === 'price_asc') {
        $orderBy = "price ASC";
    } elseif ($_GET['sort'] === 'price_desc') {
        $orderBy = "price DESC";
    }
}

// Запрос на получение продуктов
$query = "SELECT * FROM products";
if (!empty($filters)) {
    $query .= " WHERE " . implode(" AND ", $filters);
}
$query .= " GROUP BY model ORDER BY " . $orderBy; // Добавлена сортировка

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);


// session_start();
// if (isset($_SESSION['admin'])) {/////
//     session_unset();
//     session_destroy();
// }

// if (!isset($_SESSION['admin'])) {//////
//     header("Location: ./views/admin_login.view.php");
//     exit;
// }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User interface</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/sidebar.css">
</head>
<body class="relative">

    <div class="container mx-auto">
        <nav class="flex items-center justify-between py-5 px-4">
            <button id="filterBtn" class="text-gray-600 hover:text-black">☰ Filters</button>
            <h1 class="text-3xl font-extrabold text-gray-800 text-center flex-grow 
           bg-gradient-to-r from-blue-500 to-indigo-500 
           text-transparent bg-clip-text drop-shadow-lg">
    Online Store
</h1>

            <a href="./views/cart.view.php" class="relative text-stone-600 hover:text-black">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 7h14M9 21h6"/>
                </svg>
            </a>
        </nav>

        <!-- Боковая панель фильтрации -->
        <div class="sidebar" id="sidebar">
            <button id="closeBtn" class="text-red-500 text-lg font-bold mb-4">✖ Close</button>
            <form method="GET" action="" class="flex flex-col gap-4">
                <input type="text" name="brand" placeholder="Brand" class="border p-2 rounded" value="<?= $_GET['brand'] ?? '' ?>">
                <input type="text" name="model" placeholder="Model" class="border p-2 rounded" value="<?= $_GET['model'] ?? '' ?>">
                <input type="text" name="color" placeholder="Color" class="border p-2 rounded" value="<?= $_GET['color'] ?? '' ?>">
                <input type="text" name="material" placeholder="Material" class="border p-2 rounded" value="<?= $_GET['material'] ?? '' ?>">
                <input type="text" name="category" placeholder="Category" class="border p-2 rounded" value="<?= $_GET['category'] ?? '' ?>">
                <input type="number" name="min_price" placeholder="Min Price" class="border p-2 rounded" value="<?= $_GET['min_price'] ?? '' ?>">
                <input type="number" name="max_price" placeholder="Max Price" class="border p-2 rounded" value="<?= $_GET['max_price'] ?? '' ?>">

                <!-- Сортировка -->
                <select name="sort" class="border p-2 rounded">
                    <option value="">Sort by</option>
                    <option value="price_asc" <?= ($_GET['sort'] ?? '') === 'price_asc' ? 'selected' : '' ?>>Price: Low to High</option>
                    <option value="price_desc" <?= ($_GET['sort'] ?? '') === 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
                </select>

                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Filter</button>
            </form>
        </div>

        <main class="container mx-auto mt-8">
            <?php if ($products): ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <?php foreach ($products as $product): ?>
                        <div class="bg-white border border-gray-300 rounded-lg shadow-md p-4">
                            <a href="./views/product.view.user.php?id=<?= $product['id']; ?>" class="block h-40 mx-auto mb-4">
                                <img src="./uploads/<?= $product['img'] ?>" class="h-full w-full object-cover" alt="Product image">
                            </a>
                            <p class="text-lg font-bold text-gray-700 truncate"><?= $product['brand'] ?> <?= $product['model'] ?></p>
                            <p class="text-lg font-semibold text-emerald-500 mt-2"><?= $product['price'] ?>$</p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-rose-500 font-bold text-center">No products found</p>
            <?php endif; ?>
        </main>
    </div>

    <script src="scripts/scripts.js"></script>
</body>
</html>
