<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intrasix</title>
    <link rel="stylesheet" href="video.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <h1>Videos</h1>
        
        <!-- Video Upload Form -->
        <form action="upload.php" method="POST" enctype="multipart/form-data" class="upload-form">
            <input type="text" name="title" placeholder="Video Title" required>
            <input type="file" name="video" accept="video/mp4,video/webm,video/ogg" required>
            <button type="submit" style="background-color: #9b59b6;">Upload Video</button>
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
                        echo '<div class="slide"><video controls width="100%"><source src="uploads/' . htmlspecialchars($row['filename']) . '" type="video/mp4">Your browser does not support the video tag.</video><p>' . htmlspecialchars($row['title']) . '</p></div>';
                    }
                } else {
                    echo '<p>No videos uploaded yet.</p>';
                }
                $conn->close();
                ?>
            </div>
            <div class="slider-controls">
                <button id="prev" style="background-color: #9b59b6;"><i class="fas fa-angle-left"></i></button>
                <button id="next" style="background-color: #9b59b6;"><i class="fas fa-angle-right"></i></button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
    const slider = document.querySelector('.slider');
    const prevBtn = document.getElementById('prev');
    const nextBtn = document.getElementById('next');
    let slideIndex = 0;
    const slideWidth = 100; // Percentage width of each slide

    function updateSlider() {
        const totalSlides = slider.children.length;
        const maxIndex = totalSlides - 1;
        slider.style.transform = `translateX(-${slideIndex * slideWidth}%)`;

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

    // Optional: Auto-slide every 5 seconds
    let autoSlide = setInterval(() => {
        const totalSlides = slider.children.length;
        if (slideIndex < totalSlides - 1) {
            slideIndex++;
        } else {
            slideIndex = 0;
        }
        updateSlider();
    }, 5000);

    // Pause auto-slide on hover
    slider.addEventListener('mouseover', () => clearInterval(autoSlide));
    slider.addEventListener('mouseout', () => {
        autoSlide = setInterval(() => {
            const totalSlides = slider.children.length;
            if (slideIndex < totalSlides - 1) {
                slideIndex++;
            } else {
                slideIndex = 0;
            }
            updateSlider();
        }, 5000);
    });
});
    </script>
</body>
</html>