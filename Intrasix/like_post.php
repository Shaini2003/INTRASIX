<?php
include 'includes/dbh.php';
session_start();

$user_id = $_SESSION['id'];
$post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

if ($post_id <= 0) {
    echo "Invalid post ID";
    exit;
}

if (!isset($_SESSION['id'])) {
    echo "User not logged in";
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

    // Insert notification for the post owner
    $post_owner_query = "SELECT user_id FROM posts WHERE id = ?";
    $owner_stmt = mysqli_prepare($conn, $post_owner_query);
    mysqli_stmt_bind_param($owner_stmt, "i", $post_id);
    mysqli_stmt_execute($owner_stmt);
    $owner_result = mysqli_stmt_get_result($owner_stmt);
    $post_owner = mysqli_fetch_assoc($owner_result)['user_id'];

    if ($post_owner != $user_id) { // Don't notify the user for their own like
        $notif_query = "INSERT INTO notifications (user_id, actor_id, post_id, type) VALUES (?, ?, ?, 'like')";
        $notif_stmt = mysqli_prepare($conn, $notif_query);
        mysqli_stmt_bind_param($notif_stmt, "iii", $post_owner, $user_id, $post_id);
        mysqli_stmt_execute($notif_stmt);
        mysqli_stmt_close($notif_stmt);
    }
    mysqli_stmt_close($owner_stmt);
}

// Rebuild and return the page with updated like state and count
ob_start();
include 'index.php';
$pageContent = ob_get_clean();

echo $pageContent;

mysqli_stmt_close($stmt);
mysqli_close($conn);
exit;
?>