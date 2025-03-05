<?php
session_start();
include("includes/dbh.php"); // Database connection

// Fetch posts from the database only for the logged-in user
if (isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];
    $query = "SELECT * FROM posts WHERE user_id = $user_id ORDER BY created_at DESC"; 
    $result = mysqli_query($conn, $query);
} else {
    die("Unauthorized access");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Posts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Custom styling */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            padding-top: 50px;
        }

        .modal-content {
            background-color: #ffffff;
        }

        .post {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 20px;
            display: flex;
            align-items: center;
            transition: box-shadow 0.3s ease-in-out;
        }

        .post:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .post img {
            max-width: 200px; /* Medium size for the image */
            border-radius: 8px;
            margin-right: 20px;
        }

        .delete-btn {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 10px 15px;
            font-size: 0.9rem;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .delete-btn:hover {
            background-color: #c0392b;
        }

        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }

        .preview-container {
            text-align: center;
            margin-bottom: 15px;
        }

        .preview-img {
            max-width: 100%;
            max-height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }

        .form-control {
            margin-bottom: 10px;
        }

        .modal-header, .modal-body {
            padding: 20px;
        }

        .modal-title {
            font-size: 1.5rem;
            color: #333;
        }

        .container {
            width: 80%;
            margin: 0 auto;
        }

        /* Adjust for alignment */
        .post-content {
            flex-grow: 1;
        }

        .post-actions {
            text-align: right;
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Add New Post Modal -->
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Post</h5>
                    <button type="button"  id="closeModalBtn" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4 preview-container">
                        <img src="" class="preview-img" id="imagePreview" alt="Post preview">
                    </div>
                    <form method="post" action="add_posts.php" enctype="multipart/form-data">
                        <div class="mb-4">
                            <input class="form-control" name="post_img" type="file" id="formFile" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label for="postCaption" class="form-label">Say Something</label>
                            <textarea name="post_text" class="form-control" id="postCaption" rows="3" placeholder="What's on your mind?"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Post</button>
                    </form>
                </div>
            </div>
        </div>

        <h2>Your Posts</h2>

        <!-- Display the user's posts -->
        <?php while ($post = mysqli_fetch_assoc($result)): ?>
            <div class="post">
                <!-- Left side: Post Image -->
                <img src="images/posts/<?= htmlspecialchars($post['post_img']); ?>" alt="Post Image">

                <!-- Right side: Post Content and Delete button -->
                <div class="post-content">
                    <p><?= htmlspecialchars($post['post_text']); ?></p>
                </div>
                <div class="post-actions">
                    <!-- Delete button: Show only for the logged-in user's posts -->
                    <form action="delete_post.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?')">
                        <input type="hidden" name="post_id" value="<?= $post['id']; ?>">
                        <button type="submit" class="delete-btn">Delete Post</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // JavaScript to handle image preview
        document.getElementById('formFile').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const imagePreview = document.getElementById('imagePreview');

            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.classList.add('show'); // Show the image
                };

                reader.readAsDataURL(file);
            } else {
                imagePreview.src = '';
                imagePreview.classList.remove('show'); // Hide the image if no file is selected
            }
        });

        // Close button redirect to home page
        document.getElementById('closeModalBtn').addEventListener('click', function() {
            window.location.href = 'index.php'; // Redirect to home page (adjust URL as needed)
        });
    </script>
</body>
</html>