<?php
session_start(); // Keep form values if failed

if (isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirmPassword'])) {

    global $cnx;
    include('include/connect.inc.php');

    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Check if mail already used
    $query = $cnx->query('SELECT * FROM users WHERE email = "'.$email .'"');
    if ($query->rowCount() > 0) {

      // log email already used
      $query = $cnx->prepare('INSERT INTO logs_connect (description) VALUES (:description)');
      $description = "Attempt to create an account with the already used email : ".$email;
      $query->bindParam(':description', $description, PDO::PARAM_STR);
      $query->execute();

      $_SESSION['form_data'] = $_POST;
      header('Location: createaccount.php?error=email_already_used');
      exit();
    }

    if (empty($_POST['homeplanet'])) {
        $homeplanet = null;
    } else {
        $homeplanet = $_POST['homeplanet'];
    }

    if (empty($_POST['workplanet'])) {
        $workplanet = null;
    } else {
        $workplanet = $_POST['workplanet'];
    }

    $errors = "";

    // Check if passwords match
    if ($password !== $confirmPassword) {
      $_SESSION['form_data'] = $_POST;
      header('Location: createaccount.php?error=password_mismatch');
      exit();
    }

    // Tests on password strength
    if (strlen($password) < 12) {
      $errors = $errors."1";
    }

    // Test if contains at least one uppercase letter
    if (!preg_match('/[A-Z]/', $password)) {
      $errors = $errors."2";
    }

    // Test if contains at least one lowercase letter
    if (!preg_match('/[a-z]/', $password)) {
      $errors = $errors."3";
    }

    // Test if contains at least one number
    if (!preg_match('/[0-9]/', $password)) {
      $errors = $errors."4";
    }

    // Test if contains at least one special character
    if (!preg_match('/[^a-zA-Z0-9]/', $password)) {
      $errors = $errors."5";
    }

    if (!empty($errors)) {
      $_SESSION['form_data'] = $_POST;
      header('Location: createaccount.php?error='.$errors);
      exit();

    } else {
      $query = $cnx->prepare('INSERT INTO users (firstname, lastname, email, password, homeplanet, workplanet, previous_passwords) VALUES (:firstname, :lastname, :email, :password, :homeplanet, :workplanet, :previous_passwords)');
      $query->bindParam(':firstname', $firstname, PDO::PARAM_STR);
      $query->bindParam(':lastname', $lastname, PDO::PARAM_STR);
      $query->bindParam(':email', $email, PDO::PARAM_STR);
      $password = password_hash($password, PASSWORD_DEFAULT);
      $query->bindParam(':password', $password, PDO::PARAM_STR);
      $query->bindParam(':homeplanet', $homeplanet, PDO::PARAM_STR);
      $query->bindParam(':workplanet', $workplanet, PDO::PARAM_STR);
      $array = [$password];
      $serialized_array = serialize($array);
      $query->bindParam(':previous_passwords', $serialized_array, PDO::PARAM_STR);

      $query->execute();

      // log account created
      $query = $cnx->prepare('INSERT INTO logs_connect (description) VALUES (:description)');
      $description = "User ".$email." created an account, pending verification";
      $query->bindParam(':description', $description, PDO::PARAM_STR);
      $query->execute();

      unset($_SESSION['form_data']); // Remove form values
      header('Location: sendmail.php?destination='.$email);
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

<?php
$firstname = "";
$lastname = "";
$email = "";
$homeplanet = "";
$workplanet = "";

if (isset($_SESSION['form_data'])) {
    $firstname = $_SESSION['form_data']['firstname'];
    $lastname = $_SESSION['form_data']['lastname'];
    $email = $_SESSION['form_data']['email'];
    $homeplanet = $_SESSION['form_data']['homeplanet'];
    $workplanet = $_SESSION['form_data']['workplanet'];
    unset($_SESSION['form_data']);
}
?>

<div class="RegisterDiv">
  <h1>Register</h1>

  <?php
  if (isset($_GET["error"])) {
    if ($_GET["error"] == "email_already_used") {
      echo "<p class='error' >Email already used</p>";
    } else if ($_GET["error"] == "password_mismatch") {
      echo "<p class='error'>Passwords do not match</p>";
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

  <form action="createaccount.php" method="post">

    <input type="text" id="firstname" name="firstname" placeholder="First name" value="<?php echo htmlspecialchars($firstname); ?>" required>

    <input type="text" id="lastname" name="lastname" placeholder="Name" value="<?php echo htmlspecialchars($lastname); ?>" required>

    <input type="email" id="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>" required>

    <div class="LoginDivPassword">
      <input type="password" id="password" name="password" placeholder="Password" required>
      <input class="Checkbox" type="checkbox" onclick="showPassword()">
    </div>

    <div class="LoginDivPassword">
      <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm password" required>
      <input class="Checkbox" type="checkbox" onclick="showConfirmPassword()">
    </div>

    <p>Mandatory information</p>

    <input type="text" id="homeplanet" name="homeplanet" placeholder="Home planet" value="<?php echo htmlspecialchars($homeplanet); ?>">

    <input type="text" id="workplanet" name="workplanet" placeholder="Work planet" value="<?php echo htmlspecialchars($workplanet); ?>">

    <p>Optional information</p>

    <input class="inputSubmit" type="submit" value="Create account">

  </form>

  <a href="index.php">Already have an account ? Login</a>

</div>

</body>

</html>