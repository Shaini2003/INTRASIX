<?php 
session_start();
include 'includes/dbh.php'; // Database connection

// Ensure user is logged in
if (!isset($_SESSION['id'])) {
    die("Unauthorized access!");
}

$current_user_id = $_SESSION['id'];

// Fetch followers (users who follow the current user)
$stmt = $conn->prepare("
    SELECT u.id, u.name, u.profile_pic 
    FROM users u 
    JOIN follow_list fl ON u.id = fl.follower_id 
    WHERE fl.following_id = ?
");
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$followers = $stmt->get_result();

// Fetch suggested users (users the current user doesn't follow yet, excluding self)
$stmt = $conn->prepare("
    SELECT id, name, profile_pic 
    FROM users 
    WHERE id NOT IN (
        SELECT following_id 
        FROM follow_list 
        WHERE follower_id = ?
    ) AND id != ?
");
$stmt->bind_param("ii", $current_user_id, $current_user_id);
$stmt->execute();
$suggested = $stmt->get_result();

// Handle follow action
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['follow'])) {
    $following_id = intval($_POST['following_id']);
    $stmt = $conn->prepare("
        INSERT INTO follow_list (follower_id, following_id, created_at) 
        VALUES (?, ?, NOW())
    ");
    $stmt->bind_param("ii", $current_user_id, $following_id);
    $stmt->execute();
    header("Location: index.php");
    exit();
}

// Handle followback action
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['follow'])) {
    $following_id = intval($_POST['following_id']);
    $stmt = $conn->prepare("
        INSERT INTO follow_list (follower_id, following_id, created_at) 
        VALUES (?, ?, NOW())
    ");
    $stmt->bind_param("ii", $current_user_id, $following_id);
    $stmt->execute();
    header("Location: index.php");
    exit();
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intrasix - Inbox</title>
    <link rel="icon" href="images/wink.png" type="image/png" sizes="16x16"> 
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Arial, sans-serif;
    }
    body {
        display: flex;
        flex-wrap: wrap; /* Allow wrapping on smaller screens */
        height: 100vh;
        background-image: url("images/intrasix-logo.png");
    }
    .sidebar-container {
        width: 50%; /* Two equal columns on desktop */
        display: flex;
        flex-direction: column;
    }
    .followers-list, .suggested-list, .chat-list {
        background-color: #9b59b6;
        color: white;
        padding: 20px;
        overflow-y: auto;
        flex: 1; /* Allow equal height */
    }
    .chat-container {
        width: 25%;
        display: flex;
        flex-direction: column;
        background: #fff;
        border-left: 1px solid #ddd;
    }
    .followers-list h3, .chat-list h3, .suggested-list h3 {
        text-align: center;
        margin-bottom: 15px;
    }
    .followers-list ul, .chat-list ul, .suggested-list ul {
        list-style: none;
    }
    .followers-list li, .chat-list li, .suggested-list li {
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: background 0.3s;
    }
    .chat-list li:hover, .chat-list li.active {
        background: #9b59b6; /* Slightly darker shade for hover to maintain contrast */
    }
    .followers-list img, .chat-list img, .suggested-list img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 10px;
        object-fit: cover;
    }
    .chat-list li a {
        color: white;
        text-decoration: none;
        display: flex;
        align-items: center;
        width: 100%;
    }
    .follow-btn, .unfollow-btn {
        padding: 5px 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
    }
    .follow-btn {
        background: black;
        color: white;
    }
    .unfollow-btn {
        background: black;
        color: white;
    }
    .chat-header {
        background: rgb(245, 154, 204);
        color: white;
        padding: 15px;
        font-size: 18px;
        text-align: center;
    }
    .chat-messages {
        flex: 1;
        padding: 15px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        background: gray;
    }
    .chat-messages div {
        max-width: 60%;
        padding: 10px;
        border-radius: 10px;
        margin-bottom: 10px;
        word-wrap: break-word;
    }
    .sent {
        align-self: flex-end;
        background: #9b59b6;
        color: white;
    }
    .received {
        align-self: flex-start;
        background: #ffffff;
        border: 1px solid #ddd;
    }
    .chat-input {
        display: flex;
        padding: 10px;
        background: #fff;
        border-top: 1px solid #ddd;
    }
    .chat-input input {
        flex: 1;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }
    .chat-input button {
        margin-left: 10px;
        padding: 10px 15px;
        background: #3498db;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }
    .chat-input button:hover {
        background: #2980b9;
    }

    /* Responsive Styles */
    @media (max-width: 1024px) {
        .sidebar-container {
            width: 50%; /* Maintain two columns on tablets */
        }
        .chat-list {
            width: 50%;
        }
        .chat-container {
            width: 50%;
        }
        .suggested-list img, .followers-list img, .chat-list img {
            width: 35px;
            height: 35px;
        }
        .follow-btn, .unfollow-btn {
            padding: 4px 8px;
            font-size: 12px;
        }
    }

    @media (max-width: 768px) {
        .sidebar-container {
            width: 50%; /* Two columns on smaller tablets */
        }
        .chat-list {
            width: 50%;
        }
        .chat-container {
            width: 100%;
            order: 3; /* Move chat container below sidebars */
        }
        .suggested-list img, .followers-list img, .chat-list img {
            width: 30px;
            height: 30px;
        }
        .suggested-list li, .followers-list li, .chat-list li {
            padding: 8px;
        }
        .follow-btn, .unfollow-btn {
            padding: 3px 6px;
            font-size: 10px;
        }
    }

    @media (max-width: 480px) {
        .sidebar-container {
            width: 100%; /* Full width on mobile, stack vertically */
        }
        .followers-list, .suggested-list, .chat-list {
            width: 100%;
        }
        .chat-container {
            width: 100%;
            order: 3;
        }
        .suggested-list img, .followers-list img, .chat-list img {
            width: 25px;
            height: 25px;
        }
        .suggested-list li, .followers-list li, .chat-list li {
            padding: 6px;
            flex-wrap: wrap; /* Allow wrapping if content is too long */
        }
        .suggested-list li div, .followers-list li div, .chat-list li div {
            flex: 1 1 70%; /* Name takes most space */
        }
        .suggested-list li form, .followers-list li form {
            flex: 1 1 30%; /* Button takes less space */
            text-align: right;
        }
        .follow-btn, .unfollow-btn {
            padding: 3px 5px;
            font-size: 9px;
        }
    }
</style>
</head>
<body>
<!-- Followers List -->
<div class="followers-list">
    <h3>My Followers</h3>
    <ul>
        <?php while ($follower = $followers->fetch_assoc()): ?>
            <li>
                <div style="display: flex; align-items: center;">
                    <img src="<?= htmlspecialchars($follower['profile_pic'] ?? 'images/default.jpg') ?>" 
                         alt="<?= htmlspecialchars($follower['name']) ?>'s profile">
                    <span><?= htmlspecialchars($follower['name']) ?></span>
                </div>
                <form method="post">
                    <input type="hidden" name="following_id" value="<?= $follower['id'] ?>">
                    <button type="submit" name="follow" class="follow-btn">Follow</button>
                </form>
            </li>
        <?php endwhile; ?>
    </ul>
</div>


<!-- Suggested Followers -->
<div class="suggested-list" style="background-color: white; font-weight: bold;">
    <h3 style="color: black;font-weight: bold;">Suggested Friends</h3>
    <ul>
        <?php while ($suggest = $suggested->fetch_assoc()): ?>
            <li>
                <div style="display: flex; align-items: center;color: black;">
                    <img src="<?= htmlspecialchars($suggest['profile_pic'] ?? 'images/default.jpg') ?>" 
                         alt="<?= htmlspecialchars($suggest['name']) ?>'s profile">
                    <span><?= htmlspecialchars($suggest['name']) ?></span>
                </div>
                <form method="post">
                    <input type="hidden" name="following_id" value="<?= $suggest['id'] ?>">
                    <button type="submit" name="follow" class="follow-btn">Follow</button>
                </form>
            </li>
        <?php endwhile; ?>
    </ul>
</div>



</body>
</html>