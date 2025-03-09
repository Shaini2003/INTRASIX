<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    // Store verified user ID in the session
    $_SESSION['verified_users'][$user_id] = true;

    // Redirect back to main dashboard
    header("Location: main.php");
    exit;
}
?>