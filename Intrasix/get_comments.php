<?php
include 'includes/dbh.php';

$post_id = $_GET['post_id'];

$query = "SELECT comments.*, users.name, users.profile_pic 
          FROM comments 
          JOIN users ON users.id = comments.user_id 
          WHERE post_id = ? 
          ORDER BY comments.created_at DESC";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $post_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$comments = mysqli_fetch_all($result, MYSQLI_ASSOC);
echo json_encode($comments);
?>
