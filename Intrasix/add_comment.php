<?php
include 'includes/dbh.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$post_id = $_POST['post_id'];
$comment_text = trim($_POST['comment_text']);

if (empty($comment_text)) {
    echo json_encode(['status' => 'error', 'message' => 'Comment cannot be empty']);
    exit;
}

$query = "INSERT INTO comments (post_id, user_id, comment_text) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "iis", $post_id, $user_id, $comment_text);
mysqli_stmt_execute($stmt);

echo json_encode(['status' => 'success']);
?>
