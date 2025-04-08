<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Geo Map</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex justify-center items-center h-screen bg-green-100">
    <div class="text-center">
        <h1 class="text-3xl font-bold text-green-800">Welcome, <?php echo $_SESSION['email']; ?>!</h1>
        <p class="mt-4 text-gray-600">You are now logged in.</p>
        <a href="logout.php" class="mt-6 inline-block px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Logout</a>
    </div>
</body>
</html>
