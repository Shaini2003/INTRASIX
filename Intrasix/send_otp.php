<?php
session_start();
include("includes/dbh.php"); // Database connection
include("email.php"); // Email sending function

if (isset($_POST["user_email"]) && !empty($_POST["user_email"])) {
    $email = mysqli_real_escape_string($conn, $_POST["user_email"]);

    // Check if email exists
    $sql = "SELECT * FROM users WHERE email='$email'";
    $rs = mysqli_query($conn, $sql);

    if (mysqli_num_rows($rs) > 0) {
        $otp = rand(11111, 99999); // Generate OTP

        // Send OTP email first
        if (send_otp($email, "INTRASIX OTP LOGIN", $otp)) {
            $_SESSION['email'] = $email; // 🔹 Store email for verification
            // If email sent successfully, update OTP in database
            $sql = "UPDATE users SET user_otp='$otp' WHERE email='$email'";
            mysqli_query($conn, $sql);

            header("location: verify.php?msg=OTP Sent Successfully!");
            exit();
        } else {
            header("location: otp.php?msg=Failed to send OTP. Try again!");
            exit();
        }
    } else {
        header("location: otp.php?msg=Invalid Email ID. Please check again!");
        exit();
    }
} else {
    header("location: otp.php?msg=Please enter an email address.");
    exit();
}
?>

