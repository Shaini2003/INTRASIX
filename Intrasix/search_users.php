<?php
session_start();
require_once 'includes/dbh.php'; // Database connection

header('Content-Type: application/json');

$response = ['success' => false, 'message' => '', 'users' => []];

if (!isset($_POST['search_query'])) {
    $response['message'] = 'No search query provided';
    echo json_encode($response);
    exit;
}

$search_query = '%' . $_POST['search_query'] . '%';

try {
    $stmt = $conn->prepare("
        SELECT id, name, profile_pic 
        FROM users 
        WHERE name LIKE ? 
        ORDER BY name ASC 
        LIMIT 10
    ");
    $stmt->bind_param("s", $search_query);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $users = $result->fetch_all(MYSQLI_ASSOC);
    
    $response['success'] = true;
    $response['users'] = array_map(function($user) {
        return [
            'id' => htmlspecialchars($user['id']),
            'name' => htmlspecialchars($user['name']),
            'profile_pic' => htmlspecialchars($user['profile_pic'] ?? '')
        ];
    }, $users);
    
} catch (Exception $e) {
    $response['message'] = 'Database error: ' . $e->getMessage();
}

echo json_encode($response);
?>