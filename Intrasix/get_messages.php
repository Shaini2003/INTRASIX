<?php
session_start();
include 'includes/dbh.php'; // Database connection

if (!isset($_SESSION['id'])) {
    echo '<span>Please log in to see messages</span>';
    exit();
}

$current_user_id = $_SESSION['id'];

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

// Output HTML for the dropdown
$output = "<span>$unread_count New Messages</span>";
$output .= '<ul class="drops-menu">';

if ($unread_count > 0) {
    foreach ($messages as $message) {
        $profile_pic = htmlspecialchars($message['profile_pic'] ?? 'images/default.jpg', ENT_QUOTES, 'UTF-8');
        $sender_name = htmlspecialchars($message['name'], ENT_QUOTES, 'UTF-8');
        $msg_preview = htmlspecialchars(substr($message['msg'], 0, 30), ENT_QUOTES, 'UTF-8') . (strlen($message['msg']) > 30 ? '...' : '');
        $from_user_id = (int)$message['from_user_id'];

        $output .= "<li>";
        $output .= "<a href='inbox.php?user_id=$from_user_id' title='View Chat'>";
        $output .= "<img src='$profile_pic' alt='$sender_name'>";
        $output .= "<div class='mesg-meta'>";
        $output .= "<h6>$sender_name</h6>";
        $output .= "<span>$msg_preview</span>";
        $output .= "</div>";
        $output .= "</a>";
        $output .= "</li>";
    }
} else {
    $output .= "<li><span>No new messages</span></li>";
}

$output .= "</ul>";
$output .= "<a href='inbox.php' title='' class='more-mesg'>View All Messages</a>";

echo $output;

$stmt->close();
$conn->close();
?>