<?php
session_start();
require_once 'includes/dbh.php'; // Must establish $conn

// Prevent any output before JSON
ob_start(); // Start output buffering

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if (!isset($_SESSION['id'])) {
    $response['message'] = 'Please login to follow users';
    echo json_encode($response);
    ob_end_flush();
    exit;
}

if (!isset($_POST['following_id']) || !is_numeric($_POST['following_id'])) {
    $response['message'] = 'Invalid user ID';
    echo json_encode($response);
    ob_end_flush();
    exit;
}

$user_id = (int)$_POST['following_id'];

// Include the followUser function if not already available
if (!function_exists('followUser')) {
    function followUser($user_id) {
        global $conn;
        
        if (!isset($_SESSION['id'])) {
            return false;
        }
        
        $current_user = $_SESSION['id'];
        
        $stmt = $conn->prepare("
            SELECT COUNT(*) as count 
            FROM follow_list 
            WHERE follower_id = ? AND following_id = ?
        ");
        $stmt->bind_param("ii", $current_user, $user_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        
        if ($result['count'] > 0 || $current_user == $user_id) {
            return false;
        }
        
        $stmt = $conn->prepare("
            INSERT INTO follow_list (follower_id, following_id, created_at) 
            VALUES (?, ?, NOW())
        ");
        $stmt->bind_param("ii", $current_user, $user_id);
        return $stmt->execute();
    }
}

if (followUser($user_id)) {
    $response['success'] = true;
    $response['message'] = 'Successfully followed user';
} else {
    $response['message'] = 'Unable to follow user - possible duplicate or error';
}

echo json_encode($response);
ob_end_flush(); // Send output and end buffering
?>