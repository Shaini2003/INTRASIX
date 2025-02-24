<?php
require_once 'dbh.php';
require_once 'functions.inc.php';

if (isset($_POST["submit"])) {
    $email = trim($_POST["email"]);
    $pwd = $_POST["password"];

    // Check if fields are empty
    if (emptyInputLogin($email, $pwd)) {
        header("Location: ../login.php?error=emptyinput");
        exit();
    }

    // Log in user
    loginUser($conn, $email, $pwd);
} else {
    header("Location: ../login.php");
    exit();
}
?>
