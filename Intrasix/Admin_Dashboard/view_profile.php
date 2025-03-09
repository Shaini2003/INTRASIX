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

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$admin = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-detail {
            margin-bottom: 1rem;
        }
        .profile-label {
            font-weight: bold;
            color: #4285f4;
        }
    </style>
</head>
<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div class="bg-dark border-end text-white" id="sidebar-wrapper">
            <div class="sidebar-heading text-center py-3">IntraSix</div>
            <div class="list-group list-group-flush">
                <a href="main.php" class="list-group-item list-group-item-action bg-dark text-white">Dashboard</a>
                <a href="view_profile.php" class="list-group-item list-group-item-action bg-dark text-white active">View Profile</a>
                <a href="edit_profile.php" class="list-group-item list-group-item-action bg-dark text-white">Edit Profile</a>
            </div>
        </div>

        <!-- Page Content -->
        <div id="page-content-wrapper" class="w-100 p-3">
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <div class="container-fluid">
                    <a href="logout.php" class="btn btn-danger">Logout</a>
                </div>
            </nav>

            <div class="container-fluid mt-4">
                <h4>Profile Information</h4>
                <div class="card p-4">
                    <h5 class="card-header bg-primary text-white">Your Profile Details</h5>
                    <div class="card-body">
                        <div class="profile-detail">
                            <span class="profile-label">Admin ID:</span>
                            <span><?php echo htmlspecialchars($admin['admin_id']); ?></span>
                        </div>
                        <div class="profile-detail">
                            <span class="profile-label">Email:</span>
                            <span><?php echo htmlspecialchars($admin['email']); ?></span>
                        </div>
                        <div class="profile-detail">
                            <span class="profile-label">Last Updated:</span>
                            <span>
                                <?php 
                                // Assuming you have a 'last_updated' field in your admin table
                                // If not, remove this section or modify according to your database
                                echo isset($admin['last_updated']) ? 
                                    date('F j, Y, g:i a', strtotime($admin['last_updated'])) : 
                                    'Not available';
                                ?>
                            </span>
                        </div>
                        <a href="edit_profile.php" class="btn btn-primary mt-3">Edit Profile</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>