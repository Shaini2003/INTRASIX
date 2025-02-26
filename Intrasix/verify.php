<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INTRASIX OTP Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        body {
            background: linear-gradient(135deg, #667eea, #764ba2);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .verify-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 2.5rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            max-width: 450px;
            width: 100%;
        }

        .form-control {
            border-radius: 10px;
            padding: 0.75rem 1rem;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
            text-align: center;
            letter-spacing: 2px;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.25);
        }

        .btn-verify {
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            background: linear-gradient(to right, #2d2d2d, #1a1a1a);
            border: none;
            color: white;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-verify:hover {
            transform: translateY(-2px);
            background: linear-gradient(to right, #404040, #262626);
            color: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-verify::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.4s ease, height 0.4s ease;
        }

        .btn-verify:hover::after {
            width: 200%;
            height: 200%;
        }

        h1 {
            color: #333;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        .alert {
            border-radius: 10px;
            border-left: 5px solid #0d6efd;
        }

        .input-group-text {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-right: none;
            border-radius: 10px 0 0 10px;
        }
    </style>
</head>
<body>
    <div class="verify-container">
        <h1 class="text-center">OTP Verification</h1>
        
        <?php
        if (isset($_GET['msg'])) {
            echo "<div class='alert alert-primary'>" . htmlspecialchars($_GET['msg']) . "</div>";
        }
        ?>

        <form action="log.php" method="post">
            <div class="mb-4">
                <label for="otp" class="form-label fw-semibold">Enter Your OTP</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lock" viewBox="0 0 16 16">
                            <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2zM5 8h6a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1z"/>
                        </svg>
                    </span>
                    <input type="number" 
                           class="form-control" 
                           name="user_otp" 
                           id="otp" 
                           required 
                           placeholder="•••••" 
                           maxlength="5"
                           oninput="this.value = this.value.slice(0, 5)">
                </div>
                <small class="text-muted">Enter the 5-digit code sent to your email</small>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-verify">Verify OTP</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>