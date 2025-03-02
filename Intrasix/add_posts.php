<?php
session_start();
include("includes/dbh.php"); // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $response = validatePostImage($_FILES['post_img']);

    if ($response['status']) {
        if (createPost($_POST, $_FILES['post_img'])) {
            header("Location: index.php?=new_post_added");
            exit();
        } else {
            echo "Something went wrong";
        }
    } else {
        $_SESSION['error'] = $response;
        header("Location: ../../");
        exit();
    }
}

function validatePostImage($image_data) {
    $response = ['status' => true];

    if (!$image_data['name']) {
        $response['msg'] = "No image is selected";
        $response['status'] = false;
        $response['field'] = 'post_img';
        return $response;
    }

    $image = basename($image_data['name']);
    $type = strtolower(pathinfo($image, PATHINFO_EXTENSION));
    $size = $image_data['size'] / 1024; // Convert bytes to KB

    if (!in_array($type, ['jpg', 'jpeg', 'png'])) {
        $response['msg'] = "Only JPG, JPEG, PNG images are allowed";
        $response['status'] = false;
        $response['field'] = 'post_img';
    }

    if ($size > 1000) {
        $response['msg'] = "Upload image less than 1MB";
        $response['status'] = false;
        $response['field'] = 'post_img';
    }

    return $response;
}

function createPost($text, $image) {
    global $conn;

    if (!isset($_SESSION['id'])) {
        return false; // Ensure user is logged in
    }

    $post_text = mysqli_real_escape_string($conn, $text['post_text']);
    $user_id = $_SESSION['id'];

    // Define the image upload path
    $upload_dir = __DIR__ . "/images/posts/";

    // Ensure directory exists
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Define the final image path
    $image_name = time() . "_" . basename($image['name']);
    $image_path = $upload_dir . $image_name;

    if (move_uploaded_file($image['tmp_name'], $image_path)) {
        $query = "INSERT INTO posts (user_id, post_text, post_img) VALUES ($user_id, '$post_text', '$image_name')";
        return mysqli_query($conn, $query);
    }

    return false; // Return false if image upload fails
}