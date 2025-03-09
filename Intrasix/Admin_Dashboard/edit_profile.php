<?php
session_start();
include 'config.php'; // Include your database connection file

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

$admin_id = $_SESSION['admin_id'];
$query = "SELECT * FROM admin WHERE admin_id = '$admin_id'";
$result = mysqli_query($conn, $query);
$admin = mysqli_fetch_assoc($result);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $update_query = "UPDATE admin SET email='$email', password='$hashed_password' WHERE admin_id='$admin_id'";
    } else {
        $update_query = "UPDATE admin SET email='$email' WHERE admin_id='$admin_id'";
    }
    
    if (mysqli_query($conn, $update_query)) {
        echo "<script>alert('Profile updated successfully!');</script>";
        echo "<script>window.location.href='edit_profile.php';</script>";
    } else {
        echo "<script>alert('Error updating profile.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
</head>
<body>
    <div class="d-flex" id="wrapper">
        <div class="bg-dark border-end text-white" id="sidebar-wrapper">
            <div class="sidebar-heading text-center py-3">IntraSix</div>
            <div class="list-group list-group-flush">
                <a href="main.php" class="list-group-item list-group-item-action bg-dark text-white">Dashboard</a>
                <a href="edit_profile.php" class="list-group-item list-group-item-action bg-dark text-white">Edit Profile</a>
            </div>
        </div>
        
        <div id="page-content-wrapper" class="w-100 p-3">
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <div class="container-fluid">
                    <a href="logout.php" class="btn btn-danger">Logout</a>
                </div>
            </nav>
            
            <div class="container-fluid mt-4">
                <h4>Edit Profile</h4>
                <div class="card p-4">
                    <h5 class="card-header bg-primary text-white">Edit Your Profile</h5>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo $admin['email']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">New Password (Leave blank to keep current password)</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter new password">
                            </div>
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>