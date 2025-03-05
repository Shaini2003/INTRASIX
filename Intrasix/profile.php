<?php
session_start();
include 'includes/dbh.php';
include 'functions.php';

// Get current user (assuming session is active)
$current_user = getUserByUsername($_SESSION['name']);

// Determine profile to view
$view_id = $_GET['id'] ?? null;
$view_username = $_GET['name'] ?? null;

if ($view_id) {
    // Fetch profile by ID (from post link)
    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $view_id);
    $stmt->execute();
    $profile = $stmt->get_result()->fetch_assoc();
    $stmt->close();
} elseif ($view_username) {
    // Fetch profile by username (from search)
    $profile = getUserByUsername($view_username);
} else {
    // Default to current user's profile
    $profile = $current_user;
}

if (!$profile) {
    echo "User not found";
    exit;
}

$profile_posts = getPostsByUserId($profile['id']);
$is_own_profile = $current_user['id'] === $profile['id'];
$followers_count = getFollowersCount($profile['id']);
$following_count = getFollowingCount($profile['id']);
$is_following = !$is_own_profile && isFollowing($current_user['id'], $profile['id']);

// Handle follow/unfollow
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_follow']) && !$is_own_profile) {
    $success = toggleFollow($current_user['id'], $profile['id']);
    if ($success) {
        header("Location: profile.php?id=" . $profile['id']);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <title><?php echo htmlspecialchars($profile['username']); ?> | Intrasix</title>
    <style>
        body {
            background-color: #fafafa;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }
        .profile-container {
            max-width: 935px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 44px;
        }
        .profile-pic {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin-right: 100px;
            object-fit: cover;
        }
        .profile-info {
            flex-grow: 1;
        }
        .username-row {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .username {
            font-size: 28px;
            font-weight: 300;
            margin-right: 20px;
        }
        .follow-btn {
            background-color:#9b59b6;
            color: white;
            border: none;
            padding: 5px 15px;
            border-radius: 4px;
            font-weight: 600;
        }
        .follow-btn.unfollow {
            background-color: #fff;
            color: #262626;
            border: 1px solid #dbdbdb;
        }
        .stats {
            display: flex;
            gap: 40px;
            margin-bottom: 20px;
        }
        .stats span {
            font-size: 16px;
        }
        .stats span b {
            font-weight: 600;
        }
        .bio {
            font-size: 16px;
            line-height: 24px;
        }
        .posts-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 28px;
        }
        .post-img {
            width: 100%;
            height: 293px;
            object-fit: cover;
            border-radius: 4px;
        }
        .navbar {
            border-bottom: 1px solid #dbdbdb;
            background-color: #fff;
        }
        .search-bar {
            background-color: #efefef;
            border: none;
            border-radius: 8px;
            padding: 7px 15px;
            width: 215px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">
             
                <a title="" href="#"><img src="images/intrasix-logo.png" alt="" width="70px" height="70px"></a>
				<a title="" href="#"><img src="images/Name.png" alt="" width="70px" height="70px"></a>
            </a>
            <form class="d-flex mx-auto" action="profile.php" method="GET">
                <input class="search-bar" type="search" name="username" placeholder="Search" required>
            </form>
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="index.php"><i class="bi bi-house-door-fill"></i></a></li>
                <li class="nav-item"><a class="nav-link" href="post.php"><i class="bi bi-plus-square-fill"></i></a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <img src="<?php echo htmlspecialchars($current_user['profile_pic']); ?>" alt="Profile" height="30" class="rounded-circle">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <div class="profile-container">
        <div class="profile-header">
            <img src="<?php echo htmlspecialchars($profile['profile_pic']); ?>" alt="Profile" class="profile-pic">
            <div class="profile-info">
                <div class="username-row">
                    <h1 class="username"><?php echo htmlspecialchars($profile['name']); ?></h1>
                    <?php if (!$is_own_profile): ?>
                        <form method="POST">
                            <button type="submit" name="toggle_follow" class="follow-btn <?php echo $is_following ? 'unfollow' : ''; ?>">
                                <?php echo $is_following ? 'Following' : 'Follow'; ?>
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
                <div class="stats">
                    <span><b><?php echo count($profile_posts); ?></b> posts</span>
                    <span><b><?php echo $followers_count; ?></b> followers</span>
                    <span><b><?php echo $following_count; ?></b> following</span>
                </div>
                <div class="bio">
                    <strong><?php echo htmlspecialchars($profile['full_name'] ?? ''); ?></strong><br>
                    <?php echo htmlspecialchars($profile['bio'] ?? ''); ?>
                </div>
            </div>
        </div>
        <hr>
        <div class="posts-grid">
            <?php foreach ($profile_posts as $post): ?>
                <img src="images/posts/<?php echo htmlspecialchars($post['post_img']); ?>" alt="Post" class="post-img">
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>