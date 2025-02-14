<?php
if (isset($_POST['email']) && isset($_POST['password'])) {
    include('include/connect.inc.php');
    global $cnx;

    $email = $_POST['email'];
    $password = hash('sha256', $_POST['password']);

    $stmt = $cnx->prepare("SELECT * FROM admin WHERE email = :email AND password = :password");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetch();

    if (!$user) {
        header('Location: search.php');
    } else {
        ?>

        <?php
        global $cnx;
        include 'include/connect.inc.php';
        include 'class/ship.php';
        include 'class/planet.php';
        include 'class/trip.php';
        include "class/cart.php";
        ?>

        <!DOCTYPE html>
        <html lang="en" onload="loadFont()">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">

            <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

            <title>Index</title>
        </head>

        <body>

        <script>
            loadFont();

            function loadImage1() {
                var image1 = document.querySelector('.image_index_1');
                image1.style.display = 'block';
            }

            function loadImage2() {
                var image2 = document.querySelector('.image_index_2');
                image2.style.display = 'block';
            }
        </script>

        <h1 id="vaisseaux">Ships</h1>

        <h2>Import ships : (load json)</h2>
        <form action="../TraviaProject/script/import_ships.php" method="post" enctype="multipart/form-data">
            Select json file to import :
            <input type="file" name="fileToUpload" id="fileToUpload">
            <input type="submit" value="Importer les vaisseaux" name="submit">
        </form><br>

        <?php
        if (isset($_GET['return_ships'])) {
            if ($_GET['return_ships'] == -1) {
                echo 'Error importing ships';
            }
            else {
                echo 'Import of ' . $_GET['return_ships'] . ' ships succesful';
            }
        }

        print_ships_in_database($cnx);
        ?>

        <h1 id="planetes">Planets</h1>

        <h2>Import planets : (load json)</h2>
        <form action="../TraviaProject/script/import_planets.php" method="post" enctype="multipart/form-data">
            Select json file to import :
            <input type="file" name="fileToUpload" id="fileToUpload">
            <input type="submit" value="Importer les planètes" name="submit">
        </form><br>

        <?php
        if (isset($_GET['return_planets'])) {
            if ($_GET['return_planets'] == -1) {
                echo 'Error importing planets';
            }
            else {
                echo 'Import of ' . $_GET['return_planets'] . ' planets succesful';
            }
        }

        print_planets_in_database();
        ?>

        </body>

        </html>

        <?php
    }
} else {
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
    <form action="admin.php" method="post">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
        <input type="submit" value="Connect">
    </form>
    </body>
    </html>

    <?php
}
?>