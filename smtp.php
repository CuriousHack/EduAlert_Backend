<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

$mail = new PHPMailer(true);
//$mail->SMTPDebug = SMTP::DEBUG_SERVER;

$mail->isSMTP();
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Host='smtp.gmail.com';
$mail->Port=587;
$mail->SMTPAuth=true;
$mail->SMTPSecure='tls';

// email account
$mail->Username='akandelateef0@gmail.com';
$mail->Password='imtquujsskqfnlfj';

//edualertsys@gmail.com login
// zkfnvsqnmfgkigvf

//lateefs login
// imtquujsskqfnlfj
?>

