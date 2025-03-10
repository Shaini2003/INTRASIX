<?php
// Database connection
include 'config.php';

// Check if review_id is set and delete the review
if (isset($_POST['review_id']) && !empty($_POST['review_id'])) {
    $review_id = $_POST['review_id'];

    // Prepare and execute delete query
    $sql = "DELETE FROM review_table WHERE review_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $review_id); // "i" indicates integer type

    if ($stmt->execute()) {
        // Redirect back to view_reviews.php with success message
        header("Location: view_reviews.php?message=Review deleted successfully");
    } else {
        // Redirect with error message
        header("Location: view_reviews.php?message=Error deleting review");
    }

    $stmt->close();
} else {
    // Redirect if no review_id is provided
    header("Location: view_reviews.php?message=Invalid request");
}

$conn->close();
?>