<?php
include 'includes/dbh.php'; // Assuming $conn is your MySQLi connection

// Get user by username
function getUserByUsername($username) {
    global $conn;
    $query = "SELECT * FROM users WHERE name = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    return $user;
}
// Get posts by user ID
function getPostsByUserId($user_id) {
    global $conn;
    $query = "SELECT p.*, u.name AS name 
              FROM posts p 
              JOIN users u ON p.user_id = u.id 
              WHERE p.user_id = ? 
              ORDER BY p.created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $posts = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $posts;
}



// Get followers count
function getFollowersCount($user_id) {
    global $conn;
    $query = "SELECT COUNT(*) FROM follow_list WHERE following_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    return $count;
}

// Get following count
function getFollowingCount($user_id) {
    global $conn;
    $query = "SELECT COUNT(*) FROM follow_list WHERE follower_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    return $count;
}

// Check if a user is following another user
function isFollowing($follower_id, $following_id) {
    global $conn;
    $query = "SELECT COUNT(*) FROM follow_list WHERE follower_id = ? AND following_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $follower_id, $following_id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    return $count > 0;
}

// Toggle follow/unfollow
function toggleFollow($follower_id, $following_id) {
    global $conn;
    if (isFollowing($follower_id, $following_id)) {
        $query = "DELETE FROM follow_list WHERE follower_id = ? AND following_id = ?";
    } else {
        $query = "INSERT INTO follow_list (follower_id, following_id) VALUES (?, ?)";
    }
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $follower_id, $following_id);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
}

// Get suggested users (users not followed by the current user)
function getSuggestedUsers($current_user_id, $limit = 5) {
    global $conn;
    $query = "SELECT u.* 
              FROM users u 
              WHERE u.id != ? 
              AND u.id NOT IN (
                  SELECT following_id 
                  FROM followers 
                  WHERE follower_id = ?
              ) 
              LIMIT ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iii", $current_user_id, $current_user_id, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    $users = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $users;
}

function isBlocking($blocker_id, $blocked_id) {
    global $conn;
    $query = "SELECT COUNT(*) FROM blocked_users WHERE blocker_id = ? AND blocked_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $blocker_id, $blocked_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_row()[0];
    $stmt->close();
    return $result > 0;
}

function toggleBlock($blocker_id, $blocked_id) {
    global $conn;
    
    if (isBlocking($blocker_id, $blocked_id)) {
        // Unblock
        $query = "DELETE FROM blocked_users WHERE blocker_id = ? AND blocked_id = ?";
    } else {
        // Block
        $query = "INSERT INTO blocked_users (blocker_id, blocked_id) VALUES (?, ?)";
    }
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $blocker_id, $blocked_id);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
}
?>