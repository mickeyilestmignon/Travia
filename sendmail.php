<?php
global $cnx;
include "script/mailer.php";

if (isset($_GET["destination"])) {

  $email = $_GET["destination"];
  $object = "Mail verification";
  $code = rand(100000, 999999);
  $body = "Your verification code is: " . $code;

  sendmail($email, $object, $body);

  $query = $cnx->prepare('UPDATE users SET verification_code = :code, send_time = :send_time WHERE email = :email');
  $query->bindParam(':code', $code, PDO::PARAM_INT);
  $unixTime = time();
  $query->bindParam(':send_time', $unixTime, PDO::PARAM_INT);
  $query->bindParam(':email', $email, PDO::PARAM_STR);
  $query->execute();

  // log
  $query = $cnx->prepare('INSERT INTO logs_connect (description) VALUES (:description)');
  $description = "User ".$email." received a verification code";
  $query->bindParam(':description', $description, PDO::PARAM_STR);
  $query->execute();

  header('Location: mailconfirm.php?destination='.$email);
}