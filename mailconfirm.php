<?php
  use lib\PHPMailer\src\PHPMailer;

  require 'lib/PHPMailer/src/PHPMailer.php';
  require 'lib/PHPMailer/src/SMTP.php';
  require 'lib/PHPMailer/src/Exception.php';

  function sendmail($email, $object, $body){

    include 'cnx.php';
    global $e_mail;

    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 465;
    $mail->Username = $e_mail;
    $mail->Password = $password_mail;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;

    $mail->setFrom($e_mail, 'Riche Abdlerahim');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = $object;
    $mail->Body = $body;

    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    $mail->send();
  }
?>

<!DOCTYPE html>

<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="index.css"/>

  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

  <title>Mail verification</title>
</head>

<body>

<div class="MailVerification">
  <h1>Mail verification</h1>

  <form action="mailconfirm.php" method="post">

    <input type="number" maxlength="6" minlength="6">

    <input class="inputSubmit" type="submit" value="Send verification code">

  </form>

</body>

</html>