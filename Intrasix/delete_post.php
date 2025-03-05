<?php
session_start();
include("includes/dbh.php"); // Database connection

if (!isset($_SESSION['id'])) {
    die("Unauthorized access");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post_id'])) {
    $post_id = intval($_POST['post_id']);
    $user_id = $_SESSION['id']; // Logged-in user's ID

    // Fetch the post to verify ownership
    $query = "SELECT post_img FROM posts WHERE id = $post_id AND user_id = $user_id";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $image_path = "images/posts/" . $row['post_img']; // Corrected path

        // Delete the image file if it exists
        if (!empty($row['post_img']) && file_exists($image_path)) {
            unlink($image_path);
        }

        // Delete the post from the database
        $delete_query = "DELETE FROM posts WHERE id = $post_id AND user_id = $user_id";
        if (mysqli_query($conn, $delete_query)) {
            header("Location: index.php?message=Post deleted successfully");
            exit();
        } else {
            echo "Error deleting post.";
        }
    } else {
        echo "Post not found or unauthorized action.";
    }
} else {
    echo "Invalid request.";
}
?>