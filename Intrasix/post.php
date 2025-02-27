<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <title>Add New Post</title>
    <style>
        .modal-content {
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.15);
            border: none;
        }

        .modal-header {
            background: linear-gradient(135deg, #6e8efb, #a777e3);
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 1.5rem;
        }

        .modal-title {
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .btn-close {
            filter: invert(1);
            opacity: 0.8;
        }

        .modal-body {
            padding: 2rem;
        }

        .preview-container {
            position: relative;
            width: 100%;
            height: 250px; /* Fixed height for the preview */
            overflow: hidden;
            border-radius: 10px;
            border: 2px solid #e9ecef;
            background: #f8f9fa; /* Light background for empty state */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .preview-img {
            max-height: 300px;
            object-fit: cover;
            transition: all 0.3s ease;
            display: none; /* Hidden by default until an image is selected */
        }

        .preview-img.show {
            display: block; /* Show when an image is loaded */
        }
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: #6e8efb;
            box-shadow: 0 0 0 0.2rem rgba(110, 142, 251, 0.25);
        }

        .form-label {
            font-weight: 500;
            color: #495057;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 80px;
            max-height: 200px;
        }

        .modal-footer {
            padding: 1rem 2rem;
            border-top: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #6e8efb, #a777e3);
            border: none;
            padding: 0.65rem 1.5rem;
            border-radius: 10px;
            transition: transform 0.2s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
        }

        .btn-secondary {
            border-radius: 10px;
            padding: 0.65rem 1.5rem;
            background: #e9ecef;
            color: #495057;
            border: none;
        }

        @media (max-width: 576px) {
            .modal-dialog {
                margin: 1rem;
            }
            
            .modal-body {
                padding: 1.5rem;
            }

            .preview-container {
                height: 200px; /* Smaller height on mobile */
            }
        }
    </style>
</head>
<body>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Post</h5>
                <button type="button"  id="closeModalBtn"class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                <button type="submit" class="btn btn-primary">Post</button>
            
                </form>
            </div>
          
        </div>
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
            addPostModal.hide(); // Hide the modal
            window.location.href = 'index.php'; // Redirect to home page (adjust URL as needed)
        });
    </script>
</body>
</html>