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
    header("Location: ../otp.php");
    exit();
}



// Function to get user details by username
function getUserByUsername($username) {
    global $conn;
    $query = "SELECT * FROM users WHERE name = ?";
    $stmt = mysqli_prepare($conn, $query);
    
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    return mysqli_fetch_assoc($result);
}

// Function to get posts by user ID
function getPostById($user_id) {
    global $conn;
    $query = "SELECT * FROM posts WHERE user_id = ? ORDER BY id DESC";
    $stmt = mysqli_prepare($conn, $query);
    
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function getFollowSuggestions() {
    global $conn;

    // Start session if not already started
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Check if user is logged in
    if (!isset($_SESSION['userdata']['id'])) {
        return [];
    }

    $current_user = $_SESSION['userdata']['id'];

    // Secure query using prepared statements
    $stmt = $conn->prepare("SELECT id, name, profile_pic FROM users WHERE id != ? LIMIT 10");
    $stmt->bind_param("i", $current_user);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}

// Filter follow suggestions
function filterFollowSuggestion($list) {
    $filter_list = [];
    foreach ($list as $user) {
        if (!checkFollowStatus($user['id'])) {
            $filter_list[] = $user;
        }
    }
    return $filter_list;
}

// Check if the user is already followed
function checkFollowStatus($user_id) {
    global $conn;
    
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $current_user = $_SESSION['userdata']['id'];

    $stmt = $conn->prepare("SELECT COUNT(*) AS row FROM follow_list WHERE follower_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $current_user, $user_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    
    return $result['row'] > 0;
}

// Function to follow a user
function followUser($user_id) {
    global $conn;
    
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $current_user = $_SESSION['userdata']['id'];

    $stmt = $conn->prepare("INSERT INTO follow_list (follower_id, user_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $current_user, $user_id);
    return $stmt->execute();
}
