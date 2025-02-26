<?php
session_start();
include("includes/dbh.php");

if (!isset($_SESSION['email'])) {
    header("location:otp.php?msg=Session expired. Please request a new OTP.");
    exit();
}

$email = $_SESSION['email'];
$otp = isset($_POST['user_otp']) ? trim($_POST['user_otp']) : '';

if (!preg_match('/^\d{5}$/', $otp)) {
    header("location:verify.php?msg=Invalid OTP format. Please enter a 5-digit OTP.");
    exit();
}

$otp = mysqli_real_escape_string($conn, $otp);
$sql = "SELECT * FROM users WHERE email='$email' AND user_otp='$otp'";
$rs = mysqli_query($conn, $sql);

if (mysqli_num_rows($rs) > 0) {
    // Clear OTP after successful login
    $sql = "UPDATE users SET user_otp='' WHERE email='$email'";
    mysqli_query($conn, $sql);
    
    // Redirect to the dashboard or home page
    header("location:index.php?msg=Welcome User! Login Success.");
    exit();
} else {
    header("location:verify.php?msg=Invalid OTP. Please try again.");
    exit();
}
?>
