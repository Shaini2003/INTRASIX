<?php
session_start();
include 'includes/dbh.php'; // Database connection

// Check if email exists in session
if (!isset($_SESSION['email'])) {
    header("Location: forgot_password.php?msg=Session expired. Please request a new OTP.");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['otp'])) {
    $email = $_SESSION['email'];
    $otp = trim($_POST['otp']);

    // Validate OTP format
    if (!preg_match('/^\d{5}$/', $otp)) {
        header("Location: verify_otp.php?msg=Invalid OTP format. Please enter a 5-digit OTP.");
        exit();
    }

    // Verify OTP
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND user_otp = ?");
    $stmt->bind_param("ss", $email, $otp);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Clear OTP
        $stmt = $conn->prepare("UPDATE users SET user_otp = '' WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        // Set session variables
        $_SESSION['id'] = $user['id'];
        unset($_SESSION['email']);

        $stmt->close();
        $conn->close();
        
        header("Location: index2.php?msg=OTP verified successfully!");
        exit();
    } else {
        $stmt->close();
        $conn->close();
        header("Location: verify_otp.php?msg=Invalid OTP. Please try again.");
        exit();
    }
} else {
    header("Location: verify_otp.php?msg=Please enter the OTP.");
    exit();
}
?>