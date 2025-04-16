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

// Check if "Remember Me" cookies are set and user has not logged in yet
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remembered_email'])) {
    $email = urldecode($_COOKIE['remembered_email']); // decode %40 to @
    
    // For cookie login, you need password input
    if (isset($_POST['password'])) {
        $pass = $_POST['password'];
        
        $sql = "SELECT * FROM users WHERE email=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($pass, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: index.html");
            exit();
        }
    }
}

// Regular login
if (isset($_POST['email']) && isset($_POST['password'])) {
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

        if (isset($_POST['remember'])) {
            // Encode email to store it safely in cookie
            setcookie('remembered_email', $email, time() + (30 * 24 * 60 * 60), "/"); // 30 days
        }
        header("Location: index.html");
        exit();
    } else {
        echo "<script>alert('Invalid email or password'); window.location.href = 'login.html';</script>";
    }
}
?>
