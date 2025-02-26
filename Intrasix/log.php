<?php
session_start();
include("includes/dbh.php");

$email=$_SESSION['email'];
$otp=$_POST['user_otp'];
$sql="Select * from users where email='$email' and user_otp='$otp'";
$rs=mysqli_query($conn,$sql)or die(mysqli_error($conn));
if(mysqli_num_rows($rs)>0){
    $sql="update users set user_otp='' where email='$email'";
    $rs=mysqli_query($conn,$sql)or die(mysqli_error($conn));
    header("location:index.php?msg=Welcome User:Login Success!!");

}
else{
    header("location:otp.php?msg=OTP is Invalid Plz try Again!!");
}
?>