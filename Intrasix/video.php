<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intrasix - Video Reels</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f0f0f0; /* Lighter background for contrast */
            color: #fff;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            width: 100%;
            max-width: 600px; /* Limit width on larger screens */
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h1 {
            text-align: center;
            padding: 15px;
            background-color: #9b59b6;
            width: 100%;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        /* Upload Form */
        .upload-form {
            width: 100%;
            background: rgba(0, 0, 0, 0.8);
            padding: 10px;
            z-index: 10;
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .upload-form input[type="text"] {
            padding: 5px;
            width: 40%;
            border-radius: 5px;
            border: none;
        }

        .upload-form input[type="file"] {
            padding: 5px;
            color: #fff;
        }

        .upload-form button {
            padding: 5px 15px;
            background-color: #9b59b6;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        /* Slider Container */
        .slider-container {
            width: 100%;
            height: 70vh; /* Reduced height for laptops */
            max-height: 800px; /* Maximum height cap */
            overflow: hidden;
            position: relative;
            background: #000;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .slider {
            display: flex;
            flex-direction: column;
            height: 100%;
            width: 100%;
            transition: transform 0.3s ease-in-out;
        }

        .slide {
            width: 100%;
            height: 100%; /* Takes full container height */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .slide video {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Ensures video fills the space */
        }

        .slide p {
            position: absolute;
            bottom: 10px;
            left: 10px;
            background: rgba(0, 0, 0, 0.7);
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 14px;
        }

        /* Slider Controls */
        .slider-controls {
            position: absolute;
            top: 50%;
            width: 100%;
            display: flex;
            justify-content: space-between;
            transform: translateY(-50%);
            z-index: 5;
            padding: 0 10px;
        }

        .slider-controls button {
            background-color: rgba(155, 89, 182, 0.7);
            border: none;
            color: #fff;
            padding: 10px;
            cursor: pointer;
            font-size: 18px;
            border-radius: 50%;
            transition: background-color 0.3s;
        }

        .slider-controls button:hover {
            background-color: #9b59b6;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .container {
                max-width: 100%;
                padding: 10px;
            }

            .slider-container {
                height: 60vh; /* Slightly smaller on tablets */
            }

            .upload-form {
                flex-direction: column;
                align-items: center;
            }

            .upload-form input[type="text"] {
                width: 80%;
            }

            .slider-controls button {
                padding: 8px;
                font-size: 16px;
            }
        }

        @media (max-width: 480px) {
            .slider-container {
                height: 50vh; /* Even smaller on mobile */
            }

            .slide p {
                font-size: 12px;
            }

            .slider-controls button {
                padding: 6px;
                font-size: 14px;
            }
        }

        @media (min-width: 1024px) {
            .slider-container {
                height: 80vh; /* Slightly larger on desktops */
                max-height: 900px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Videos</h1>
        
        <!-- Video Upload Form -->
        <form action="upload.php" method="POST" enctype="multipart/form-data" class="upload-form">
            <input type="text" name="title" placeholder="Video Title" required>
            <input type="file" name="video" accept="video/mp4,video/webm,video/ogg" required>
            <button type="submit">Upload Video</button>
        </form>

        <!-- Video Slider -->
        <div class="slider-container">
            <div class="slider">
                <?php
                include 'includes/dbh.php';
                $sql = "SELECT * FROM videos ORDER BY upload_date DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="slide"><video controls><source src="uploads/' . htmlspecialchars($row['filename']) . '" type="video/mp4">Your browser does not support the video tag.</video><p>' . htmlspecialchars($row['title']) . '</p></div>';
                    }
                } else {
                    echo '<div class="slide"><p>No videos uploaded yet.</p></div>';
                }
                $conn->close();
                ?>
            </div>
            <div class="slider-controls">
                <button id="prev"><i class="fa-angle-up fas"></i></button>
                <button id="next"><i class="fa-angle-down fas"></i></button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const slider = document.querySelector('.slider');
            const prevBtn = document.getElementById('prev');
            const nextBtn = document.getElementById('next');
            let slideIndex = 0;
            const slideHeight = 100; // Percentage height of each slide relative to container

            function updateSlider() {
                const totalSlides = slider.children.length;
                const maxIndex = totalSlides - 1;
                slider.style.transform = `translateY(-${slideIndex * slideHeight}%)`;

                // Show/hide navigation buttons
                prevBtn.style.display = slideIndex === 0 ? 'none' : 'block';
                nextBtn.style.display = slideIndex === maxIndex ? 'none' : 'block';
            }

            nextBtn.addEventListener('click', () => {
                const totalSlides = slider.children.length;
                if (slideIndex < totalSlides - 1) {
                    slideIndex++;
                    updateSlider();
                }
            });

            prevBtn.addEventListener('click', () => {
                if (slideIndex > 0) {
                    slideIndex--;
                    updateSlider();
                }
            });

            // Touch swipe support for mobile
            let touchStartY = 0;
            let touchEndY = 0;

            slider.addEventListener('touchstart', (e) => {
                touchStartY = e.changedTouches[0].screenY;
            });

            slider.addEventListener('touchend', (e) => {
                touchEndY = e.changedTouches[0].screenY;
                handleSwipe();
            });

            function handleSwipe() {
                const totalSlides = slider.children.length;
                if (touchStartY - touchEndY > 50) { // Swipe up
                    if (slideIndex < totalSlides - 1) {
                        slideIndex++;
                        updateSlider();
                    }
                }
                if (touchEndY - touchStartY > 50) { // Swipe down
                    if (slideIndex > 0) {
                        slideIndex--;
                        updateSlider();
                    }
                }
            }

            // Auto-play videos when in view
            const videos = document.querySelectorAll('.slide video');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.play();
                    } else {
                        entry.target.pause();
                    }
                });
            }, { threshold: 0.8 });

            videos.forEach(video => observer.observe(video));

            // Initial update
            updateSlider();
        });
    </script>
</body>
</html>