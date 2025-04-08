<?php
session_start();

$host = "localhost";
$dbname = "geomap";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$pass = $_POST['password'];

$sql = "SELECT * FROM users WHERE email=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($pass, $user['password'])) {
  $_SESSION['user_id'] = $user['id'];
  header("Location: dashboard.html");
} else {
  echo "<script>alert('Invalid email or password'); window.location.href = 'login.html';</script>";
}
?>
