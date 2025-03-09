<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $user_id = intval($_POST['user_id']);
    $blocker_id = $_SESSION['admin_id'];

    $query = "SELECT COUNT(*) as count FROM blocked_users WHERE blocker_id = ? AND blocked_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $blocker_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $is_blocked = $result['count'] > 0;
    $stmt->close();

    if ($is_blocked) {
        $query = "DELETE FROM blocked_users WHERE blocker_id = ? AND blocked_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $blocker_id, $user_id);
    } else {
        $query = "INSERT INTO blocked_users (blocker_id, blocked_id, created_at) VALUES (?, ?, NOW())";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $blocker_id, $user_id);
    }

    if ($stmt->execute()) {
        header("Location: main.php");
        exit;
    } else {
        echo "Error updating block status.";
    }
    $stmt->close();
}

$conn->close();
?>