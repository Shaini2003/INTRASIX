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
        echo "<div class='alert alert-danger text-center mt-5'>User not found.</div>";
        exit;
    }
} else {
    echo "<div class='alert alert-warning text-center mt-5'>Invalid request.</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($user['name']); ?>'s Profile - Intrasix</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            padding: 20px;
        }
        .profile-card {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            transition: transform 0.3s ease;
        }
        .profile-card:hover {
            transform: translateY(-5px);
        }
        .profile-pic {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #007bff;
            margin-bottom: 20px;
        }
        h2 {
            color: #333;
            font-weight: 600;
            margin-bottom: 20px;
            text-align: center;
        }
        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .info-item strong {
            color: #555;
            font-weight: 500;
        }
        .info-item span {
            color: #777;
        }
        .btn-back {
            display: block;
            width: 200px;
            margin: 20px auto 0;
            background-color:rgb(171, 39, 176);
            border: none;
            padding: 10px;
            border-radius: 25px;
            color: white;
            text-align: center;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .btn-back:hover {
            background-color:rgb(128, 20, 174);
            transform: scale(1.05);
        }
        @media (max-width: 576px) {
            .profile-card {
                margin: 20px auto;
                padding: 20px;
            }
            .profile-pic {
                width: 100px;
                height: 100px;
            }
            .btn-back {
                width: 150px;
            }
        }
    </style>
</head>
<body>
    <div class="profile-card">
        <h2><?php echo htmlspecialchars($user['name']); ?>'s Profile</h2>
        <div class="text-center">
            <img src="<?php echo !empty($user['profile_pic']) ? "uploads/" . htmlspecialchars($user['profile_pic']) : "uploads/default.png"; ?>" 
                 alt="Profile Picture" class="profile-pic">
        </div>
        <div class="info-item">
            <strong>Email:</strong> <span><?php echo htmlspecialchars($user['email']); ?></span>
        </div>
        <div class="info-item">
            <strong>Gender:</strong> <span><?php echo htmlspecialchars($user['gender']); ?></span>
        </div>
        <div class="info-item">
            <strong>Town:</strong> <span><?php echo htmlspecialchars($user['town']); ?></span>
        </div>
        <div class="info-item">
            <strong>Date of Birth:</strong> <span><?php echo htmlspecialchars($user['dob']); ?></span>
        </div>
        <a href="main.php" class="btn-back">Back to Dashboard</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>