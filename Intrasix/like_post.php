<?php
include 'includes/dbh.php'; // Database connection
session_start();

$user_id = $_SESSION['id'];
$post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

if ($post_id <= 0) {
    header("Location: index.php"); // Redirect on invalid post ID
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

header("Location: " . $_SERVER['HTTP_REFERER']); // Redirect back to the post page
exit;
?>
