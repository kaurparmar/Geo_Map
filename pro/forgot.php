<?php
$host = "localhost";
$dbname = "geomap";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'];
$secret_phrase = $_POST['secret_phrase'];

// Check if user exists
$sql = "SELECT * FROM users WHERE email=? AND secret_phrase=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $secret_phrase);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    // You can redirect them to a reset page
    session_start();
    $_SESSION['reset_email'] = $email;
    header("Location: reset_password.html");
} else {
    echo "<script>alert('Invalid email or secret phrase.'); window.location.href = 'forgot.html';</script>";
}
?>
