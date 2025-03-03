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

// Fetch follow suggestions
function getFollowSuggestions() {
    global $conn;
    

    if (!isset($_SESSION['userdata']['id'])) {
        return [];
    }

    $current_user = $_SESSION['userdata']['id'];

    // Secure query to get users that are not already followed
    $stmt = $conn->prepare("
        SELECT id, name, profile_pic 
        FROM users 
        WHERE id != ? 
        ORDER BY RAND() 
        LIMIT 10
    ");
    $stmt->bind_param("i", $current_user);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}

// Filter out already followed users
function filterFollowSuggestion($list) {
    $filtered_list = [];
    foreach ($list as $user) {
        if (!checkFollowStatus($user['id'])) {
            $filtered_list[] = $user;
        }
    }
    return $filtered_list;
}

// Check if the user is already followed
function checkFollowStatus($user_id) {
    global $conn;
   

    if (!isset($_SESSION['userdata']['id'])) {
        return true;
    }

    $current_user = $_SESSION['userdata']['id'];

    $stmt = $conn->prepare("
        SELECT COUNT(*) AS row_count 
        FROM follow_list 
        WHERE follower_id = ? AND user_id = ?
    ");
    $stmt->bind_param("ii", $current_user, $user_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    return $result['row_count'] > 0;
}

// Follow a user
function followUser($user_id) {
    global $conn;
    session_start();

    if (!isset($_SESSION['userdata']['id'])) {
        return false;
    }

    $current_user = $_SESSION['userdata']['id'];

    // Check if the follow relationship already exists
    if (checkFollowStatus($user_id)) {
        return false;
    }

    $stmt = $conn->prepare("INSERT INTO follow_list (follower_id, user_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $current_user, $user_id);
    return $stmt->execute();
}

function getLikeCount($post_id) {
    include 'includes/dbh.php'; 

    $query = "SELECT COUNT(*) AS like_count FROM likes WHERE post_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $post_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    
    return $row['like_count'] ?? 0;
}

function userLikedPost($post_id, $user_id) {
    include 'includes/dbh.php';

    $query = "SELECT id FROM likes WHERE post_id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ii", $post_id, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $liked = mysqli_fetch_assoc($result) ? true : false;
    mysqli_stmt_close($stmt);

    return $liked;
}


function getUserrByUsername(string $username): ?array {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM users WHERE name = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    
    $result = $stmt->get_result(); // Get the result set
    $user = $result->fetch_assoc(); // Fetch as associative array
    
    $stmt->close();
    return $user ?: null;
}
function getPosttById(int $user_id): array {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $posts = [];
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
    
    $stmt->close();
    return $posts;
}

function getFollowersCount(int $user_id): int {
    global $conn;
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM follow_list WHERE following_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    $stmt->close();
    return (int)$row['count'];
}

/**
 * Get following count
 * @param int $user_id
 * @return int
 */
function getFollowingCount(int $user_id): int {
    global $conn;
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM follow_list WHERE follower_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    $stmt->close();
    return (int)$row['count'];
}

/**
 * Check if user is following another user
 * @param int $follower_id
 * @param int $following_id
 * @return bool
 */
function isFollowing(int $follower_id, int $following_id): bool {
    global $conn;
    $stmt = $conn->prepare("SELECT COUNT(*) FROM follow_list WHERE follower_id = ? AND following_id = ?");
    $stmt->bind_param("ii", $follower_id, $following_id);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $count = $result->fetch_assoc()['COUNT(*)'];
    
    $stmt->close();
    return $count > 0;
}

/**
 * Toggle follow/unfollow
 * @param int $follower_id
 * @param int $following_id
 * @return bool
 */
function toggleFollow(int $follower_id, int $following_id): bool {
    global $conn;
    if (isFollowing($follower_id, $following_id)) {
        $stmt = $conn->prepare("DELETE FROM follow_list WHERE follower_id = ? AND following_id = ?");
    } else {
        $stmt = $conn->prepare("INSERT INTO follow_list (follower_id, following_id) VALUES (?, ?)");
    }
    $stmt->bind_param("ii", $follower_id, $following_id);
    $success = $stmt->execute();
    
    $stmt->close();
    return $success;
}

/**
 * Block a user
 * @param int $blocker_id
 * @param int $blocked_id
 * @return bool
 */
function blockUser(int $blocker_id, int $blocked_id): bool {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO blocked_users (blocker_id, blocked_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $blocker_id, $blocked_id);
    $success = $stmt->execute();
    
    $stmt->close();
    return $success;
}