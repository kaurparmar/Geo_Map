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
$sql = "SELECT secret_phrase FROM users WHERE email=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if (password_verify($secret_phrase, $result)) {
    // You can redirect them to a reset page
    $_SESSION['reset_email'] = $email;
    header("Location: resetpassword.html");
} else {
    echo "<script>alert('Invalid email or secret phrase.'); window.location.href = 'forgot.html';</script>";
}
?>
