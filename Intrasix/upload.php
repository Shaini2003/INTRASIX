<?php
include 'includes/dbh.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['video'])) {
    $title = $_POST['title'];
    $video = $_FILES['video'];
    $videoName = basename($video['name']);
    $videoPath = 'uploads/' . $videoName;
    $allowedTypes = ['video/mp4', 'video/webm', 'video/ogg'];
    $allowedExtensions = ['mp4', 'webm', 'ogg'];

    // Get file extension
    $fileExtension = strtolower(pathinfo($videoName, PATHINFO_EXTENSION));

    try {
        // Check if the fileinfo extension is loaded
        if (!extension_loaded('fileinfo')) {
            throw new Exception("The Fileinfo PHP extension is not enabled. Please enable it in php.ini and restart your web server.");
        }

        // Initialize finfo with FILEINFO_MIME_TYPE
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        if (!$finfo) {
            throw new Exception("Failed to initialize finfo object. Check PHP configuration.");
        }

        // Verify file type using finfo with explicit FILEINFO_MIME_TYPE flag
        $mimeType = $finfo->file($video['tmp_name'], FILEINFO_MIME_TYPE);

        // Debug: Output MIME type for troubleshooting
        error_log("Detected MIME type: " . $mimeType);

        // Check if the MIME type and extension match allowed types
        if (!in_array($mimeType, $allowedTypes) || !in_array($fileExtension, $allowedExtensions)) {
            // Fallback: Manually check file headers if finfo fails or MIME type is unreliable
            $handle = fopen($video['tmp_name'], 'rb');
            $header = fread($handle, 4); // Read first 4 bytes for signature
            fclose($handle);

            $isValidVideo = false;
            if (strpos($header, "\x00\x00\x01") === 0 || strpos($header, "ftyp") === 0) { // MP4 signature
                $isValidVideo = $fileExtension === 'mp4' && in_array($mimeType, ['video/mp4', 'application/octet-stream']);
            } elseif (strpos($header, "WEBM") === 0) { // WebM signature
                $isValidVideo = $fileExtension === 'webm' && in_array($mimeType, ['video/webm', 'application/octet-stream']);
            } elseif (strpos($header, "OggS") === 0) { // OGG signature
                $isValidVideo = $fileExtension === 'ogg' && in_array($mimeType, ['video/ogg', 'application/ogg']);
            }

            if (!$isValidVideo) {
                echo "Error: Only MP4, WebM, and OGG videos are allowed. Uploaded file type: " . htmlspecialchars($mimeType) . " (Extension: " . htmlspecialchars($fileExtension) . ")";
                exit;
            }
        }

        // Check file size (e.g., max 100MB)
        if ($video['size'] > 100 * 1024 * 1024) {
            echo "Error: Video size must be less than 100MB.";
            exit;
        }

        // Check if the uploads directory exists and is writable
        if (!file_exists('uploads')) {
            if (!mkdir('uploads', 0777, true)) {
                throw new Exception("Failed to create uploads directory. Check permissions.");
            }
        }

        if (!is_writable('uploads')) {
            throw new Exception("Uploads directory is not writable. Check permissions (e.g., chmod 755 or 777 on Linux, or adjust Windows permissions).");
        }

        // Move file to uploads directory
        if (move_uploaded_file($video['tmp_name'], $videoPath)) {
            // Save to database
            $sql = "INSERT INTO videos (title, filename) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Failed to prepare SQL statement: " . $conn->error);
            }

            $stmt->bind_param("ss", $title, $videoName);
            if ($stmt->execute()) {
                header("Location: index.php");
                exit;
            } else {
                echo "Error: Failed to save video metadata. " . $stmt->error;
                unlink($videoPath); // Clean up the uploaded file if database insertion fails
            }
            $stmt->close();
        } else {
            echo "Error: Failed to upload video. Check directory permissions, disk space, or file size limits.";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        // Clean up if the file was partially uploaded
        if (file_exists($videoPath)) {
            unlink($videoPath);
        }
        exit;
    } finally {
        if (isset($conn) && $conn instanceof mysqli) {
            $conn->close();
        }
        if (isset($finfo)) {
            unset($finfo); // Clean up finfo object
        }
    }
} else {
    echo "Error: No video file uploaded.";
}