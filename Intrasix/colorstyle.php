<?php 
session_start();
header("Content-type: text/css");

$theme_color = isset($_SESSION['theme_color']) ? $_SESSION['theme_color'] : "#3498db";
?>

/* Apply the theme color globally */
body {
    background-color: <?= $theme_color ?>;
    color: white;
    font-family: Arial, sans-serif;
}

/* Header styling */
header {
    background-color: <?= $theme_color ?>;
    padding: 15px;
    text-align: center;
    font-size: 20px;
    font-weight: bold;
}

/* Buttons */
button {
    background-color: <?= $theme_color ?>;
    color: black;
    border: none;
    padding: 10px 15px;
    cursor: pointer;
    border-radius: 5px;
    transition: background 0.3s ease-in-out;
}

button:hover {
    background-color: <?php echo adjustBrightness($theme_color, -30); ?>;
}

/* Chat messages */
.chat-messages div.sent {
    background: <?= $theme_color ?>;
    color: white;
}

/* Sidebar */
.sidebar {
    background: <?= $theme_color ?>;
    padding: 15px;
    color: black;
}

/* Function to adjust brightness of color */
<?php
function adjustBrightness($hex, $steps) {
    // Convert HEX to RGB
    $hex = str_replace('#', '', $hex);
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));

    // Adjust brightness
    $r = max(0, min(255, $r + $steps));
    $g = max(0, min(255, $g + $steps));
    $b = max(0, min(255, $b + $steps));

    // Convert back to HEX
    return sprintf("#%02x%02x%02x", $r, $g, $b);
}
?>