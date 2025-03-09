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
    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $view_id);
    $stmt->execute();
    $profile = $stmt->get_result()->fetch_assoc();
    $stmt->close();
} elseif ($view_username) {
    $profile = getUserByUsername($view_username);
} else {
    $profile = $current_user;
}

if (!$profile) {
    echo "User not found";
    exit;
}

// Check if the current user is blocked by the admin
$is_blocked_by_admin = false;
if (isset($_SESSION['admin_id']) && $_SESSION['admin_id'] != $current_user['id']) {
    $query = "SELECT COUNT(*) as count FROM blocked_users WHERE blocker_id = ? AND blocked_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $_SESSION['admin_id'], $profile['id']);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $is_blocked_by_admin = $result['count'] > 0;
    $stmt->close();
}

if ($is_blocked_by_admin && $current_user['id'] === $profile['id']) {
    echo "<div class='profile-container' style='text-align: center; padding: 50px;'>
            <h2>Access Denied</h2>
            <p>Admin has blocked your profile. You cannot view or edit this profile.</p>
          </div>";
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

// Handle block/unblock (only for admin)
$is_blocking = !$is_own_profile && isset($_SESSION['admin_id']) && isBlocking($_SESSION['admin_id'], $profile['id']);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_block']) && !$is_own_profile && isset($_SESSION['admin_id'])) {
    $success = toggleBlock($_SESSION['admin_id'], $profile['id']);
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
    <title>Intrasix</title>
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
            background-color: #0984e3;
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
        .stas-btn {
            background-color: #9b59b6;
            color: white;
            border-radius: 2px;
            border: none;
        }
        .block-btn {
            background-color: #dc3545;
            border: none;
            padding: 5px 15px;
            border-radius: 4px;
            font-weight: 600;
        }
        .follow-btn.unfollow.block {
            background-color: #fff;
            color: #dc3545;
            border: 1px solid #dc3545;
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
                        <img src="<?php echo htmlspecialchars($current_user['profile_pic'] ?? 'uploads/default.png'); ?>" alt="Profile" height="30" class="rounded-circle">
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
        <?php if ($is_blocked_by_admin && $current_user['id'] === $profile['id']): ?>
            <div style="text-align: center; padding: 50px;">
                <h2>Access Denied</h2>
                <p>Admin has blocked your profile. You cannot view or edit this profile.</p>
            </div>
        <?php else: ?>
            <div class="profile-header">
                <img src="<?php echo htmlspecialchars($profile['profile_pic'] ?? 'uploads/default.png'); ?>" alt="Profile" class="profile-pic">
                <div class="profile-info">
                    <div class="username-row">
                        <h1 class="username"><?php echo htmlspecialchars($profile['name']); ?></h1>
                        <?php if (!$is_own_profile && isset($_SESSION['admin_id'])): ?>
                            <form method="POST">
                                <button type="submit" name="toggle_follow" class="follow-btn <?php echo $is_following ? 'unfollow' : ''; ?>">
                                    <?php echo $is_following ? 'Following' : 'Follow'; ?>
                                </button>
                            </form>
                            <form method="POST" class="d-inline">
                                <button type="submit" name="toggle_block" class="block-btn <?php echo $is_blocking ? 'unfollow block' : ''; ?>">
                                    <?php echo $is_blocking ? 'Unblock' : 'Block'; ?>
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                    <p>@<?php echo htmlspecialchars($profile['email']); ?></p>
                    <div class="stats">
                        <button class="stas-btn"><span><b><?php echo count($profile_posts); ?></b> posts</span></button>
                        <button class="stas-btn"><span><b><?php echo $followers_count; ?></b> followers</span></button>
                        <button class="stas-btn"><span><b><?php echo $following_count; ?></b> following</span></button>
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
                    <img
                        src="images/posts/<?php echo htmlspecialchars($post['post_img']); ?>"
                        alt="Post"
                        class="post-img post-clickable"
                        data-img="images/posts/<?php echo htmlspecialchars($post['post_img']); ?>"
                        data-username="<?php echo htmlspecialchars($profile['name']); ?>"
                        style="cursor: pointer;">
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Single Modal -->
        <div class="modal fade" id="postModal" tabindex="-1" aria-labelledby="postModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="postModalLabel"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img id="modalImage" src="" alt="Post" class="img-fluid" style="max-height: 70vh; width: auto;">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const images = document.querySelectorAll('.post-clickable');
                const modal = document.getElementById('postModal');
                const modalImage = document.getElementById('modalImage');
                const modalTitle = document.getElementById('postModalLabel');

                images.forEach(image => {
                    image.addEventListener('click', function() {
                        const imgSrc = this.getAttribute('data-img');
                        const username = this.getAttribute('data-username');

                        modalImage.src = imgSrc;
                        modalTitle.textContent = `${username}'s Post`;

                        const bootstrapModal = new bootstrap.Modal(modal);
                        bootstrapModal.show();
                    });
                });
            });
        </script>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>