<?php
session_start();
include("dbh.php");
include("email.php");

$email = $_POST["email"];
$sql = "Select * from users where email='$email'";
$rs = mysqli_query($conn, $sql) or die(mysqli_error($conn));
if (mysqli_num_rows($rs) > 0) {
    $_SESSION['email'] = $email;
    $otp = rand(11111, 99999);
    send_otp($email, "PHP OTP LOGIN", $otp);
    $sql = "update users set user_otp='$otp' where email='$email'";
    $rs = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    header("location:verify.php?msg=Plz check Your Email For OTP and Verify");
} else {
    header("location:otp.php?msg=Email id is Invalid....plz check Again!!!");
}
