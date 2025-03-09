<?php
include 'config.php';

if (isset($_GET['email'])) {
    $user_email = $conn->real_escape_string($_GET['email']);

    // Fetch user details
    $sql = "SELECT * FROM users WHERE email = '$user_email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "User not found.";
        exit;
    }
} else {
    echo "Invalid request.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $user['name']; ?>'s Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2><?php echo $user['name']; ?>'s Profile</h2>
        <img src="<?php echo !empty($user['profile_pic']) ? "uploads/{$user['profile_pic']}" : "uploads/default.png"; ?>" 
             width="100" height="100" style="border-radius:50%; object-fit:cover;">
        <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
        <p><strong>Gender:</strong> <?php echo $user['gender']; ?></p>
        <p><strong>Town:</strong> <?php echo $user['town']; ?></p>
        <p><strong>Date of Birth:</strong> <?php echo $user['dob']; ?></p>
        <a href="main.php" class="btn btn-primary">Back to Dashboard</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>