<?php
function emptyInputSignup($name,$email,$dob,$gender,$pwd,$pwdRepeat,$town){
    if(empty($name) || empty($email) || empty($dob) || empty($gender) || empty($pwd) || empty($pwdRepeat) || empty($town)) {
        $result =true;
    }
    else{
        $result=false;
    }
    return $result;
}
function invalidDOB($dob){
    if (!strtotime($dob)) {
        die("Error: Invalid Date of Birth format!");
    }

}

function invalidUid($email){
    if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
        $result=true;
    }
    else{
        $result=false;
    }
    return $result;

}

function invalidGender($gender){
    $valid_genders = ["Male", "Female", "Custom"];
    if (!in_array($gender, $valid_genders)) {
        die("Error: Invalid gender selection!");
    }
}
function invalidName($name){
    if (!preg_match("/^[a-zA-Z ]{2,50}$/", $name)) {
        die("Error: Name must contain only letters and spaces (2-50 characters)!");
    }
}
function invalidEmail($email){
    if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
        $result=true;
    }else{
        $result=false;
    }
    return $result;

}
function pwdMatch($pwd , $pwdRepeat){
    if($pwd !== $pwdRepeat){
        $result=true;
    }else{
        $result=false;
    }
    return $result;

}

function invalidTown($town){
    if (strtolower(trim($town)) !== "galle") {
        die("Error: Registration is only allowed for users from Galle.");
    }
}

function uidExists($conn,$name,$email){
    $sql= "SELECT*FROM users WHERE email= ? OR name= ?;";
    $stmt =mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)){
        header("Location:../signup.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"ss",$name,$email);
    mysqli_stmt_execute($stmt);
    $resultData= mysqli_stmt_get_result($stmt);
    if($row =mysqli_fetch_assoc($resultData)){
        return $row;
    }
    else{
        return false;
    }
    mysqli_stmt_close($stmt);
    

}
function createUser($conn,$name,$email,$dob,$gender,$pwd,$town){
    $sql = "INSERT INTO users (name,email,dob,gender,password,town) VALUES (?,?,?,?,?,?);";
    $stmt =mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)){
        header("Location:../signup.php?error=stmtfailed");
        exit();
    }
    $hashedPwd = password_hash($pwd,PASSWORD_DEFAULT);
    mysqli_stmt_bind_param($stmt,"ssssss",$name,$email,$dob,$gender,$hashedPwd,$town);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location:../login.php?error=none");
    exit();
}

function emptyInputLogin($email,$pwd){
    if(empty($email) || empty($pwd)) {
        $result =true;
    }
    else{
        $result=false;
    }
    return $result;
}
function LoginUser($conn,$email,$pwd){
    $uidExists = uidExists($conn,$email,$email);
    if($uidExists === false){
        header("location:../signup.php?error=wronglogin");
        exit();
    }
    $pwdHashed= $uidExists["password"];
    $checkPwd =password_verify($pwd,$pwdHashed);
    if($checkPwd === false){
        header("location:../signup.php?error=wronglogin");
        exit();
    }
    else if($checkPwd === true){
        session_start();
        $_SESSION["id"] =$uidExists["id"];
        $_SESSION["email"] =$uidExists["email"];
        $_SESSION['name'] = $uidExists["name"];
        header("Location:../index.php");
        exit();
    }
}