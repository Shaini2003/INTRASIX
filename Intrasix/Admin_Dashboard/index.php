<!-- index.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="styles.css">
    <!-- Add Font Awesome for icons (optional) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* styles.css */
body {
    margin: 0;
    padding: 0;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: linear-gradient(135deg, #6e8efb, #a777e3); /* Gradient background */
    font-family: 'Arial', sans-serif;
}

.login-container {
    background: #fff;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    width: 100%;
    max-width: 400px;
    transition: transform 0.3s ease;
}

.login-container:hover {
    transform: translateY(-5px); /* Subtle lift effect on hover */
}

.login-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.login-header h2 {
    margin: 0;
    color: #333;
    font-size: 1.8rem;
    font-weight: 600;
}

.login-header i {
    font-size: 1.5rem;
    color: #6e8efb;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: #555;
    font-weight: 500;
}

.input-wrapper {
    position: relative;
}

.input-wrapper i {
    position: absolute;
    top: 50%;
    left: 10px;
    transform: translateY(-50%);
    color: #999;
}

.form-group input {
    width: 100%;
    padding: 0.75rem 1rem 0.75rem 2.5rem; /* Space for the icon */
    border: 2px solid #ddd;
    border-radius: 25px;
    font-size: 1rem;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
    box-sizing: border-box;
}

.form-group input:focus {
    outline: none;
    border-color: #6e8efb;
    box-shadow: 0 0 5px rgba(110, 142, 251, 0.3);
}

.login-btn {
    width: 100%;
    padding: 0.75rem;
    background: linear-gradient(to right, #6e8efb, #a777e3);
    border: none;
    border-radius: 25px;
    color: white;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s ease, transform 0.2s ease;
}

.login-btn:hover {
    background: linear-gradient(to right, #5f7de3, #9468d2);
    transform: scale(1.02);
}

.error {
    color: #e74c3c;
    text-align: center;
    margin-bottom: 1rem;
    font-size: 0.9rem;
}

/* Responsive Design */
@media (max-width: 480px) {
    .login-container {
        margin: 1rem;
        padding: 1.5rem;
    }

    .login-header h2 {
        font-size: 1.5rem;
    }

    .form-group input {
        padding: 0.6rem 1rem 0.6rem 2.5rem;
    }

    .login-btn {
        padding: 0.6rem;
    }
}
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h2>Admin Login</h2>
            <i class="fas fa-user-lock"></i>
        </div>
        <?php
        session_start();
        if (isset($_SESSION['error'])) {
            echo "<p class='error'>" . $_SESSION['error'] . "</p>";
            unset($_SESSION['error']);
        }
        ?>
        <form action="login_process.php" method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" id="email" name="email" required placeholder="Enter your email">
                </div>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" id="password" name="password" required placeholder="Enter your password">
                </div>
            </div>
            <button type="submit" class="login-btn">Login</button>
        </form>
    </div>
</body>
</html>