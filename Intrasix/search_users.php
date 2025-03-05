<?php
session_start();
include 'includes/dbh.php';

if (!isset($_SESSION['id'])) {
    echo '<div class="result-item">Please log in to search</div>';
    exit();
}

$search_term = isset($_GET['term']) ? trim($_GET['term']) : '';

if (empty($search_term)) {
    echo '<div class="result-item">Enter a search term</div>';
    exit();
}

$query = "SELECT id, name, profile_pic FROM users WHERE name LIKE ? AND id != ? LIMIT 10";
$stmt = $conn->prepare($query);
$search_param = "%$search_term%";
$stmt->bind_param("si", $search_param, $_SESSION['id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $profile_pic = $row['profile_pic'] ?? 'images/default.jpg';
        echo "<div class='result-item' onclick=\"window.location.href='profile.php?id={$row['id']}'\">";
        echo "<img src='$profile_pic' alt='{$row['name']}'>";
        echo "<span>{$row['name']}</span>";
        echo "</div>";
    }
} else {
    echo '<div class="result-item">No users found</div>';
}

$stmt->close();
$conn->close();
?>