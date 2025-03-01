<?php
include 'includes/dbh.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = 1; // Change this to dynamically get the logged-in user ID
    $upload_dir = "uploads/";

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $image = $_FILES['story_img'];
    $image_name = time() . "_" . basename($image['name']);
    $target_file = $upload_dir . $image_name;

    if (move_uploaded_file($image['tmp_name'], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO stories (user_id, story_img) VALUES (?, ?)");
        $stmt->bind_param("is", $user_id, $target_file);

        if ($stmt->execute()) {
            header("Location: index.php?=new_story_added");
        } else {
            header("Location: index.php?=Error uploading story.");
        }
        $stmt->close();
    } else {
        header("Location: index.php?=Error uploading file.");
    }
}
?>
