<?php
session_start();
include 'includes/dbh.php'; // Database connection

if (!isset($_SESSION['id'])) {
    echo '<span>Please log in to see messages</span>';
    exit();
}

$current_user_id = $_SESSION['id'];
echo "Debug: Current User ID = $current_user_id<br>"; // Debug line

// Fetch unread messages count and details
$stmt = $conn->prepare("
    SELECT c.chat_id, c.from_user_id, c.msg, c.start_time, u.name, u.profile_pic, c.is_read
    FROM chat c
    JOIN users u ON c.from_user_id = u.id
    WHERE c.to_user_id = ? AND c.is_read = 0
    ORDER BY c.start_time DESC
    LIMIT 5
");
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = $result->fetch_all(MYSQLI_ASSOC);
$unread_count = count($messages);

echo "Debug: Unread Count = $unread_count<br>"; // Debug line
echo "Debug: Messages = " . print_r($messages, true) . "<br>"; // Debug line

// Output HTML for the dropdown
if ($unread_count > 0) {
    echo "<span>{$unread_count} New Messages</span><ul>";
    foreach ($messages as $message) {
        $profile_pic = htmlspecialchars($message['profile_pic'] ?? 'images/default.jpg');
        $sender_name = htmlspecialchars($message['name']);
        $msg_preview = htmlspecialchars(substr($message['msg'], 0, 30)) . (strlen($message['msg']) > 30 ? '...' : '');
        $from_user_id = $message['from_user_id'];

        echo "<li>
            <a href='inbox.php?user_id={$from_user_id}' title='View Chat'>
                <img src='{$profile_pic}' alt='{$sender_name}' style='width: 30px; height: 30px; border-radius: 50%; margin-right: 10px;'>
                <strong>{$sender_name}</strong>: {$msg_preview}
            </a>
        </li>";
    }
    echo "</ul>";
} else {
    echo "<span>No new messages</span>";
}
?>