<?php
session_start();
include 'includes/dbh.php';

if (!isset($_SESSION['id'])) {
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$user_id = $data['user_id'];

$query = "UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>