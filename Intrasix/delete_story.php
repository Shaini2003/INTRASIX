<?php
session_start();
include 'includes/dbh.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $story_id = $_POST['story_id'] ?? null;
    $user_id = $_SESSION['id'] ?? null;

    if (!$story_id || !$user_id) {
        die("❌ Error: Invalid request.");
    }

    // Fetch the story to verify ownership and get image path
    $stmt = $conn->prepare("SELECT story_img FROM stories WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $story_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $image_path = $row['story_img'];

        // Delete story from database
        $delete_stmt = $conn->prepare("DELETE FROM stories WHERE id = ? AND user_id = ?");
        $delete_stmt->bind_param("ii", $story_id, $user_id);
        
        if ($delete_stmt->execute()) {
            // Remove the image file
            if (file_exists($image_path)) {
                unlink($image_path);
            }
            header("Location: index.php?message=Story deleted");
        } else {
            die("❌ Error: Could not delete story.");
        }
    } else {
        die("❌ Error: Story not found or unauthorized.");
    }
}
?>