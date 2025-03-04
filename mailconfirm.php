<?php
  global $cnx;
  include('include/connect.inc.php');

  if (!isset($_GET["destination"])) {
    header('Location: createaccount.php');
    exit();
  }

  $email = urldecode($_GET["destination"]);

  if (isset($_GET["code"])) {

    $code = $_GET["code"];

    $query = $cnx->prepare('SELECT verification_code FROM users WHERE email = :email');
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch();
    $bdd_code = $result["verification_code"];

    if ($result && $code == $bdd_code) {
      $query = $cnx->prepare('UPDATE users SET verified = 1 WHERE email = :email');
      $query->bindParam(':email', $email, PDO::PARAM_STR);
      $query->execute();
      header('Location: accountcreated.php?destination='.$email);
      exit();

    } else {
      header('Location: mailconfirm.php?destination='.$email.'&error=incorect_code');
      exit();
    }
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

  <form action="mailconfirm.php" method="get">

    <label for="code">Verification code:</label>
    <input id="code" name="code" type="number" maxlength="6" minlength="6">
    <input id="destination" name="destination" type="text" value="<?php echo $email; ?>" hidden>

    <input class="inputSubmit" type="submit" value="Verify">

  </form>

</body>

</html>