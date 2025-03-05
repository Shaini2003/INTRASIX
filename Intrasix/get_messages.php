<?php
session_start();
include 'includes/dbh.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['id'])) {
    echo "<span>Please log in</span>";
    exit;
}

$user_id = $_SESSION['id'];

// Fetch recent messages (received by the user)
$query = "SELECT c.chat_id, c.from_user_id, c.msg, c.start_time, u.name, u.profile_pic 
          FROM chat c 
          JOIN users u ON c.from_user_id = u.id 
          WHERE c.to_user_id = ? 
          ORDER BY c.start_time DESC 
          LIMIT 5";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$messages = [];
while ($row = mysqli_fetch_assoc($result)) {
    $time_ago = timeAgo($row['start_time']);
    $messages[] = [
        'chat_id' => $row['chat_id'],
        'sender_name' => $row['name'],
        'profile_pic' => $row['profile_pic'] ?? 'images/default.jpg',
        'message' => $row['msg'],
        'time_ago' => $time_ago
    ];
}

// Count unread messages (assuming we track read status elsewhere, here we'll simulate)
$count_query = "SELECT COUNT(*) as unread FROM chat WHERE to_user_id = ? AND start_time > (SELECT IFNULL(MAX(start_time), '1970-01-01') FROM chat WHERE from_user_id = ?)";
$count_stmt = mysqli_prepare($conn, $count_query);
mysqli_stmt_bind_param($count_stmt, "ii", $user_id, $user_id);
mysqli_stmt_execute($count_stmt);
$count_result = mysqli_stmt_get_result($count_stmt);
$unread_count = mysqli_fetch_assoc($count_result)['unread'];

// Output HTML
echo "<span>$unread_count New Messages</span>";
echo '<ul class="drops-menu">';
if (empty($messages)) {
    echo "<li><div class='mesg-meta'>No new messages</div></li>";
} else {
    foreach ($messages as $msg) {
        $message_text = strlen($msg['message']) > 20 ? substr($msg['message'], 0, 20) . "..." : $msg['message'];
        echo "<li>";
        echo "<a href='inbox.php?user_id={$msg['from_user_id']}' title=''>";
        echo "<img src='{$msg['profile_pic']}' alt='{$msg['sender_name']}'>";
        echo "<div class='mesg-meta'>";
        echo "<h6>{$msg['sender_name']}</h6>";
        echo "<span>{$message_text}</span>";
        echo "<i>{$msg['time_ago']}</i>";
        echo "</div>";
        echo "</a>";
        echo "<span class='tag green'>New</span>";
        echo "</li>";
    }
}
echo "</ul>";
echo "<a href='inbox.php' title='' class='more-mesg'>View more</a>";

function timeAgo($datetime) {
    $time = strtotime($datetime);
    $diff = time() - $time;
    if ($diff < 60) return "$diff sec ago";
    $diff = round($diff / 60);
    if ($diff < 60) return "$diff min ago";
    $diff = round($diff / 60);
    if ($diff < 24) return "$diff hour" . ($diff > 1 ? "s" : "") . " ago";
    $diff = round($diff / 24);
    return "$diff day" . ($diff > 1 ? "s" : "") . " ago";
}

mysqli_stmt_close($stmt);
mysqli_stmt_close($count_stmt);
mysqli_close($conn);
?>