<?php
include_once 'includes/config_session_inc.php';
include_once 'includes/dbh_inc.php';
include_once 'includes/error_model_inc.php';
include_once '../vendor/phpmailer/phpmailer/PHPMailer.php';
include_once '../vendor/phpmailer/phpmailer/SMTP.php';
include_once '../vendor/phpmailer/phpmailer/Exception.php';

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

    // Prepare user_information values
    $date_Created = $date_Assigned;
    $time_Created = $time_Access;
    $account_Status = 1; // Assuming 1 means active
    $id_Number = $user_id; // Assuming id_Number is same as user_ID or generate if different

    // Start transaction
    $mysqli->begin_transaction();

    try {

        // Insert into user_information table
        $sql3 = "INSERT INTO user_information (user_ID, account_Status, date_Created, time_Created, id_Number) VALUES (?, ?, ?, ?, ?)";
        $stmt3 = $mysqli->prepare($sql3);
        $stmt3->bind_param("sisss", $user_id, $account_Status, $date_Created, $time_Created, $id_Number);
        $stmt3->execute();

        // Insert into user_access table
        //$sql1 = "INSERT INTO user_access (user_ID, user_Password, last_Access, time_Access, first_Access) VALUES (?, ?, ?, ?, ?)";
        $sql1 = "INSERT INTO user_access (user_ID, user_Password) VALUES (?, ?)";
        $stmt1 = $mysqli->prepare($sql1);
        //$stmt1->bind_param("sssss", $user_id, $hashed_password, $last_Access, $time_Access, $first_Access);
        $stmt1->bind_param("ss", $user_id, $hashed_password);
        $stmt1->execute();

        // Insert into user_role table
        $sql2 = "INSERT INTO user_role (user_ID, user_Role, date_Assigned, previous_Role, date_Change) VALUES (?, ?, ?, ?, ?)";
        $stmt2 = $mysqli->prepare($sql2);
        $stmt2->bind_param("sisss", $user_id, $user_Role, $date_Assigned, $previous_Role, $date_Change);

        $stmt2->execute();

        // Commit transaction
        $mysqli->commit();

        // Send email to user
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
            $mail->SMTPAuth = true;
            $mail->Username = 'tobayerichelleann@gmail.com'; // SMTP username
            $mail->Password = 'bpsu ezxp xbga elxc'; // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('tobayerichelleann@gmail.com', 'PUP LMS');
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
        $mysqli->rollback();
        echo "Error: " . $e->getMessage();
    }

    $stmt1->close();
    $stmt2->close();
    $stmt3->close();
    $mysqli->close();
}
?>