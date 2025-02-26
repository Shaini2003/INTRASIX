<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INTRASIX OTP Login</title>
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

        .login-container {
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
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.25);
        }

        .btn-black {
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

        .btn-black:hover {
            transform: translateY(-2px);
            background: linear-gradient(to right, #404040, #262626);
            color: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-black::after {
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

        .btn-black:hover::after {
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
    </style>
</head>
<body>
    <div class="login-container">
        <h1 class="text-center">Intrasix OTP Login</h1>
        
        <div class="alert alert-primary <?php echo isset($_REQUEST['msg']) ? '' : 'd-none'; ?>" role="alert">
            <?php
            if(isset($_REQUEST['msg']))
                echo $_REQUEST['msg'];
            ?>
        </div>

        <form action="send_otp.php" method="post">
            <div class="mb-4">
                <label for="user_email" class="form-label fw-semibold">Enter Your Email</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope" viewBox="0 0 16 16">
                            <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"/>
                        </svg>
                    </span>
                    <input type="email" 
                           class="form-control" 
                           name="user_email" 
                           id="user_email" 
                           placeholder="name@example.com" 
                           required>
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-black">Send OTP</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>