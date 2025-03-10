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
$total_users = $row['total_users'] ?? 0;

// Get total posts count
$sql = "SELECT COUNT(id) AS total_posts FROM posts";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$total_posts = $row['total_posts'] ?? 0;

// Get total comments count
$sql = "SELECT COUNT(id) AS total_comments FROM comments";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$total_comments = $row['total_comments'] ?? 0;

// Get total likes count
$sql = "SELECT COUNT(id) AS total_likes FROM likes";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$total_likes = $row['total_likes'] ?? 0;

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-pic {
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
            vertical-align: middle;
            border: 2px solid #ddd;
        }
        .btn-peach {
            background-color: #ff9999;
            color: white;
            border: none;
        }
        .btn-peach:hover {
            background-color: #ff6666;
        }
        .table-responsive {
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class="d-flex" id="wrapper">
        <div class="bg-dark border-end text-white" id="sidebar-wrapper">
            <div class="sidebar-heading text-center py-3">IntraSix</div>
            <div class="list-group list-group-flush">
                <a href="main.php" class="list-group-item list-group-item-action bg-dark text-white">Dashboard</a>
                <a href="edit_profile.php" class="list-group-item list-group-item-action bg-dark text-white">Edit Profile</a>
                <a href="view_profile.php" class="list-group-item list-group-item-action bg-dark text-white">View Profile</a>
                <a href="view_reviews.php" class="list-group-item list-group-item-action bg-dark text-white">View Reviews</a>
            </div>
        </div>
        <div id="page-content-wrapper" class="w-100 p-3">
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <div class="container-fluid">
                    <a href="logout.php" class="btn btn-danger">Logout</a>
                </div>
            </nav>
            <div class="container-fluid mt-4">
                <h4>Admin Dashboard</h4>
                <div class="row g-4 mb-4">
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card bg-info text-white p-3 text-center">
                            <h4><?php echo $total_users; ?></h4>
                            <p>Total Users</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card bg-success text-white p-3 text-center">
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

    <div class="container mt-4">
        <h2>User Lists</h2>
        <div class="table-responsive">
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
                include 'config.php';
                $sql = "SELECT id, name, email, profile_pic FROM users";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $user_id = htmlspecialchars($row['id']);
                        $user_name = htmlspecialchars($row['name']);
                        $user_email = htmlspecialchars($row['email']);
                        $profile_pic = htmlspecialchars($row['profile_pic'] ?? '');

                        // Determine profile picture path
                        $image_path = !empty($profile_pic) ? "uploads/" . $profile_pic : "uploads/default.png";
                        $full_path = __DIR__ . '/uploads/' . ($profile_pic ?: 'default.png');
                        if (!file_exists($full_path)) {
                            $image_path = "uploads/default.png";
                        }

                        // Check block status using database
                        $is_blocked = false;
                        $query = "SELECT COUNT(*) as count FROM blocked_users WHERE blocker_id = ? AND blocked_id = ?";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("ii", $_SESSION['admin_id'], $user_id);
                        $stmt->execute();
                        $result_block = $stmt->get_result()->fetch_assoc();
                        $is_blocked = $result_block['count'] > 0;
                        $stmt->close();

                        $block_button_text = $is_blocked ? "Unblock" : "Block";
                        $block_button_class = $is_blocked ? "btn btn-primary" : "btn btn-danger";

                        // Check verification status (simulated with session for demo)
                        $verify_button_text = isset($_SESSION['verified_users'][$user_id]) ? "Verified" : "Verify";
                        $verify_button_class = isset($_SESSION['verified_users'][$user_id]) ? "btn btn-peach" : "btn btn-warning";

                        echo "<tr>
                                <td>#{$count}</td>
                                <td>
                                    <img src='{$image_path}' alt='Profile Picture' class='profile-pic' width='20' height='20'>
                                    {$user_name} - @{$user_email}
                                </td>
                                <td>
                                    <a href='user_profile.php?email=" . urlencode($user_email) . "' class='btn btn-success'>Login User</a>
                                    <form action='verify_user.php' method='POST' style='display:inline-block;'>
                                        <input type='hidden' name='user_id' value='{$user_id}'>
                                       
                                    </form>
                                    <form action='toggle_block.php' method='POST' style='display:inline-block;'>
                                        <input type='hidden' name='user_id' value='{$user_id}'>
                                        <button type='submit' class='{$block_button_class} btn-sm'>{$block_button_text}</button>
                                    </form>
                                </td>
                            </tr>";

                        $count++;
                    }
                } else {
                    echo "<tr><td colspan='3' class='text-center'>No users found</td></tr>";
                }

                $conn->close();
                ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>