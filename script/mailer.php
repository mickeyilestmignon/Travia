<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "vendor/phpmailer/phpmailer/src/PHPMailer.php";
require "vendor/phpmailer/phpmailer/src/Exception.php";
require "vendor/phpmailer/phpmailer/src/SMTP.php";

global $cnx;
include('include/connect.inc.php');

function sendmail($email, $object, $body){

  $mail = new PHPMailer(true);
  $e_mail = "achireceseiandrei@gmail.com";

  $mail->isSMTP();
  $mail->SMTPAuth = true;
  $mail->Host = "smtp.gmail.com";
  $mail->Port = 465;
  $mail->Username = $e_mail;
  $mail->Password = "lobl ycxf kxci nhzb";
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;

  $mail->setFrom($e_mail, 'Travia - No reply');
  $mail->addAddress($email);

  $mail->isHTML(true);
  $mail->Subject = $object;
  $mail->Body = $body;

  $mail->CharSet = 'UTF-8';
  $mail->Encoding = 'base64';

  $mail->send();
}