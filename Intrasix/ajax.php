<?php
require_once 'includes/dbh.php';
require_once 'includes/functions.inc.php';

if (isset($_GET['follow'])) {
    session_start();
    
    if (!isset($_SESSION['userdata']['id'])) {
        echo json_encode(['status' => false, 'message' => 'User not logged in']);
        exit;
    }

    $user_id = intval($_POST['user_id']);
    
    if ($user_id > 0 && followUser($user_id)) {
        echo json_encode(['status' => true]);
    } else {
        echo json_encode(['status' => false]);
    }
}
