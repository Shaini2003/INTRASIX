<?php
session_start();
include 'includes/dbh.php'; // Database connection

if (!isset($_SESSION['id'])) {
    die("Unauthorized access!");
}

$current_user_id = $_SESSION['id'];

// Fetch all users except the logged-in user with profile picture
$stmt = $conn->prepare("SELECT id, name, profile_pic FROM users WHERE id != ?");
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$friends = $stmt->get_result();

// Set default chat user (if not selected)
$chat_user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : null;

// Mark messages as read when viewing the chat
if ($chat_user_id) {
    $stmt = $conn->prepare("
        UPDATE chat 
        SET is_read = 1 
        WHERE to_user_id = ? AND from_user_id = ? AND is_read = 0
    ");
    $stmt->bind_param("ii", $current_user_id, $chat_user_id);
    $stmt->execute();
}

// Fetch chat messages
$chatMessages = null;
if ($chat_user_id) {
    $stmt = $conn->prepare("
        SELECT c.chat_id, c.from_user_id, c.to_user_id, c.start_time, c.msg, u.name AS sender_name, u.profile_pic 
        FROM chat c
        JOIN users u ON c.from_user_id = u.id
        WHERE (c.from_user_id = ? AND c.to_user_id = ?) OR (c.from_user_id = ? AND c.to_user_id = ?) 
        ORDER BY c.start_time ASC
    ");
    $stmt->bind_param("iiii", $current_user_id, $chat_user_id, $chat_user_id, $current_user_id);
    $stmt->execute();
    $chatMessages = $stmt->get_result();
}

// Handle sending messages
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_message'])) {
    $message = trim($_POST['chat_message']);
    if (!empty($message) && $chat_user_id) {
        $stmt = $conn->prepare("
            INSERT INTO chat (from_user_id, to_user_id, start_time, msg, is_read) 
            VALUES (?, ?, NOW(), ?, 0)
        ");
        $stmt->bind_param("iis", $current_user_id, $chat_user_id, $message);
        $stmt->execute();
        header("Location: inbox.php?user_id=" . $chat_user_id);
        exit();
    }
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
            height: 100vh;
            background: #f4f4f4;
        }
        .chat-list {
            width: 25%;
            background: rgb(175, 30, 180);
            color: white;
            padding: 30px;
            overflow-y: auto;
        }
        .chat-list h3 {
            text-align: center;
            margin-bottom: 15px;
        }
        .chat-list ul {
            list-style: none;
        }
        .chat-list li {
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 5px;
            cursor: pointer;
            transition: background 0.3s;
            display: flex;
            align-items: center;
        }
        .chat-list li a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            width: 100%;
        }
        .chat-list li:hover, .chat-list li.active {
            background: rgb(216, 167, 212);
        }
        .chat-list img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            object-fit: cover;
        }
        .chat-container {
            width: 75%;
            display: flex;
            flex-direction: column;
            background: #fff;
            border-left: 1px solid #ddd;
        }
        .chat-header {
            background: #9b59b6;
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
            background: #95a5a6;
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
            background: #2c3e50;
            color: white;
        }
        .received {
            align-self: flex-start;
            background: rgb(255, 255, 255);
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
    </style>
</head>
<body>
<div class="chat-list">
    <a href="index.php" style="text-decoration: none; font-weight: bold;">←Back</a>
    <h3>Chat List</h3>
    <ul>
        <?php while ($friend = $friends->fetch_assoc()): ?>
            <li class="<?= ($chat_user_id == $friend['id']) ? 'active' : '' ?>">
                <a href="inbox.php?user_id=<?= $friend['id'] ?>">
                    <img src="<?= htmlspecialchars($friend['profile_pic'] ?? 'images/default.jpg') ?>" 
                         alt="<?= htmlspecialchars($friend['name']) ?>'s profile">
                    <span><?= htmlspecialchars($friend['name']) ?></span>
                </a>
            </li>
        <?php endwhile; ?>
    </ul>
</div>

<div class="chat-container">
    <?php if ($chat_user_id): ?>
        <div class="chat-box">
            <div class="chat-header">
                <?php
                $stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
                $stmt->bind_param("i", $chat_user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $chat_user = $result->fetch_assoc();
                ?>
                <h4>Chat with <?= htmlspecialchars($chat_user['name']) ?></h4>
            </div>
            <div class="chat-messages" id="chatMessages">
                <?php while ($message = $chatMessages->fetch_assoc()): ?>
                    <div class="<?= $message['from_user_id'] == $current_user_id ? 'sent' : 'received' ?>">
                        <strong><?= htmlspecialchars($message['sender_name']) ?>:</strong> 
                        <?= htmlspecialchars($message['msg']) ?>
                    </div>
                <?php endwhile; ?>
            </div>
            <form method="post">
                <div class="chat-input">
                    <input type="text" name="chat_message" id="chatMessage" placeholder="Type your message..." required>
                    <button type="submit" name="send_message">Send</button>
                </div>
            </form>
        </div>
    <?php else: ?>
        <p style="text-align:center; padding:20px;">Select a user to start chatting...</p>
    <?php endif; ?>
</div>
</body>
</html>