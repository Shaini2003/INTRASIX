<?php
require_once 'dbh.php';
require_once 'functions.inc.php';

// Function to generate alert message
function generateAlert($message, $type = 'error') {
    $color = ($type === 'success') ? 'green' : 'red';
    return "<script>alert('$message'); window.history.back();</script>";
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
    // Sanitize inputs with modern methods
    $name = htmlspecialchars(trim($_POST["name"] ?? ''), ENT_QUOTES, 'UTF-8');
    $email = filter_var(trim($_POST["email"] ?? ''), FILTER_SANITIZE_EMAIL);
    $dob = trim($_POST["dob"] ?? '');
    $gender = htmlspecialchars(trim($_POST["gender"] ?? ''), ENT_QUOTES, 'UTF-8');
    $pwd = $_POST["password"] ?? '';
    $pwdRepeat = $_POST["confirm_password"] ?? '';
    $town = htmlspecialchars(trim($_POST["town"] ?? ''), ENT_QUOTES, 'UTF-8');

    // Check for empty inputs first
    if (empty($name)) {
        echo generateAlert("Name cannot be empty");
        exit();
    }
    if (empty($email)) {
        echo generateAlert("Email cannot be empty");
        exit();
    }
    if (empty($dob)) {
        echo generateAlert("Date of birth cannot be empty");
        exit();
    }
    if (empty($gender)) {
        echo generateAlert("Gender cannot be empty");
        exit();
    }
    if (empty($pwd)) {
        echo generateAlert("Password cannot be empty");
        exit();
    }
    if (empty($pwdRepeat)) {
        echo generateAlert("Confirm password cannot be empty");
        exit();
    }
    if (empty($town)) {
        echo generateAlert("Town cannot be empty");
        exit();
    }

    // reCAPTCHA verification
    if (!isset($_POST['g-recaptcha-response']) || empty($_POST['g-recaptcha-response'])) {
        echo generateAlert("Please complete the reCAPTCHA verification");
        exit();
    }

    $secretKey = '6LcbHM8qAAAAADjudJJyldZ8JfXXAXm697OW-pdO';
    $verifyResponse = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response=" . urlencode($_POST['g-recaptcha-response']));
    $response = json_decode($verifyResponse);

    if (!$response->success) {
        echo generateAlert("reCAPTCHA verification failed");
        exit();
    }

    // Validation checks
    try {
        if (invalidUid($email)) {
            echo generateAlert("Please enter a valid email address");
            exit();
        }

        if (invalidName($name)) {
            echo generateAlert("Name must be 2-50 characters and contain only letters");
            exit();
        }

        if (invalidDOB($dob)) {
            echo generateAlert("Please enter a valid date of birth (not in future)");
            exit();
        }

        if (invalidGender($gender)) {
            echo generateAlert("Please select a valid gender");
            exit();
        }

        if (invalidTown($town)) {
            echo generateAlert("Town must be 2-50 characters and contain only letters");
            exit();
        }

        if (pwdMatch($pwd, $pwdRepeat)) {
            echo generateAlert("Passwords do not match");
            exit();
        }

        if (uidExists($conn, $name, $email)) {
            echo generateAlert("Email already registered");
            exit();
        }

        // If all validations pass, create user
        createUser($conn, $name, $email, $dob, $gender, $pwd, $town);
        echo "<script>alert('Registration successful!'); window.location.href='../login.php';</script>";

    } catch (Exception $e) {
        echo generateAlert("An error occurred: " . $e->getMessage());
        exit();
    }

} else {
    echo "<script>window.location.href='../login.php';</script>";
    exit();
}
?>