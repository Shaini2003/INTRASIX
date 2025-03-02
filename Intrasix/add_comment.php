<?php
session_start();
include 'includes/dbh.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

$user_id = $_SESSION['id'];
$post_id = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT);
$comment_text = trim($_POST['comment_text'] ?? '');

if (!$post_id || empty($comment_text)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid comment or post ID']);
    exit;
}

$checkQuery = "SELECT id FROM comments WHERE post_id = ? AND user_id = ? AND comment_text = ? AND created_at > NOW() - INTERVAL 1 MINUTE";
$checkStmt = mysqli_prepare($conn, $checkQuery);
mysqli_stmt_bind_param($checkStmt, "iis", $post_id, $user_id, $comment_text);
mysqli_stmt_execute($checkStmt);
mysqli_stmt_store_result($checkStmt);

if (mysqli_stmt_num_rows($checkStmt) > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Duplicate comment detected']);
    exit;
}

$query = "INSERT INTO comments (post_id, user_id, comment_text) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "iis", $post_id, $user_id, $comment_text);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to save comment']);
}

mysqli_stmt_close($stmt);
mysqli_stmt_close($checkStmt);
mysqli_close($conn);
exit;
?>