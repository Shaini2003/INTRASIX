<?php
session_start();

// Check if the user ID is set and valid
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    // Check if the user is already blocked
    if (isset($_SESSION['blocked_users'][$user_id])) {
        // Unblock the user
        unset($_SESSION['blocked_users'][$user_id]);
    } else {
        // Block the user
        $_SESSION['blocked_users'][$user_id] = true;
    }

    // Redirect back to the main dashboard
    header("Location: main.php");
    exit;
}
?>