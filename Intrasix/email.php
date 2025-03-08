<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

function send_otp($to, $subject, $otp) {
  $mail = new PHPMailer(true);

  try {
      // Enable debugging
      $mail->SMTPDebug = 2; // Use 2 for debugging (0 in production)
      $mail->isSMTP();
      $mail->Host       = 'smtp.gmail.com';
      $mail->SMTPAuth   = true;
      $mail->Username   = 'intrasix06@gmail.com'; 
      $mail->Password   = 'upxq zhpv fmsm mohk'; // Use an App Password, NOT your Gmail password
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      $mail->Port       = 587;

      // Recipients
      $mail->setFrom('intrasix06@gmail.com', 'OTP Verification');
      $mail->addAddress($to);

      // Email Content
      $mail->isHTML(true);
      $mail->Subject = $subject;
      $mail->Body    = "<h3>Your OTP Code: <strong>$otp</strong></h3>";

      // Send Email
      if ($mail->send()) {
          return true;
      } else {
          echo "Mailer Error: " . $mail->ErrorInfo; // Show error message
          return false;
      }
  } catch (Exception $e) {
      echo "Exception Error: " . $mail->ErrorInfo; // Show error message
      return false;
  }
}

?>
