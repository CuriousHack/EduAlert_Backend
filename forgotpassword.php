<?php

include "./config.php";
require "./vendor/autoload.php";
include "./smtp.php";

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $jsonData = file_get_contents("php://input");
    $data = json_decode($jsonData);

    $email = mysqli_real_escape_string($db, $data->email);
    // ensure that the user exists on our system
    $query = "SELECT email FROM records WHERE email = '$email'";
    $results = mysqli_query($db, $query);
  
    if (empty($email)) {
        http_response_code(400);
      echo json_encode(array('error' => 'Email is Required!'));
        exit();
    }
    else if(mysqli_num_rows($results) <= 0) {
        http_response_code(400);
      echo json_encode(array('error' => 'Sorry, This Email Does not Exist on EduAlert!'));
        exit();
    }

    // generate a unique random token of length 100
    $token = bin2hex(random_bytes(50));
    $current_time = time();
    $new_time = $current_time + 15 * 60;
    $new_datetime = date('Y-m-d H:i:s', $new_time);

      // store token in the password-reset database table against the user's email
      $sql = "INSERT INTO password_resets(email, token, exp_time) VALUES ('$email', '$token', '$new_datetime')";
      mysqli_query($db, $sql);

  // send by  email
  $mail->setFrom('akandelateef0@gmail.com', 'EduAlert');
  // get email from input
  $mail->addAddress($email);
  $mail->addReplyTo('edualertsys@gmail.com');

  // HTML body
  $mail->isHTML(true);
  $mail->Subject = "EduAlert Password Reset";
  $mail->Body = <<<HTML
<!doctype html>
<html lang="en-US">

<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <title>Reset Password Email Template</title>
    <meta name="description" content="Reset Password Email Template.">
    <style type="text/css">
        a:hover {text-decoration: underline !important;}
    </style>
</head>

<body marginheight="0" topmargin="0" marginwidth="0" style="margin: 0px; background-color: #f2f3f8;" leftmargin="0">
    <!--100% body table-->
    <table cellspacing="0" border="0" cellpadding="0" width="100%" bgcolor="#f2f3f8"
        style="@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: 'Open Sans', sans-serif;">
        <tr>
            <td>
                <table style="background-color: #f2f3f8; max-width:670px;  margin:0 auto;" width="100%" border="0"
                    align="center" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="height:80px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="text-align:center;">
                          <a href="https://edu-alert.netlify.app" title="logo" target="_blank">
                            <img width="150" src="https://edualert.skinx.skin/logo.png" title="logo" alt="logo">
                          </a>
                        </td>
                    </tr>
                    <tr>
                        <td style="height:20px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td>
                            <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0"
                                style="max-width:670px;background:#fff; border-radius:3px; text-align:center;-webkit-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);-moz-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);box-shadow:0 6px 18px 0 rgba(0,0,0,.06);">
                                <tr>
                                    <td style="height:40px;">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td style="padding:0 35px;">
                                        <h1 style="color:#1e1e2d; font-weight:500; margin:0;font-size:32px;font-family:'Rubik',sans-serif;">You have
                                            requested to reset your password</h1>
                                        <span
                                            style="display:inline-block; vertical-align:middle; margin:29px 0 26px; border-bottom:1px solid #cecece; width:100px;"></span>
                                        <p style="color:#455056; font-size:15px;line-height:24px; margin:0;">
                                            We cannot simply send you your old password. A unique link to reset your
                                            password has been generated for you. To reset your password, click the
                                            following link and follow the instructions.
                                        </p>
                                        <a href="https://edualert.skinx.skin/resetpassword.php?token=$token"
                                            style="background:#20e277;text-decoration:none !important; font-weight:500; margin-top:35px; color:#fff;text-transform:uppercase; font-size:14px;padding:10px 24px;display:inline-block;border-radius:50px;">Reset
                                            Password</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="height:40px;">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    <tr>
                        <td style="height:20px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="text-align:center;">
                            <p style="font-size:14px; color:rgba(69, 80, 86, 0.7411764705882353); line-height:18px; margin:0 0 0;">&copy; <strong>www.edu-alert.netlify.app</strong></p>
                        </td>
                    </tr>
                    <tr>
                        <td style="height:80px;">&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <!--/100% body table-->
</body>

</html>
HTML;

  if(!$mail->send()){
      http_response_code(400);
    echo json_encode(array('error' => 'Invalid Email Address!'));
        exit();
  }
  else{
      http_response_code(200);
    echo json_encode(array('success' => 'Please login into your email account and click on the link we sent to reset your password'));
  }
  }


  
?>