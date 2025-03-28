<?php
session_start();
include 'includes/dbh.php';

if (!isset($_SESSION['id'])) {
    echo "<span>User not logged in</span>";
    exit;
}

$user_id = $_SESSION['id'];

// Fetch notifications
$query = "SELECT n.id, n.actor_id, n.post_id, n.type, n.created_at, n.is_read, u.name, u.profile_pic 
          FROM notifications n 
          JOIN users u ON n.actor_id = u.id 
          WHERE n.user_id = ? 
          ORDER BY n.created_at DESC 
          LIMIT 5";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$notifications = [];
while ($row = mysqli_fetch_assoc($result)) {
    $time_ago = timeAgo($row['created_at']);
    $notifications[] = [
        'id' => $row['id'],
        'actor_name' => $row['name'],
        'profile_pic' => $row['profile_pic'] ?? 'images/default.jpg',
        'post_id' => $row['post_id'],
        'type' => $row['type'],
        'time_ago' => $time_ago,
        'is_read' => $row['is_read']
    ];
}

// Count unread notifications
$count_query = "SELECT COUNT(*) as unread FROM notifications WHERE user_id = ? AND is_read = 0";
$count_stmt = mysqli_prepare($conn, $count_query);
mysqli_stmt_bind_param($count_stmt, "i", $user_id);
mysqli_stmt_execute($count_stmt);
$count_result = mysqli_stmt_get_result($count_stmt);
$unread_count = mysqli_fetch_assoc($count_result)['unread'];

// Output notifications as HTML
$output = "<span>$unread_count New Notifications</span>";
$output .= '<ul class="drops-menu">';
foreach ($notifications as $notif) {
    $message = $notif['type'] === 'like' ? "liked your post" : "commented on your post";
    $tag_class = $notif['is_read'] ? '' : 'green';
    $output .= "<li>";
    $output .= "<a href='profile.php?id={$notif['post_id']}' title=''>";
    $output .= "<img src='{$notif['profile_pic']}' alt=''>";
    $output .= "<div class='mesg-meta'>";
    $output .= "<h6>{$notif['actor_name']}</h6>";
    $output .= "<span>$message</span>";
    $output .= "<i>{$notif['time_ago']}</i>";
    $output .= "</div>";
    $output .= "</a>";
    if (!$notif['is_read']) {
        $output .= "<span class='tag $tag_class'>New</span>";
    }
    $output .= "</li>";
}
$output .= "</ul>";
$output .= "<a href='notifications.php' title='' class='more-mesg'>view more</a>";

echo $output;

// Updated timeAgo function to handle negative timestamps
function timeAgo($datetime) {
    $time = strtotime($datetime);
    $current_time = time();
    
    // If the timestamp is in the future, return "just now"
    if ($time > $current_time) {
        return "just now";
    }

    $diff = $current_time - $time;
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