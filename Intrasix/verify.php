<?php
session_start();
?>

<!doctype html>
<html lang="en">
<head>
    <title>INTRASIX OTP Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>INTRASIX OTP Login</h1>
        
        <?php
        if (isset($_GET['msg'])) {
            echo "<div class='alert alert-primary'>" . htmlspecialchars($_GET['msg']) . "</div>";
        }
        ?>

        <form action="log.php" method="post">
            <div class="mb-3">
                <label for="otp" class="form-label">Enter OTP:</label>
                <input type="number" class="form-control" name="user_otp" id="otp" required placeholder="5 Digits OTP">
            </div>
            <button type="submit" class="btn btn-success">Verify OTP</button>
        </form>
    </div>
</body>
</html>
