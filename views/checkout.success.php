<?php
session_start();
if (isset($_SESSION['admin'])) {////
    session_unset();
    session_destroy();
}
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.view.php");
    exit;
}
$successMessage = isset($_SESSION['success']) ? $_SESSION['success'] : "Your order was processed successfully";
unset($_SESSION['success']);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Success</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto py-6 text-center">
        <h1 class="text-2xl font-bold text-gray-800"><?= $successMessage ?></h1>
        <a href="../index.php" class="mt-6 inline-block py-3 px-6 bg-blue-500 text-white rounded-lg text-lg font-semibold hover:bg-blue-600">
            Continue Shopping
        </a>
    </div>
</body>
</html>
