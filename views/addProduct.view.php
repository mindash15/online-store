<?php
session_start();
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
$success = isset($_SESSION['success']) ? $_SESSION['success'] : "";
unset($_SESSION['errors']);
unset($_SESSION['success']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
<a href="dashboard.view.php" class="text-gray-500 hover:text-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
            </svg>
        </a>

<!-- Container for the form -->
<div class="container mx-auto px-4 py-8">
    <form action="../handlers/addProduct.handler.php" class="max-w-lg mx-auto bg-white p-8 rounded-xl shadow-lg space-y-6" method="post" enctype="multipart/form-data">
        
        <!-- Form Header -->
        <h1 class="text-2xl font-semibold text-center text-gray-800">Add New Product</h1>

        <!-- Form Fields -->
        <div class="space-y-4">
            <!-- Brand -->
            <div class="flex flex-col">
                <label class="text-lg font-medium text-gray-700">Brand</label>
                <input type="text" name="brand" class="border-2 border-gray-300 py-3 px-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter brand" value="<?= isset($_POST['brand']) ? $_POST['brand'] : '' ?>">
            </div>

            <!-- Model -->
            <div class="flex flex-col">
                <label class="text-lg font-medium text-gray-700">Model</label>
                <input type="text" name="model" class="border-2 border-gray-300 py-3 px-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter model" value="<?= isset($_POST['model']) ? $_POST['model'] : '' ?>">
            </div>

            <!-- Size -->
            <div class="flex flex-col">
                <label class="text-lg font-medium text-gray-700">Size (EU)</label>
                <input type="text" name="size" class="border-2 border-gray-300 py-3 px-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter size" value="<?= isset($_POST['size']) ? $_POST['size'] : '' ?>">
            </div>

            <!-- Color -->
            <div class="flex flex-col">
                <label class="text-lg font-medium text-gray-700">Color</label>
                <input type="text" name="color" class="border-2 border-gray-300 py-3 px-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter color" value="<?= isset($_POST['color']) ? $_POST['color'] : '' ?>">
            </div>

            <!-- Material -->
            <div class="flex flex-col">
                <label class="text-lg font-medium text-gray-700">Material</label>
                <input type="text" name="material" class="border-2 border-gray-300 py-3 px-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter material" value="<?= isset($_POST['material']) ? $_POST['material'] : '' ?>">
            </div>

            <!-- Category -->
            <div class="flex flex-col">
                <label class="text-lg font-medium text-gray-700">Category</label>
                <input type="text" name="category" class="border-2 border-gray-300 py-3 px-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter category" value="<?= isset($_POST['category']) ? $_POST['category'] : '' ?>">
            </div>

            <!-- Price -->
            <div class="flex flex-col">
                <label class="text-lg font-medium text-gray-700">Price</label>
                <input type="text" name="price" class="border-2 border-gray-300 py-3 px-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter price" value="<?= isset($_POST['price']) ? $_POST['price'] : '' ?>">
            </div>

            <!--Stock-->
            <div class="flex flex-col">
                <label class="text-lg font-medium text-gray-700">Stock</label>
                <input type="number" name="stock" class="border-2 border-gray-300 py-3 px-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter stock quantity" value="<?= isset($_POST['stock']) ? $_POST['stock'] : '' ?>">
            </div>

            <!-- Image Upload -->
            <div class="flex flex-col">
                <label class="text-lg font-medium text-gray-700">Add Image</label>
                <input type="file" name="img" accept="image/*" class="border-2 border-gray-300 py-3 px-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full bg-black text-white py-4 rounded-xl text-lg font-semibold hover:bg-gray-800 transition-colors">Add Product</button>

        <!-- Error and Success Messages -->
        <?php if ($errors): ?>
            <div class="mt-4 bg-red-100 text-red-600 p-4 rounded-xl">
                <ul class="list-disc pl-5">
                    <?php foreach ($errors as $error): ?>
                        <li class="font-bold"><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php elseif ($success): ?>
            <div class="mt-4 bg-green-100 text-green-600 p-4 rounded-xl">
                <p class="font-bold"><?= $success ?></p>
            </div>
        <?php endif; ?>
    </form>
</div>

</body>
</html>
