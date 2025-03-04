<?php
session_start();
include 'includes/dbh.php'; // Database connection

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$id = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
    
    // Handling profile picture upload
    $profile_picture = null;
    if (!empty($_FILES['profile_picture']['name']) && is_uploaded_file($_FILES["profile_picture"]["tmp_name"])) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = mime_content_type($_FILES["profile_picture"]["tmp_name"]);

        if (in_array($file_type, $allowed_types)) {
            $target_dir = "uploads/";

            // Create directory if not exists
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $file_ext = pathinfo($_FILES["profile_picture"]["name"], PATHINFO_EXTENSION);
            $unique_filename = "profile_" . $id . "_" . time() . "." . $file_ext;
            $target_file = $target_dir . $unique_filename;

            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                $profile_picture = $target_file;
            } else {
                echo "Error uploading file.";
                exit();
            }
        } else {
            echo "Invalid file type. Only JPG, PNG, and GIF are allowed.";
            exit();
        }
    }

    // Prepare SQL query
    $sql = "UPDATE users SET name = ?, email = ?, dob = ?, gender = ?";
    $params = [$name, $email, $dob, $gender];

    if ($password) {
        $sql .= ", password = ?";
        $params[] = $password;
    }
    if ($profile_picture) {
        $sql .= ", profile_pic = ?";
        $params[] = $profile_picture;
    }

    $sql .= " WHERE id = ?";
    $params[] = $id;

    // Execute prepared statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(str_repeat("s", count($params) - 1) . "i", ...$params);
    $stmt->execute();

    header("Location: view_profile.php?success=1");
    exit();
}

// Fetch user data
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #f5f6fa;
            min-height: 100vh;
        }
        .profile-container {
            max-width: 600px;
            margin: 2rem auto;
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .profile-pic img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            display: block;
            margin: 0 auto 1rem;
        }
        .btn-primary {
            background: linear-gradient(to right, #667eea, #764ba2);
            border: none;
            border-radius: 8px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        
        }
        .navbar {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }


    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white border">
        <div class="container col-9 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <a class="navbar-brand" href="#">
            <img src="images/intrasix-logo.png" alt="Logo" height="70px"width="70px">
                <img src="images/intrasix.png" alt="Logo" height="70px"width="70px">
            </a>
           
        </div>
            <ul class="navbar-nav d-flex align-items-center gap-3">
                <li class="nav-item">
                    <a class="nav-link text-dark" href="index.php"><i class="bi bi-house-door-fill fs-5"></i></a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link text-dark" href="notifications.php"><i class="bi bi-bell-fill fs-5"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="message.php"><i class="bi bi-chat-right-dots-fill fs-5"></i></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle p-0" href="#" id="navbarDropdown" role="button" 
                       data-bs-toggle="dropdown" aria-expanded="false">
                       <img src="<?php echo htmlspecialchars($user['profile_pic'] ?? 'uploads/default.jpg'); ?>" alt="Profile" height="35" class="rounded-circle border">
                    
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="profile-container">
        <h1 class="text-center">Edit Profile</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="profile-pic text-center">
            <img src="<?php echo htmlspecialchars($user['profile_pic'] ?? 'uploads/default.jpg'); ?>" alt="Profile Picture">

            </div>
            <div class="mb-3">
                <label class="form-label">Change Profile Picture</label>
                <input class="form-control" type="file" name="profile_picture">
            </div>
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Date of Birth</label>
                <input type="date" class="form-control" name="dob" value="<?php echo htmlspecialchars($user['dob']); ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Gender</label>
                <select class="form-control" name="gender">
                    <option value="male" <?php if ($user['gender'] == 'male') echo 'selected'; ?>>Male</option>
                    <option value="female" <?php if ($user['gender'] == 'female') echo 'selected'; ?>>Female</option>
                    <option value="other" <?php if ($user['gender'] == 'other') echo 'selected'; ?>>Other</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">New Password (leave blank to keep current)</label>
                <input type="password" class="form-control" name="password">
            </div>
            <div class="text-center">
                <button class="btn btn-primary" type="submit">Update Profile</button>
            </div>
        </form>
    </div>
</body>
</html>
