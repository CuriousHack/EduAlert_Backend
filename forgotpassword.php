<?php

include "./config.php";
require "./vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $email = mysqli_real_escape_string($db, $_POST['email']);
    // ensure that the user exists on our system
    $query = "SELECT email FROM records WHERE email = '$email'";
    $results = mysqli_query($db, $query);
  
    if (empty($email)) {
      sendReply(400, "Your email is required");
    }else if(mysqli_num_rows($results) <= 0) {
      sendReply(400, "Sorry, this email does not exist on EduAlert!");
    }

    // generate a unique random token of length 100
    $token = bin2hex(random_bytes(50));
    $current_time = time();
    $new_time = $current_time + 15 * 60;
    $new_datetime = date('Y-m-d H:i:s', $new_time);

      // store token in the password-reset database table against the user's email
      $sql = "INSERT INTO password_resets(email, token, exp_time) VALUES ('$email', '$token', '$new_datetime')";
      mysqli_query($db, $sql);
      

    //testing with php mailer

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

  // send by  email
  $mail->setFrom('akandelateef0@gmail.com', 'Password Reset');
  // get email from input
  $mail->addAddress($email);
  //$mail->addReplyTo('lamkaizhe16@gmail.com');

  // HTML body
  $mail->isHTML(true);
  $mail->Subject="EduAlert Password Reset";
  $mail->Body="<b>Dear Subscriber</b>
  <h3>We received a request to reset your password.</h3>
  <p>Hi there, click on this <a href=\"resetpassword.php?token=" . $token . "\">link</a> to reset your password on EduAlert</p>
  <br><br>
  <p>With regrads,</p>
  <b>EduAlert Team</b>";

  $mail->send();

  //echo("Email sent!");
  if(!$mail->send()){
    sendReply(400, "invalid Email Address!");
  }
  else{
    sendReply(200,"Please login into your email account and click on the link we sent to reset your password");
  }
  }


  
?>