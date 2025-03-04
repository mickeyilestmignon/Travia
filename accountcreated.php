<?php

  global $cnx;
  include "script/mailer.php";

  if (isset($_GET["destination"])) {

    $email = $_GET["destination"];
    $object = "Account succesfully created";
    $body = "Your account was succesfuly created";

    sendmail($email, $object, $body);
  }

?>

<!DOCTYPE html>

<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="index.css"/>

  <title>Account created</title>
</head>

<body>

  <h1>Your account was succesfuly created, you received a confirmation mail.</h1>

  <a href="index.php">Go to login</a>

</body>

<style>
  body {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    color: #FFFFFF;
    font-family: Arial, sans-serif;
    height: 100vh;
  }

  h1 {
    margin-bottom: 20px;
    font-size: 3vh;
  }

  a {
    text-decoration: none;
    color: white;
    font-size: 2vh;
  }
</style>

</html>