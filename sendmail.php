<?php
global $cnx;
include "script/mailer.php";

if (isset($_GET["destination"])) {

  $email = $_GET["destination"];
  $object = "Mail verification";
  $code = rand(100000, 999999);
  $body = "Your verification code is: " . $code;

  sendmail($email, $object, $body);

  $query = $cnx->prepare('UPDATE users SET verification_code = :code WHERE email = :email');
  $query->bindParam(':code', $code, PDO::PARAM_INT);
  $query->bindParam(':email', $email, PDO::PARAM_STR);
  $query->execute();

  header('Location: mailconfirm.php?destination='.$email);
}
?>