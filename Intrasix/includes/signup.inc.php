<?php
require_once 'dbh.php';
require_once 'functions.inc.php';

if(isset($_POST["submit"])){
    $name=$_POST["name"];
    $email = $_POST["email"];
    $dob = $_POST["dob"];
    $gender = $_POST["gender"];
    $pwd = $_POST["password"];
    $pwdRepeat =$_POST["confirm_password"];
    $town =$_POST["town"];

    if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])){
        
        $secretKey= '6LcbHM8qAAAAADjudJJyldZ8JfXXAXm697OW-pdO';
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secretKey.'&response='.($_POST['g-recaptcha-response']));
        $response = json_decode($verifyResponse);

    
        $emptyInput =emptyInputSignup($name,$email,$dob,$gender,$pwd,$pwdRepeat,$town);
        $invalidUid = invalidUid($email);
        $invalidName = invalidName($name);
        $invalidEmail=invalidEmail($email);
        $invalidDob=invalidDOB($dob);
        $invalidGender =invalidGender($gender);
        $invalidTown = invalidTown($town);
        $invalidPwdMatch = pwdMatch($pwd , $pwdRepeat);
        $invalidUidExists =uidExists($conn,$name,$email);
    
        if ($emptyInput !== false){
            header("Location:../signup.php?error=emptyinput");
            exit();
        }
        if ($invalidUid !== false){
            header("Location:../signup.php?error=invaliduid");
            exit();
        }
        if($invalidEmail !== false){
            header("Location:../signup.php?error=invalidemail");
            exit();
        }
       
        if($invalidName !== false){
            header("Location:../signup.php?error=invalidname");
            exit();
        }
        if($invalidDob !== false){
            header("Location:../signup.php?error=invaliddob");
            exit();
        }
        if($invalidGender !== false){
            header("Location:../signup.php?error=invalidgender");
            exit();
        }
        if($invalidTown !== false){
            header("Location:../signup.php?error=invalidtown");
            exit();
        }
        
        if ($pwdMatch !== false){
            header("Location:../signup.php?error=passwordsdontmatch");
            exit();
        }
       
        if ($uidExists !== false){
            header("Location:../signup.php?error=emailtaken");
            exit();
        }
    createUser($conn,$name,$email,$dob,$gender,$pwd,$town);
    
    }
    else{
        $_SESSION['status'] = "Error in recapcha verification";
        header("Location: {$_SERVER["HTTP_REFERER"]}");
        exit(0);
    }
    }
    else{
        header('location:../login.php');
    }
