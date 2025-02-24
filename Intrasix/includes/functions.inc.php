<?php
function emptyInputSignup($name, $email, $dob, $gender, $pwd, $pwdRepeat, $town) {
    return empty($name) || empty($email) || empty($dob) || empty($gender) || empty($pwd) || empty($pwdRepeat) || empty($town);
}

function invalidDOB($dob) {
    return !strtotime($dob);
}

function invalidUid($email) {
    return !filter_var($email, FILTER_VALIDATE_EMAIL);
}

function invalidGender($gender) {
    $valid_genders = ["male", "female", "custom"];
    return !in_array(strtolower(trim($gender)), $valid_genders);
}


function invalidName($name) {
    return !preg_match("/^[a-zA-Z ]{2,50}$/", $name);
}

function pwdMatch($pwd, $pwdRepeat) {
    return $pwd !== $pwdRepeat;
}

function invalidTown($town) {
    return strtolower(trim($town)) !== "galle";
}

function uidExists($conn, $name, $email) {
    $sql = "SELECT * FROM users WHERE email = ? OR name = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        die("SQL Error: " . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt, "ss", $email, $name);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);

    return mysqli_fetch_assoc($resultData) ?: false;
}

function createUser($conn, $name, $email, $dob, $gender, $pwd, $town) {
    $sql = "INSERT INTO users (name, email, dob, gender, password, town) VALUES (?, ?, ?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        die("SQL Error: " . mysqli_error($conn));
    }
    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
    mysqli_stmt_bind_param($stmt, "ssssss", $name, $email, $dob, $gender, $hashedPwd, $town);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: ../login.php?error=none");
    exit();
}

function emptyInputLogin($email, $pwd) {
    return empty($email) || empty($pwd);
}

function loginUser($conn, $email, $pwd) {
    $uidExists = uidExists($conn, $email, $email);
    if ($uidExists === false) {
        header("Location: ../signup.php?error=wronglogin");
        exit();
    }
    $pwdHashed = $uidExists["password"];
    if (!password_verify($pwd, $pwdHashed)) {
        header("Location: ../signup.php?error=wronglogin");
        exit();
    }
    session_start();
    $_SESSION["id"] = $uidExists["id"];
    $_SESSION["email"] = $uidExists["email"];
    $_SESSION['name'] = $uidExists["name"];
    header("Location: ../index.php");
    exit();
}
?>
