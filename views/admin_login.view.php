<?php
session_start();
require_once '../config/db.config.php';

$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pdo = getDbConnection();
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin'] = $admin['id'];
        header("Location: dashboard.view.php");
        exit;
    } else {
        $errorMessage = " Invalid credentials!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">

    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">Admin Login</h2>

        <?php if ($errorMessage): ?>
            <p class="text-red-500 text-center mb-4"><?= $errorMessage ?></p>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-gray-700">Username</label>
                <input type="text" name="username" class="w-full p-2 border rounded-md focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div>
                <label class="block text-gray-700">Password</label>
                <input type="password" name="password" class="w-full p-2 border rounded-md focus:ring-2 focus:ring-blue-500" required>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition">
                Login
            </button>
        </form>
    </div>

</body>
</html>
