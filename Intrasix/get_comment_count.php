<?php
include 'includes/dbh.php';

header('Content-Type: application/json');

$post_id = filter_input(INPUT_GET, 'post_id', FILTER_VALIDATE_INT);

if (!$post_id) {
    echo json_encode(['comment_count' => 0]);
    exit;
}

$query = "SELECT COUNT(*) as comment_count FROM comments WHERE post_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $post_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

echo json_encode(['comment_count' => $row['comment_count']]);

mysqli_stmt_close($stmt);
mysqli_close($conn);
exit;
?>