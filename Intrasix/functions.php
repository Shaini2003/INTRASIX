<?php
// includes/functions.inc.php
require_once 'includes/dbh.php';

function getUserByUsername(string $username): ?array {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM users WHERE name = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    return $user ?: null;
}

function getPostById(int $user_id): array {
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

function isFollowing(int $follower_id, int $following_id): bool {
    global $conn;
    $stmt = $conn->prepare("SELECT COUNT(*) FROM followers WHERE follower_id = ? AND following_id = ?");
    $stmt->bind_param("ii", $follower_id, $following_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_assoc()['COUNT(*)'];
    $stmt->close();
    return $count > 0;
}

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

function blockUser(int $blocker_id, int $blocked_id): bool {
    global $conn;
    // Check if already blocked to avoid duplicates
    $stmt = $conn->prepare("SELECT COUNT(*) FROM blocked_users WHERE blocker_id = ? AND blocked_id = ?");
    $stmt->bind_param("ii", $blocker_id, $blocked_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_assoc()['COUNT(*)'];
    $stmt->close();

    if ($count == 0) {
        $stmt = $conn->prepare("INSERT INTO blocked_users (blocker_id, blocked_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $blocker_id, $blocked_id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
    return true; // Already blocked, consider it a success
}
?>