<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    
    $to = "contactgeomaps0@gmail.com";  
    $subject = "New Contact Form Submission from $name";
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    $email_body = "You have received a new message from the Geo Tech Maps contact form.\n\n".
                  "Name: $name\n".
                  "Email: $email\n".
                  "Message:\n$message\n";

    
    if (mail($to, $subject, $email_body, $headers)) {
        echo "<script>alert('Message sent successfully!'); window.location.href='contactus.html';</script>";
    } else {
        echo "<script>alert('Message sending failed. Please try again later.'); window.location.href='contactus.html';</script>";
    }
} else {
    header("Location: contactus.html");
    exit();
}
?>