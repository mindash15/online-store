<?php
session_start();
// if (isset($_SESSION['admin'])) {///
//     session_unset();
//     session_destroy();
// }
// if (!isset($_SESSION['admin'])) {
//     header("Location: admin_login.view.php");
//     exit;
// }/////
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = 0;



if (isset($_POST['clear_cart'])) {
    unset($_SESSION['cart']);
    header('Location: cart.view.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
        <a href="../index.php" class="text-gray-500 hover:text-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
            </svg>
        </a>
    <div class="container mx-auto py-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Your Shopping Cart</h1>

        <?php if (empty($cart)): ?>
            <p class="text-lg text-gray-600">Your cart is empty.</p>
        <?php else: ?>
            <div class="bg-white rounded-lg shadow-md p-6">
                <table class="table-auto w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">Image</th>
                            <th class="px-4 py-2">Product</th>
                            <th class="px-4 py-2">Price</th>
                            <th class="px-4 py-2">Quantity</th>
                            <th class="px-4 py-2">Subtotal</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart as $item): ?>
                            <tr>
                                <td class="px-4 py-2">
                                    <div class="w-40 h-35">
                                        <img src="../uploads/<?= $item['img'] ?>" 
                                             alt="<?= $item['brand'] ?>" 
                                             class="w-full h-full object-cover rounded-lg shadow-md">
                                    </div>
                                </td>
                                <td class="px-4 py-2"><?= $item['brand'] ?> <?= $item['model'] ?></td>
                                <td class="px-4 py-2 text-emerald-500 font-bold"><?= $item['price'] ?>$</td>
                                <td class="px-4 py-2">
                                <form method="post" action="../handlers/cart.handler.php">
    <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
    <input type="hidden" name="size" value="<?= $item['size'] ?>"> <!-- Добавляем размер -->
    
    <button type="submit" name="decrease_quantity">-</button>
    <span><?= $item['quantity'] ?></span>
    <button type="submit" name="increase_quantity">+</button>
</form>

                                </td>
                                <td class="px-4 py-2 text-black font-bold">
                                    <?php 
                                        $subtotal = $item['price'] * $item['quantity'];
                                        $total += $subtotal;
                                        echo "$" . $subtotal;
                                    ?>
                                </td>
                                <td class="px-4 py-2">
                                <form method="post" action="../handlers/cart.handler.php">
    <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
    <input type="hidden" name="size" value="<?= $item['size'] ?>">
    
    <button type="submit" name="remove_item">Remove</button>
</form>
</form>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <p class="text-xl font-bold text-gray-800 mt-6">Total: <span class="text-emerald-500"><?= $total ?>$</span></p>

                <div class="mt-6 flex justify-between">
                    <form method="post">
                        <button type="submit" name="clear_cart" 
                                class="py-3 px-6 bg-rose-500 text-white rounded-lg text-lg font-semibold hover:bg-rose-600">
                            Clear Cart
                        </button>
                    </form>
                    <div class="flex gap-4">
                        <a href="../index.php" class="py-3 px-6 bg-gray-300 text-black rounded-lg text-lg font-semibold hover:bg-gray-400">
                            Continue Shopping
                        </a>
                    </div>
                    <a href="../handlers/checkout.handler.php" 
                       class="py-3 px-6 bg-blue-500 text-white rounded-lg text-lg font-semibold hover:bg-blue-600">
                        Proceed to Checkout
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
