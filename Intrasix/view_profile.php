<?php
session_start();
include 'includes/dbh.php'; // Database connection

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$id = $_SESSION['id'];

// Fetch user details
$stmt = $conn->prepare("SELECT id, name, email, dob, gender, profile_pic FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "User not found!";
    exit();
}

// Fetch counts
// Followers count
$followers_stmt = $conn->prepare("SELECT COUNT(*) as follow_list FROM follow_list WHERE following_id = ?");
$followers_stmt->bind_param("i", $id);
$followers_stmt->execute();
$followers_result = $followers_stmt->get_result();
$followers_count = $followers_result->fetch_assoc()['follow_list'];

// Following count
$following_stmt = $conn->prepare("SELECT COUNT(*) as following FROM follow_list WHERE follower_id = ?");
$following_stmt->bind_param("i", $id);
$following_stmt->execute();
$following_result = $following_stmt->get_result();
$following_count = $following_result->fetch_assoc()['following'];

// Posts count
$posts_stmt = $conn->prepare("SELECT COUNT(*) as posts FROM posts WHERE user_id = ?");
$posts_stmt->bind_param("i", $id);
$posts_stmt->execute();
$posts_result = $posts_stmt->get_result();
$posts_count = $posts_result->fetch_assoc()['posts'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #f5f6fa, #e2e7ff);
            padding: 2rem;
        }

        .profile-container {
            max-width: 700px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .profile-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .profile-header h1 {
            color: #333;
            font-weight: 600;
            letter-spacing: 1px;
            margin-bottom: 1rem;
        }

        .profile-pic img {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .profile-pic img:hover {
            transform: scale(1.05);
        }

        .stats {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin: 1.5rem 0;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .stat-item {
            text-align: center;
        }

        .stat-item span {
            display: block;
            font-size: 1.5rem;
            font-weight: 600;
            color: #667eea;
        }

        .stat-item p {
            color: #555;
            font-size: 0.9rem;
            margin: 0;
        }

        .profile-info {
            text-align: left;
            padding: 1rem;
        }

        .profile-info p {
            margin: 0.75rem 0;
            color: #444;
            font-size: 1rem;
        }

        .profile-info strong {
            color: #333;
            font-weight: 500;
        }

        .buttons {
            margin-top: 2rem;
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .btn {
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            background: linear-gradient(45deg, #764ba2, #667eea);
        }

        .btn-secondary {
            background: #e9ecef;
            color: #333;
            border: none;
        }

        .btn-secondary:hover {
            background: #dee2e6;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 576px) {
            .profile-container {
                padding: 1.5rem;
            }

            .profile-pic img {
                width: 150px;
                height: 150px;
            }

            .stats {
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            <h1>My Profile</h1>
            <div class="profile-pic">
                <img src="<?php echo htmlspecialchars($user['profile_pic'] ?? 'default.jpg'); ?>" alt="Profile Picture">
            </div>
        </div>

        <div class="stats">
            <div class="stat-item">
                <span><?php echo $posts_count; ?></span>
                <p>Posts</p>
            </div>
            <div class="stat-item">
                <span><?php echo $followers_count; ?></span>
                <p>Followers</p>
            </div>
            <div class="stat-item">
                <span><?php echo $following_count; ?></span>
                <p>Following</p>
            </div>
        </div>

        <div class="profile-info">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($user['dob']); ?></p>
            <p><strong>Gender:</strong> <?php echo htmlspecialchars($user['gender']); ?></p>
        </div>

        <div class="buttons">
            <a href="edit_profile.php" class="btn btn-primary">Edit Profile</a>
            <a href="index.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
<?php
// Close statements
$followers_stmt->close();
$following_stmt->close();
$posts_stmt->close();
$stmt->close();
$conn->close();
?>