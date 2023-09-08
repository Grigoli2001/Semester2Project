<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer-master/src/Exception.php';
require './PHPMailer-master/src/PHPMailer.php';
require './PHPMailer-master/src/SMTP.php';




// Get the form data from the decoded array
$_SESSION['email'];
$_SESSION['randompassword'];
if (empty($_SESSION['email']) || empty($_SESSION['randompassword'])) {
    http_response_code(400);
    echo json_encode(array('error' => 'All fields are required'));
    exit;
}


$mail = new PHPMailer(true); // Passing `true` enables exceptions
try {
    //Server settings
    $mail->SMTPDebug = 2; // Enable verbose debug output
    $mail->isSMTP(); // Set mailer to use SMTP
    $mail->Host = 'smtp-relay.brevo.com'; // Specify main and backup SMTP servers
    $mail->SMTPAuth = true; // Enable SMTP authentication
    $mail->Username = "gegapacacia@gmail.com";
    $mail->Password = "WOjKTVqDsIJGB0vx";
    $mail->Port = 587; // TCP port to connect to
    //Recipients
    $mail->setFrom('StudentPortal@Epita.fr', 'EPITA'); //This is the email your form sends From
    $mail->addAddress($_SESSION['email'], 'User'); // Add a recipient address
    //Content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = 'Password For Student Portal';
    $mail->Body = "Welcome to EPITA Student Portal\n\n\n To Login in your student portal Use your Epita Email - " . $_SESSION['epita_email'] . "Your Password to the epita portal is - " . $_SESSION['randompassword'];
    if ($mail->send()) {
        unset($_SESSION['epita_email']);
        unset($_SESSION['email']);
        unset($_SESSION['randompassword']);
        echo ('mail has been sent');
    } else {
        echo ('mail cannot be sent');
    }

} catch (Exception $e) {
    echo ('mail can not be send');

    echo ($mail->ErrorInfo);
}


?>