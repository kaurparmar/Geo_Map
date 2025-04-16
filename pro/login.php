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

// Check if "Remember Me" cookies are set
if (isset($_COOKIE['remembered_email']) && isset($_COOKIE['remembered_password'])) {
    $email = $_COOKIE['remembered_email'];
    $hashed_password = $_COOKIE['remembered_password'];

    // Check if the hashed password matches the stored one in the database
    $sql = "SELECT * FROM users WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($hashed_password, $user['password'])) {
        // Set the session if the user is authenticated
        $_SESSION['user_id'] = $user['id'];
        header("Location: dashboard.html");
        exit();
    }
}

// For regular login
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

    // If the "Remember Me" checkbox is checked
    if (isset($_POST['remember'])) {
        // Set cookies for auto-login, the cookie expires in 30 days
        setcookie('remembered_email', $email, time() + (30 * 24 * 60 * 60), "/"); // 30 days
        setcookie('remembered_password', $pass, time() + (30 * 24 * 60 * 60), "/"); // 30 days
    }

    header("Location: index.html");
} else {
    echo "<script>alert('Invalid email or password'); window.location.href = 'login.html';</script>";
}
?>
