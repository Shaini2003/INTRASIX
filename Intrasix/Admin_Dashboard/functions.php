<?php
// functions.php (partial, add these if not present)

function isBlocking($blocker_id, $blocked_id) {
    global $conn;
    $query = "SELECT COUNT(*) as count FROM blocked_users WHERE blocker_id = ? AND blocked_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $blocker_id, $blocked_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['count'] > 0;
}

function toggleBlock($blocker_id, $blocked_id) {
    global $conn;
    if (isBlocking($blocker_id, $blocked_id)) {
        $query = "DELETE FROM blocked_users WHERE blocker_id = ? AND blocked_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $blocker_id, $blocked_id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    } else {
        $query = "INSERT INTO blocked_users (blocker_id, blocked_id, created_at) VALUES (?, ?, NOW())";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $blocker_id, $blocked_id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
}

// Include other existing functions (getUserByUsername, getPostsByUserId, etc.) as needed
?>