<?php
session_start();
require_once 'includes/dbh.php';

header('Content-Type: application/json');

if (!isset($_POST['user_id']) || !isset($_SESSION['userdata']['id'])) {
    echo json_encode(['success' => false]);
    exit;
}

$result = followUser($_POST['user_id']);
echo json_encode(['success' => $result]);
?>