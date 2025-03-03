<?php
include 'includes/dbh.php';
session_start();

$user_id = $_SESSION['id'];
$post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

if ($post_id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid post ID']);
    exit;
}

if (!isset($_SESSION['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

// Check if user already liked the post
$query = "SELECT id FROM likes WHERE post_id = ? AND user_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ii", $post_id, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_fetch_assoc($result)) {
    // Unlike the post
    $query = "DELETE FROM likes WHERE post_id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ii", $post_id, $user_id);
    mysqli_stmt_execute($stmt);
} else {
    // Like the post
    $query = "INSERT INTO likes (post_id, user_id) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ii", $post_id, $user_id);
    mysqli_stmt_execute($stmt);
}

// Rebuild and return the page with updated like state and count
// This assumes you have the full page logic in a template or function
ob_start();
include 'index.php'; // Or wherever your main page template is
$pageContent = ob_get_clean();

echo $pageContent;

mysqli_stmt_close($stmt);
mysqli_close($conn);
exit;
?>