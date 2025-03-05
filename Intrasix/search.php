<?php
include 'includes/dbh.php'; // Database connection

$query = $_GET['q'] ?? '';
$query = "%" . $query . "%"; // Wildcard search

// Use 'username' instead of 'name' to match typical schema
$stmt = $conn->prepare("SELECT id, name, profile_pic FROM users WHERE name LIKE ? LIMIT 5");
$stmt->bind_param("s", $query);
$stmt->execute();
$result = $stmt->get_result();
$users = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

header('Content-Type: application/json');
echo json_encode($users);
?>