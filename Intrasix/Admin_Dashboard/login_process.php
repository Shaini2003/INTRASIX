<?php
// login_process.php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute query
    $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $admin = $result->fetch_assoc();
        // In production, use password_verify() with hashed passwords
        if ($password === $admin['password']) { // Simple comparison for demo
            $_SESSION['admin_id'] = $admin['admin_id'];
            header("Location: main.php?message=Post deleted successfully");
            exit();
        } else {
            $_SESSION['error'] = "Invalid password";
        }
    } else {
        $_SESSION['error'] = "Invalid email";
    }
    
    $stmt->close();
    header("Location: index.php");
    exit();
}

$conn->close();
?>