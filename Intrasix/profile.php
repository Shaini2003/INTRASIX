<?php
session_start();
include 'includes/dbh.php';
include 'includes/functions.inc.php';

if (!isset($_SESSION['name'])) {
    header("Location: login.php");
    exit;
}

$view_username = $_GET['username'] ?? $_SESSION['name'];
$current_user = getUserrByUsername($_SESSION['name']);
$profile = getUserrByUsername($view_username);

if (!$profile) {
    echo "User not found";
    exit;
}

$profile_posts = getPosttById($profile['id']);
$is_own_profile = $current_user['id'] === $profile['id'];
$followers_count = getFollowersCount($profile['id']);
$following_count = getFollowingCount($profile['id']);
$is_following = !$is_own_profile && isFollowing($current_user['id'], $profile['id']);

// Handle follow/unfollow and block actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['toggle_follow']) && !$is_own_profile) {
        toggleFollow($current_user['id'], $profile['id']);
        header("Location: profile.php?username=" . $view_username);
        exit;
    }
    if (isset($_POST['block']) && !$is_own_profile) {
        blockUser($current_user['id'], $profile['id']);
        header("Location: profile.php?username=" . $view_username);
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
    <title><?php echo htmlspecialchars($profile['username']); ?>'s Profile</title>
    <style>
        /* Same styles as your original code */
        :root {
            --primary-color: #007bff;
            --secondary-color: #6c757d;
            --background-color: #f8f9fa;
        }

        body {
            background-color: var(--background-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
        }

        .profile-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .profile-img {
            border: 4px solid white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white border">
        <div class="container col-9 d-flex justify-content-between">
            <div class="d-flex justify-content-between col-8 align-items-center">
                <a class="navbar-brand" href="index.php">
                    <img src="images/intrasix.png" alt="Logo" width="70px" height="70px">
                </a>
                <form class="d-flex w-50" action="profile.php" method="GET">
                    <input class="form-control me-2 rounded-pill" type="search" name="username" 
                           placeholder="Search for someone..." aria-label="Search">
                </form>
            </div>
            <!-- Same navbar items as your original code -->
            <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link text-dark" href="index.php"><i class="bi bi-house-door-fill"></i></a></li>
                <li class="nav-item"><a class="nav-link text-dark" href="post.php"><i class="bi bi-plus-square-fill"></i></a></li>
                <li class="nav-item"><a class="nav-link text-dark" href="#"><i class="bi bi-bell-fill"></i></a></li>
                <li class="nav-item"><a class="nav-link text-dark" href="#"><i class="bi bi-chat-right-dots-fill"></i></a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <img src="<?php echo htmlspecialchars($current_user['profile_pic'] ?? 'default.jpg'); ?>"
                             alt="Profile" height="30" class="rounded-circle border">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="profile.php">My Profile</a></li>
                        <li><a class="dropdown-item" href="#">Account Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container col-9 rounded-0 my-4">
        <div class="col-12 profile-container p-4 d-flex gap-5">
            <div class="col-4 d-flex justify-content-end align-items-start">
                <img src="<?php echo htmlspecialchars($profile['profile_pic'] ?? 'default.jpg'); ?>"
                     class="profile-img rounded-circle my-3" style="height:170px;" alt="Profile">
            </div>
            <div class="col-8">
                <div class="d-flex flex-column">
                    <div class="d-flex gap-5 align-items-center">
                        <span style="font-size: xx-large;"><?php echo htmlspecialchars($profile['name']); ?></span>
                        <?php if (!$is_own_profile): ?>
                            <div class="dropdown">
                                <span class="text-dark" style="font-size:xx-large" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots"></i>
                                </span>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#"><i class="bi bi-chat-fill"></i> Message</a></li>
                                    <li>
                                        <form method="POST">
                                            <button type="submit" name="block" class="dropdown-item">
                                                <i class="bi bi-x-circle-fill"></i> Block
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                    <span style="font-size: larger;" class="text-secondary mb-3">@<?php echo htmlspecialchars($profile['email']); ?></span>
                    <div class="d-flex gap-2 align-items-center my-3">
                        <a class="btn btn-sm btn-primary"><i class="bi bi-file-post-fill"></i> <?php echo count($profile_posts); ?> Posts</a>
                        <a class="btn btn-sm btn-primary"><i class="bi bi-people-fill"></i> <?php echo $followers_count; ?> Followers</a>
                        <a class="btn btn-sm btn-primary"><i class="bi bi-person-fill"></i> <?php echo $following_count; ?> Following</a>
                    </div>
                    <?php if (!$is_own_profile): ?>
                        <form method="POST" class="d-flex gap-2 align-items-center my-1">
                            <button type="submit" name="toggle_follow" 
                                    class="btn btn-sm <?php echo $is_following ? 'btn-danger' : 'btn-primary'; ?>">
                                <?php echo $is_following ? 'Unfollow' : 'Follow'; ?>
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <h3 class="border-bottom py-2 mt-4">Posts</h3>
        <div class="gallery d-flex flex-wrap justify-content-center gap-3 mb-4">
            <?php foreach ($profile_posts as $post): ?>
                <img src="images/posts/<?php echo htmlspecialchars($post['post_img']); ?>" 
                     width="300px" height="300px" class="rounded" alt="Post">
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>