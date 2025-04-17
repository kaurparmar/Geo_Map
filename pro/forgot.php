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

$email = $_POST['email'];
$secret_phrase = $_POST['secret_phrase'];

// Check if user exists
$sql = "SELECT secret_phrase FROM users WHERE email=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();



if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hashed_phrase = $row['secret_phrase'];

    if (password_verify($secret_phrase, $hashed_phrase)) {
        // Secret phrase is correct
        $_SESSION['reset_email'] = $email;
        header("Location: resetpassword.html");
        exit;
    } else {
        // Invalid secret phrase
        echo "<script>alert('Invalid email or secret phrase.'); window.location.href = 'forgot.html';</script>";
    }
} 
else {
    echo "<script>alert('Invalid email or secret phrase.'); window.location.href = 'forgot.html';</script>";
}
?>
