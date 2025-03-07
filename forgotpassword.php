<!DOCTYPE html>

<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="logincreate.css"/>

  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<?php
include "script/mailer.php";
global $cnx;
include('include/connect.inc.php');

if (isset($_POST["destination"]) && isset($_POST["code"])) { // Code entered ---------------------------------------------------------------------

  $email = urldecode($_POST["destination"]);
  $code = $_POST["code"];

  $query = $cnx->prepare('SELECT verification_code, send_time FROM users WHERE email = :email');
  $query->bindParam(':email', $email, PDO::PARAM_STR);
  $query->execute();
  $result = $query->fetch();
  $bdd_code = $result["verification_code"];
  $send_time = $result["send_time"];
  $unixTime = time();

  if ($unixTime - $send_time > 60) { // 1 minute expired
    header('Location: forgotpassword.php?destination='.$email.'&error=code_expired');
    exit();
  }

  if ($result && $code == $bdd_code) { // Correct code

    $query = $cnx->prepare('UPDATE users SET verified = 1 WHERE email = :email');
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();

    // log recovery succesful
    $query = $cnx->prepare('INSERT INTO logs_connect (description) VALUES (:description)');
    $description = "User ".$email." succesfully recovered its account";
    $query->bindParam(':description', $description, PDO::PARAM_STR);
    $query->execute();

    session_start([
      'cookie_lifetime' => 3600*2,
      'cookie_secure'   => true,
      'cookie_httponly' => true,
      'use_strict_mode' => true,
    ]);
    $_SESSION['email'] = $email;

    header('Location: newpassword.php?destination='.$email);
    exit();

  } else { // Incorrect code
    header('Location: forgotpassword.php?destination='.$email.'&error=incorect_code');
    exit();
  }

} else if (isset($_GET["destination"])) { // Email entered ---------------------------------------------------------------------

  $email = $_GET["destination"];

  if (!isset($_GET["error"])) {

    $object = "Password recovery";
    $code = rand(100000, 999999);
    $body = "Your recovery code is: " . $code;

    sendmail($email, $object, $body);

    $query = $cnx->prepare('UPDATE users SET verification_code = :code, send_time = :send_time WHERE email = :email');
    $query->bindParam(':code', $code, PDO::PARAM_INT);
    $unixTime = time();
    $query->bindParam(':send_time', $unixTime, PDO::PARAM_INT);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();

    // log recovery code sent
    $query = $cnx->prepare('INSERT INTO logs_connect (description) VALUES (:description)');
    $description = "Recovery code sent to ".$email;
    $query->bindParam(':description', $description, PDO::PARAM_STR);
    $query->execute();

  }

  ?>

    <title>Recover account</title>
  </head>

  <body>

  <div class="LoginDiv">

    <h1>Recovery code</h1>

    <?php
    if (isset($_GET["error"])) {
      if ($_GET["error"] == "incorect_code") {
        echo "<p class='error'>The code you entered is incorrect</p>";
      } else if ($_GET["error"] == "code_expired") {
        echo "<p class='error'>Your code has expired, please request another one</p>";
      }
    }
    ?>

    <form action="forgotpassword.php" method="post">

      <label for="code">Enter the recovery code sent to : <?php echo $email; ?></label>
      <input type="text" id="code" name="code" placeholder="Code" required>
      <input id="destination" name="destination" type="email" value="<?php echo $email; ?>" hidden>

      <p style="font-weight: normal">If you didn't receive anything, make sure the email you submited is correct and has an account created</p>

      <input class="inputSubmit" type="submit" value="Submit recover code">
    </form>

    <a href="forgotpassword.php?destination=<?php echo $email; ?>"><button>Send another code</button></a>

  </div>

  </body>

  </html>

  <?php

} else {
?>

  <title>Recover account</title>
</head>

<body>

<div class="LoginDiv">

  <h1>Recover your account</h1>

  <form action="forgotpassword.php" method="get">

    <input type="email" id="destination" name="destination" placeholder="Email" required>

    <input class="inputSubmit" type="submit" value="Submit recover code">
  </form>

</div>

</body>

</html>

<?php
}
?>