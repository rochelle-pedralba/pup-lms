<?php
declare(strict_types=1);

date_default_timezone_set('Asia/Manila');

require_once 'includes/dbh_inc.php';
require_once 'includes/execute_query_inc.php';
require_once 'includes/error_model_inc.php';
require 'vendor/autoload.php'; // Make sure to have Composer installed and PHPMailer loaded

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function getUserByEmail(object $mysqli, string $email): ?array
{
    $query = "SELECT ua.user_ID, ua.user_Password, ui.account_Status
              FROM USER_ACCESS ua
              JOIN USER_INFORMATION ui ON ua.user_ID = ui.user_ID
              WHERE ui.email = ?";

    $queryResult = executeQuery($mysqli, $query, "s", [$email]);

    if (!$queryResult['success']) {
        $error_message = "An error has occurred. Please try again later or contact the administrator.";
        redirectWithError($error_message);
        exit;
    }

    return $queryResult['result'] ? $queryResult['result']->fetch_assoc() : null;
}

function sendResetEmail(string $email, string $resetToken)
{
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.your-email-provider.com'; // Replace with your SMTP host
        $mail->SMTPAuth = true;
        $mail->Username = 'your-email@example.com'; // Replace with your SMTP username
        $mail->Password = 'your-email-password';   // Replace with your SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('no-reply@yourdomain.com', 'Mailer'); // Replace with your "from" email
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Request';
        $mail->Body = 'Please click the following link to reset your password: <a href="http://yourdomain.com/reset_password.php?token=' . $resetToken . '">Reset Password</a>';
        $mail->AltBody = 'Please click the following link to reset your password: http://yourdomain.com/reset_password.php?token=' . $resetToken;

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];

    $user = getUserByEmail($mysqli, $email);

    if ($user === null) {
        $error_message = "No account found with that email address.";
        redirectWithError($error_message);
        exit;
    }

    if ($user['account_Status'] != '1') {
        $error_message = "Your account must be activated. Please contact the administrator.";
        redirectWithError($error_message);
        exit;
    }

    $resetToken = bin2hex(random_bytes(16));
    $query = "INSERT INTO password_resets (user_ID, reset_Token, reset_Expires) VALUES (?, ?, ?)";
    $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
    $params = [$user['user_ID'], $resetToken, $expires];

    $insertResult = executeQuery($mysqli, $query, "sss", $params);

    if (!$insertResult['success']) {
        $error_message = "An internal error has occurred. Please try again later or contact the administrator.";
        redirectWithError($error_message);
        exit;
    }

    sendResetEmail($email, $resetToken);

    header("Location: ../pages/reset_password_sent.html");
    exit;
}

$error_message = "Form submission method not allowed";
redirectWithError($error_message);
exit;
?>