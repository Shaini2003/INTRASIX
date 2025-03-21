<?php
session_start();
include("includes/dbh.php"); // Database connection
include("email.php"); // Email sending function

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["user_email"])) {
    $email = filter_var($_POST["user_email"], FILTER_SANITIZE_EMAIL);
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: forgot_password.php?msg=Invalid email format");
        exit();
    }

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $otp = sprintf("%05d", rand(0, 99999)); // Generate 5-digit OTP

        // Send OTP email
        if (send_otp($email, "INTRASIX OTP LOGIN", $otp)) {
            // Store email in session
            $_SESSION['email'] = $email;
            
            // Update OTP in database
            $stmt = $conn->prepare("UPDATE users SET user_otp = ? WHERE email = ?");
            $stmt->bind_param("ss", $otp, $email);
            $stmt->execute();
            
            $stmt->close();
            header("Location: verify_otp.php?msg=OTP Sent Successfully!");
            exit();
        } else {
            $stmt->close();
            header("Location: forgot_password.php?msg=Failed to send OTP. Try again!");
            exit();
        }
    } else {
        $stmt->close();
        header("Location: forgot_password.php?msg=Email not registered!");
        exit();
    }
} else {
    header("Location: forgot_password.php?msg=Please enter an email address");
    exit();
}

$conn->close();
?>