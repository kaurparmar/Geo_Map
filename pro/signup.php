<?php
$host = "localhost";
$dbname = "geomap";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$pass = $_POST['password'];
$hashed_password = password_hash($pass, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (email, password) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $hashed_password);

if ($stmt->execute()) {
  header("Location: login.html");
} else {
  echo "Signup failed. Email might already exist.";
}

$stmt->close();
$conn->close();
?>
