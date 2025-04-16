<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../pro/login.html");
    exit();
}

$host = "localhost";
$dbname = "geomap";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Change Email
if (isset($_POST['change_email'])) {
    $new_email = filter_var($_POST['new_email'], FILTER_SANITIZE_EMAIL);
    if(empty($new_email)){
        echo "<script>alert('Please enter your New Email'); window.history.back();</script>";
        exit();
    }
    if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format'); window.history.back();</script>";
        exit();
    }

    $check = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $check->bind_param("si", $new_email, $user_id);
    $check->execute();

    if ($check->get_result()->num_rows > 0) {
        echo "<script>alert('Email already in use'); window.history.back();</script>";
        exit();
    }

    $update = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
    $update->bind_param("si", $new_email, $user_id);
    $update->execute();

    echo "<script>alert('Email updated successfully'); window.location.href='myaccount.html';</script>";
    exit();
}

// Change Password
if (isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    if(empty($current_password)){
        echo "<script>alert('Please enter your current password'); window.history.back();</script>";
        exit();
    }
    if(empty($new_password)){
        echo "<script>alert('Please enter new password'); window.history.back();</script>";
        exit();
    }
    if (!password_verify($current_password, $user['password'])) {
        echo "<script>alert('Current password is incorrect'); window.history.back();</script>";
        exit();
    }

    $hashed = password_hash($new_password, PASSWORD_DEFAULT);
    $update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $update->bind_param("si", $hashed, $user_id);
    $update->execute();

    echo "<script>alert('Password updated successfully'); window.location.href='myaccount.html';</script>";
    exit();
}

// Delete Account
if (isset($_POST['delete_account'])) {
    $confirm_password = $_POST['confirm_password'];
    if(empty($confirm_password)){
        echo "<script>alert('Please Confirm your password'); window.history.back();</script>";
        exit();
    }
    if (!password_verify($confirm_password, $user['password'])) {
        echo "<script>alert('Incorrect password'); window.history.back();</script>";
        exit();
    }

    $delete = $conn->prepare("DELETE FROM users WHERE id = ?");
    $delete->bind_param("i", $user_id);
    $delete->execute();

    session_unset();
    session_destroy();
    setcookie('remembered_email', '', time() - 3600, "/");
    setcookie('remembered_password', '', time() - 3600, "/");

    echo "<script>alert('Account deleted successfully'); window.location.href='login.html';</script>";
    exit();
}

// Logout
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    setcookie('remembered_email', '', time() - 3600, "/");
    setcookie('remembered_password', '', time() - 3600, "/");

    header("Location: login.html");
    exit();
}
?>
