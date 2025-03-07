<?php
include('include/connect.inc.php');
global $cnx;

if (isset($_POST['email']) && isset($_POST['password'])) {

  $email = $_POST['email'];
  $password = $_POST['password'];

  $query = $cnx->prepare('SELECT * FROM users WHERE email = :email');
  $query->bindParam(':email', $email, PDO::PARAM_STR);
  $query->execute();
  $result = $query->fetch();

  if ($result && password_verify($password, $result['password'])) {

    if ($result['verified'] == 0) {
      header('Location: index.php?error=unverified&destination='.$email);
      exit();
    }

    session_start([
      'cookie_lifetime' => 3600*2,
      'cookie_secure'   => true,
      'cookie_httponly' => true,
      'use_strict_mode' => true,
    ]);
    $_SESSION['email'] = $email;

    // log connexion
    $query = $cnx->prepare('INSERT INTO logs_connect (description) VALUES (:description)');
    $description = "User ".$email." logged in";
    $query->bindParam(':description', $description, PDO::PARAM_STR);
    $query->execute();

    header('Location: search.php');
    exit();

  } else {

    // log
    $query = $cnx->prepare('INSERT INTO logs_connect (description) VALUES (:description)');
    $description = "A connexion failed on email : ".$email;
    $query->bindParam(':description', $description, PDO::PARAM_STR);
    $query->execute();

    header('Location: index.php?error=incorrect_login');
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
    <link rel="stylesheet" type="text/css" href="logincreate.css"/>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="script/showPassword.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <title>Login</title>
</head>

<body>

    <div class="LoginDiv">
      <h1>Login</h1>

      <?php
      if (isset($_GET['error'])) {
        if ($_GET['error'] == 'incorrect_login') {
          echo '<p class="error">Incorrect email or password</p>';
        } else if ($_GET['error'] == 'unverified') {
          $email = urldecode($_GET["destination"]);
          echo '<p class="error">Your account is not verified yet, <a href="mailconfirm.php?destination='.$email.'">enter confirmation code here</a></p>';
        }
      }
      ?>

      <form action="index.php" method="post">

        <input type="email" id="email" name="email" placeholder="Email" required>

        <div class="LoginDivPassword">
          <input type="password" id="password" name="password" placeholder="Password" required>
          <input class="Checkbox" type="checkbox" onclick="showPassword()">
        </div>

        <div class="LoginDivOptions">
          <label for="rememberMe">
            <input type="checkbox" id="rememberMe" name="rememberMe" checked>
            Remember me</label>
          <a href="forgotpassword.php">Forgot password ?</a>
        </div>

        <input class="inputSubmit" type="submit" value="Login">
      </form>

      <a href="createaccount.php">Don't have an account ? Register</a>

    </div>

</body>

</html>