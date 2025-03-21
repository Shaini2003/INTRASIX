<?php
include 'includes/dbh.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $video = $_FILES['video'];

    // Debug: Check file upload error
    if ($video['error'] !== UPLOAD_ERR_OK) {
        echo "Upload failed with error code: " . $video['error'];
        exit();
    }

    $uploadDir = 'uploads/';
    $fileName = basename($video['name']);
    $targetFile = $uploadDir . time() . '_' . $fileName;
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Debug: Check directory
    if (!is_dir($uploadDir) || !is_writable($uploadDir)) {
        echo "Uploads directory issue: " . (!is_dir($uploadDir) ? "does not exist" : "not writable");
        exit();
    }

    // Debug: Check temporary file
    if (!file_exists($video['tmp_name']) || !is_uploaded_file($video['tmp_name'])) {
        echo "Temporary file is missing or invalid.";
        exit();
    }

    $allowedTypes = array('mp4', 'webm', 'ogg');
    if (in_array($fileType, $allowedTypes)) {
        if ($video['size'] <= 50000000) {
            if (move_uploaded_file($video['tmp_name'], $targetFile)) {
                $sql = "INSERT INTO videos (title, filename, upload_date) VALUES (?, ?, NOW())";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $title, basename($targetFile));

                if ($stmt->execute()) {
                    header("Location: video.php?upload=success");
                    exit();
                } else {
                    echo "Database error: " . $conn->error;
                }
                $stmt->close();
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "File is too large. Maximum size is 50MB.";
        }
    } else {
        echo "Invalid file type. Only MP4, WEBM, and OGG files are allowed.";
    }
} else {
    echo "No file uploaded.";
}

$conn->close();
?>