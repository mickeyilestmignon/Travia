<?php
session_start();
if (!isset($_SESSION['email'])) {
  header('Location: index.php');
  exit();
}

if (isset($_POST['password']) && isset($_POST['confirmPassword'])) {

  $password = $_POST['password'];
  $confirmPassword = $_POST['confirmPassword'];

  $errors = "";

  // Check if passwords match
  if ($password !== $confirmPassword) {
    header('Location: newpassword.php?error=password_mismatch');
    exit();
  }

  // Tests on password strength
  if (strlen($password) < 12) {
    $errors = $errors . "1";
  }

  // Test if contains at least one uppercase letter
  if (!preg_match('/[A-Z]/', $password)) {
    $errors = $errors . "2";
  }

  // Test if contains at least one lowercase letter
  if (!preg_match('/[a-z]/', $password)) {
    $errors = $errors . "3";
  }

  // Test if contains at least one number
  if (!preg_match('/[0-9]/', $password)) {
    $errors = $errors . "4";
  }

  // Test if contains at least one special character
  if (!preg_match('/[^a-zA-Z0-9]/', $password)) {
    $errors = $errors . "5";
  }

  if (!empty($errors)) {
    header('Location: newpassword.php?error='.$errors);
    exit();

  } else {

    global $cnx;
    include 'include/connect.inc.php';

    $email = $_SESSION['email'];

    $query = $cnx->prepare('SELECT * FROM users WHERE email = :email');
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch();

    $previous_passwords = unserialize($result["previous_passwords"]);

    foreach ($previous_passwords as $prev) {
      if (password_verify($password, $prev)) {
        header('Location: newpassword.php?error=previous_password');
        exit();
      }
    }

    $password = password_hash($password, PASSWORD_DEFAULT);

    if (count($previous_passwords) < 3) {
      $previous_passwords[] = $password;
    } else {
      $previous_passwords[0] = $previous_passwords[1];
      $previous_passwords[1] = $previous_passwords[2];
      $previous_passwords[2] = $password;
    }
    $previous_passwords = serialize($previous_passwords);

    $query = $cnx->prepare('UPDATE users SET password = :password, previous_passwords = :previous_passwords WHERE email = :email');
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->bindParam(':previous_passwords', $previous_passwords, PDO::PARAM_STR);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();

    // log
    $query = $cnx->prepare('INSERT INTO logs_connect (description) VALUES (:description)');
    $description = "User " . $email . " succesfully changed its password";
    $query->bindParam(':description', $description, PDO::PARAM_STR);
    $query->execute();

    header('Location: passwordchanged.php?destination='.$email);
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

  <title>New password</title>
</head>

<body>

<div class="MailVerification">
  <h1>Create your new password</h1>

  <?php
  if (isset($_GET["error"])) {
    if ($_GET["error"] == "email_already_used") {
      echo "<p class='error' >Email already used</p>";
    } else if ($_GET["error"] == "password_mismatch") {
      echo "<p class='error'>Passwords do not match</p>";
    } else if ($_GET["error"] == "previous_password") {
      echo "<p class='error'>Password must be different from three previous</p>";
    } else {
      echo "<p class='error'>Password must contain at least ";
      for ($i = 0; $i < strlen($_GET["error"]); $i++) {
        if ($_GET["error"][$i] == "1") {
          echo "12 characters";
        } else if ($_GET["error"][$i] == "2") {
          echo "one uppercase letter";
        } else if ($_GET["error"][$i] == "3") {
          echo "one lowercase letter";
        } else if ($_GET["error"][$i] == "4") {
          echo "one number";
        } else if ($_GET["error"][$i] == "5") {
          echo "one special character";
        }
        if ($i < strlen($_GET["error"]) - 1) {
          echo ", ";
        } else {
          echo ".</p>";
        }
      }
    }
  }
  ?>

  <form action="newpassword.php" method="post">

    <p>Enter your new password, must be different from three previous</p>

    <div class="LoginDivPassword">
      <input type="password" id="password" name="password" placeholder="Password" required>
      <input class="Checkbox" type="checkbox" onclick="showPassword()">
    </div>

    <div class="LoginDivPassword">
      <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm password" required>
      <input class="Checkbox" type="checkbox" onclick="showConfirmPassword()">
    </div>

    <input class="inputSubmit" type="submit" value="Submit">

  </form>

</div>

</body>

</html>