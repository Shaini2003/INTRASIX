<?php  
session_start();
include 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

// Get total users count
$sql = "SELECT COUNT(id) AS total_users FROM users";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$total_users = $row['total_users'];

// Get total posts count
$sql = "SELECT COUNT(id) AS total_posts FROM posts";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$total_posts = $row['total_posts'];

// Get total comments count
$sql = "SELECT COUNT(id) AS total_comments FROM comments";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$total_comments = $row['total_comments'];

// Get total likes count
$sql = "SELECT COUNT(id) AS total_likes FROM likes";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$total_likes = $row['total_likes'];

// Fetch users data from the database, including profile_pic
$sql = "SELECT id, name, email, profile_pic FROM users";
$result = $conn->query($sql);

// Initialize counter
$count = 1;

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
</head>
<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div class="bg-dark border-end text-white" id="sidebar-wrapper">
            <div class="sidebar-heading text-center py-3">IntraSix</div>
            <div class="list-group list-group-flush">
                <a href="main.php" class="list-group-item list-group-item-action bg-dark text-white">Dashboard</a>
                <a href="edit_profile.php" class="list-group-item list-group-item-action bg-dark text-white">Edit Profile</a>
            </div>
        </div>
        
        <!-- Page Content -->
        <div id="page-content-wrapper" class="w-100 p-3">
            <!-- Top Navigation Bar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <div class="container-fluid">
                    <button class="btn btn-danger">Logout</button>
                </div>
            </nav>
            
            <div class="container-fluid mt-4">
                <h4>Admin Dashboard</h4>
                
                <!-- Stats Cards -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="card bg-info text-white p-3">
                            <h4><?php echo $total_users; ?></h4>
                            <p>Total Users</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white p-3">
                            <h4><?php echo $total_posts; ?></h4>
                            <p>Total Posts</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card bg-warning text-white p-3 text-center">
                            <h4><?php echo $total_comments; ?></h4>
                            <p>Total Comments</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card bg-danger text-white p-3 text-center">
                            <h4><?php echo $total_likes; ?></h4>
                            <p>Total Likes</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User List -->
    <div class="container mt-4">
        <h2>User Lists</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#No</th>
                    <th>User</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php 
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $user_id = $row['id'];
                    $user_name = $row['name'];
                    $user_email = $row['email'];
                    $profile_pic = $row['profile_pic'];

                    // Check if this user is verified (stored in session)
                    if (isset($_SESSION['verified_users'][$user_id])) {
                        $verify_button_text = "Verified";
                        $verify_button_class = "btn btn-peach"; // Peach color for verified users
                    } else {
                        $verify_button_text = "Verify";
                        $verify_button_class = "btn btn-warning"; // Yellow color for unverified users
                    }

                    // Check if this user is blocked (stored in session)
                    if (isset($_SESSION['blocked_users'][$user_id])) {
                        $block_button_text = "Unblock";
                        $block_button_class = "btn btn-primary"; // Blue color for unblock button
                    } else {
                        $block_button_text = "Block";
                        $block_button_class = "btn btn-danger"; // Red color for block button
                    }

                    echo "<tr>
                        <td>#{$count}</td>
                        <td>
                            <img src='" . (!empty($profile_pic) ? "uploads/{$profile_pic}" : "uploads/default.png") . "' width='20' height='20' style='border-radius:50%; object-fit:cover;'>
                            {$user_name} - @{$user_email}
                        </td>
                        <td>
                            <a href='user_profile.php?email=" . urlencode($user_email) . "' class='btn btn-success'>Login User</a>
                            <form action='verify_user.php' method='POST' style='display:inline;'>
                                <input type='hidden' name='user_id' value='{$user_id}'>
                                <button type='submit' class='$verify_button_class'>$verify_button_text</button>
                            </form>
                            <form action='toggle_block.php' method='POST' style='display:inline;'>
                                <input type='hidden' name='user_id' value='{$user_id}'>
                                <button type='submit' class='$block_button_class'>$block_button_text</button>
                            </form>
                        </td>
                    </tr>";

                    $count++;
                }
            } else {
                echo "<tr><td colspan='3'>No users found</td></tr>";
            }

            ?>
            </tbody>
        </table>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>