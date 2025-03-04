<?php  
session_start();

// Default color if not set
if (!isset($_SESSION['theme_color'])) {
    $_SESSION['theme_color'] = "#3498db"; // Default blue
}

// Handle color selection
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['theme_color'])) {
    $_SESSION['theme_color'] = $_POST['theme_color'];
    header("Location: themes.php"); // Refresh to apply changes
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Color Palette - IntraSix</title>
    <link rel="stylesheet" href="colorstyle.php"> <!-- Dynamic CSS -->
    <style>
        .palette-container { text-align: center; margin-top: 20px; }
        .color-box {
            display: inline-block;
            width: 50px; height: 50px;
            margin: 5px;
            cursor: pointer;
            border-radius: 50%;
            border: 3px solid white;
            transition: transform 0.2s ease-in-out;
        }
        .color-box:hover {
            transform: scale(1.1);
        }
        button {
            margin-top: 15px;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            background: white;
            font-weight: bold;
            border-radius: 5px;
            transition: background 0.3s ease-in-out;
        }
    </style>
</head>
<body>

<h2 style="text-align:center;">Choose a Theme Color</h2>

<form method="post">
    <div class="palette-container">
        <input type="hidden" name="theme_color" id="selectedColor" value="<?= $_SESSION['theme_color'] ?>">
        
        <div class="color-box" style="background:#3498db" onclick="selectColor('#3498db')"></div>
        <div class="color-box" style="background:#e74c3c" onclick="selectColor('#e74c3c')"></div>
        <div class="color-box" style="background:#2ecc71" onclick="selectColor('#2ecc71')"></div>
        <div class="color-box" style="background:#f1c40f" onclick="selectColor('#f1c40f')"></div>
        <div class="color-box" style="background:#9b59b6" onclick="selectColor('#9b59b6')"></div>
		<div class="color-box" style="background:#070708" onclick="selectColor('#070708')"></div>
        <div class="color-box" style="background:white" onclick="selectColor('white')"></div>


        <br>
        <button type="submit">Apply Theme</button>
    </div>
</form>

<script>
    function selectColor(color) {
        document.getElementById('selectedColor').value = color;
    }
</script>

</body>
</html>