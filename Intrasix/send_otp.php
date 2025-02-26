<?php
session_start();
include("includes/dbh.php");
include("email.php");

if (isset($_POST["user_email"]) && !empty($_POST["user_email"])) {
    $email = mysqli_real_escape_string($conn, $_POST["user_email"]); // Prevent SQL Injection

    // Check if email exists in the database
    $sql = "SELECT * FROM users WHERE email='$email'";
    $rs = mysqli_query($conn, $sql) or die(mysqli_error($conn));

    if (mysqli_num_rows($rs) > 0) {
        $otp = rand(11111, 99999); // Generate OTP
        send_otp($email,"PHP OTP LOGIN",$otp);

        // Update OTP in database
        $sql = "UPDATE users SET user_otp='$otp' WHERE email='$email'";
        $rs = mysqli_query($conn, $sql) or die(mysqli_error($conn));

        // Redirect with success message
        header("location: verify.php?msg=OTP Sent Successfully!");
        exit();
    } else {
        // Redirect with error message
        header("location: otp.php?msg=Email ID is invalid. Please check again!");
        exit();
    }
} else {
    header("location: otp.php?msg=Please enter an email address.");
    exit();
}
?>

