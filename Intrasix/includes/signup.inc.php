<?php
require_once 'dbh.php';
require_once 'functions.inc.php';

if (isset($_POST["submit"])) {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $dob = trim($_POST["dob"]);
    $gender = trim($_POST["gender"]);
    $pwd = $_POST["password"];
    $pwdRepeat = $_POST["confirm_password"];
    $town = trim($_POST["town"]);

    if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
        $secretKey = '6LcbHM8qAAAAADjudJJyldZ8JfXXAXm697OW-pdO';
        $verifyResponse = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=" . $_POST['g-recaptcha-response']);
        $response = json_decode($verifyResponse);

        if (!$response->success) {
            header("Location: ../signup.php?error=recaptcha_failed");
            exit();
        }

        // Validation
        if (emptyInputSignup($name, $email, $dob, $gender, $pwd, $pwdRepeat, $town)) {
            header("Location: ../signup.php?error=emptyinput");
            exit();
        }
        if (invalidUid($email)) {
            header("Location: ../signup.php?error=invalidemail");
            exit();
        }
        if (invalidName($name)) {
            header("Location: ../signup.php?error=invalidname");
            exit();
        }
        if (invalidDOB($dob)) {
            header("Location: ../signup.php?error=invaliddob");
            exit();
        }
        if (invalidGender($gender)) {
            header("Location: ../signup.php?error=invalidgender");
            exit();
        }
        if (invalidTown($town)) {
            header("Location: ../signup.php?error=invalidtown");
            exit();
        }
        if (pwdMatch($pwd, $pwdRepeat)) {
            header("Location: ../signup.php?error=passwordsdontmatch");
            exit();
        }
        if (uidExists($conn, $name, $email)) {
            header("Location: ../signup.php?error=emailtaken");
            exit();
        }

        createUser($conn, $name, $email, $dob, $gender, $pwd, $town);
    } else {
        header("Location: ../signup.php?error=recaptcha_missing");
        exit();
    }
} else {
    header("Location: ../login.php");
    exit();
}
?>
