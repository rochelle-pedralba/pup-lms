<?php
include_once '../includes/config_session_inc.php';
include_once '../includes/dbh_inc.php';
include_once '../includes/error_model_inc.php';
include_once '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
include_once '../vendor/phpmailer/phpmailer/src/SMTP.php';
include_once '../vendor/phpmailer/phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $role = $_POST['role'];
    $email = $_POST['email'];
    $user_id = $_POST['user_id'];
    $password = $_POST['password'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare role values
    $user_Role = $role === 'faculty' ? 1 : 2;
    $previous_Role = 0;
    $date_Assigned = date('Y-m-d');
    $date_Change = date('Y-m-d');
    $last_Access = $first_Access = $date_Assigned;
    $time_Access = date('H:i:s');

    // Start transaction
    $conn->begin_transaction();

    try {
        // Insert into user_access table
        $sql1 = "INSERT INTO user_access (user_ID, user_Password, last_Access, time_Access, first_Access) VALUES (?, ?, ?, ?, ?)";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->bind_param("sssss", $user_id, $hashed_password, $last_Access, $time_Access, $first_Access);
        $stmt1->execute();

        // Insert into user_role table
        $sql2 = "INSERT INTO user_role (user_ID, user_Role, date_Assigned, previous_Role, date_Change) VALUES (?, ?, ?, ?, ?)";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("sisss", $user_id, $user_Role, $date_Assigned, $previous_Role, $date_Change);
        $stmt2->execute();

        // Commit transaction
        $conn->commit();

        // Send email to user
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com'; // Set the SMTP server to send through
            $mail->SMTPAuth = true;
            $mail->Username = 'your_email@example.com'; // SMTP username
            $mail->Password = 'your_password'; // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('your_email@example.com', 'PUP LMS');
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Your Account Information';
            $mail->Body = "Dear user,<br><br>Your account has been created.<br><br>User ID: $user_id<br>Temporary Password: $password<br><br>Please login at <a href='http://localhost/pup-lms/pages/login.html'>Login Page</a> and change your password immediately.<br><br>Regards,<br>PUP LMS";

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } catch (Exception $e) {
        // Rollback transaction if any error occurs
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

    $stmt1->close();
    $stmt2->close();
    $conn->close();
}
?>