<?php
global $cnx;
include 'include/connect.inc.php';
include 'class/ship.php';
include 'class/planet.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>Index</title>
</head>
<body>

<h1>Les vaiseaux : </h1><br>
<?php
print_ships_in_database($cnx);
?>

<h1>Importer des vaiseaux : (charger un json)</h1><br>
<form action="../TraviaProject/script/import_ships.php" method="post" enctype="multipart/form-data">
    Selectionner un fichier json de vaisseaux à importer :
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Importer les vaisseaux" name="submit">
</form>
<?php
if (isset($_GET['return_ships'])) {
    if ($_GET['return_ships'] == -1) {
        echo 'Erreur lors de l\'importation des vaisseaux';
    }
    else {
        echo 'Importation de ' . $_GET['return_ships'] . ' vaisseaux réussie';
    }
}
?>

<h1>Les planètes : </h1><br>
<?php
print_planets_in_database($cnx);
?>

<h1>Importer des planètes : (charger un json)</h1><br>
<form action="../TraviaProject/script/import_planets.php" method="post" enctype="multipart/form-data">
    Selectionner un fichier json de planètes à importer :
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Importer les planètes" name="submit">
</form>
<?php
if (isset($_GET['return_planets'])) {
    if ($_GET['return_planets'] == -1) {
        echo 'Erreur lors de l\'importation des planètes';
    }
    else {
        echo 'Importation de ' . $_GET['return_planets'] . ' planètes réussie';
    }
}
?>
</body>
</html>