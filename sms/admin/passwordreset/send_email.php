<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Require the necessary PHPMailer files
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = ''*********************';'; // Your Gmail address
        $mail->Password = ''*********************';'; // Your new App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->SMTPDebug = 0; // Enable verbose debug output
        $mail->Debugoutput = 'html'; // Set the debug output format

        // Recipients
        $mail->setFrom(''*********************';', 'Muindi Shop');  // replace *********** with your gmail
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Password Recovery';
        $mail->Body    = '
        <p>By clicking this <a href="http://localhost/sms/admin/emailreset.php/" target="_blank">LINK</a>
, you confirm that you have successfully reset your Password.<p>
<br><br><br><br>
        <p>If you received this email by mistake, kindly ignore it.</p>
<br>
<p>Regards,<br>Muindi<br><br><br><br>PHP Dev<br>Thank you<br><br><br><br><br>
Thank you!</p>';

$mail->send();
header('Location: /sms/admin/successreset.php');
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
