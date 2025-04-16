<?php
session_start();

if (!isset($_SESSION['reset_email'])) {
    die("Unauthorized access.");
}

$host = "localhost";
$dbname = "geomap";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];
$email = $_SESSION['reset_email'];

if ($new_password !== $confirm_password) {
    echo "<script>alert('Passwords do not match.'); window.location.href = 'reset_password.html';</script>";
    exit();
}

// Hash the password
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

// Update password in DB
$sql = "UPDATE users SET password=? WHERE email=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $hashed_password, $email);
if ($stmt->execute()) {
    // Clear session and redirect
    session_destroy();
    echo "<script>alert('Password reset successful.'); window.location.href = 'index.html';</script>";
} else {
    echo "<script>alert('Error resetting password. Please try again.'); window.location.href = 'reset_password.html';</script>";
}
?>
