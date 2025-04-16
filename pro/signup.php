<?php
$host = "localhost";
$dbname = "geomap";
$username = "root";
$password = "";

try {
    // Create connection
    $conn = new mysqli($host, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Validate inputs
    if (empty($_POST['email']) || empty($_POST['password']) || empty($_POST['secretkey'])) {
        throw new Exception("All fields are required");
    }

    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email format");
    }

    $pass = $_POST['password'];
    $secretkey = $_POST['secretkey'];
    
    $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
    $hashed_secretkey = password_hash($secretkey, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (email, password, secret_phrase) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("sss", $email, $hashed_password, $hashed_secretkey);

    if ($stmt->execute()) {
        header("Location: login.html");
        exit();
    } else {
        throw new Exception("Signup failed: " . $stmt->error);
    }
} catch (Exception $e) {
  error_log($e->getMessage());
  $error_message = urlencode("Email already exists. Try using another email.");
  header("Location: signup.html?error=$error_message");
  exit();
} finally {
    if (isset($stmt)) $stmt->close();
    if (isset($conn)) $conn->close();
}
?>