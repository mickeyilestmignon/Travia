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
    <link rel="stylesheet" type="text/css" href="index.css"/>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <title>Index</title>
</head>
<body>

<nav>
    <a href="#recherche">Recherche</a>
    <a href="#vaisseaux">Vaisseaux</a>
    <a href="#planetes">Planètes</a>
    <a href="#voyages">Voyages</a>
</nav>

<br><br>

<h1 id="recherche">Recherche</h1>

<label for="search"></label><input type="text" id="search">

<h1 id="vaisseaux">Les vaiseaux</h1>

<h2>Importer des vaiseaux : (charger un json)</h2>
<form action="../TraviaProject/script/import_ships.php" method="post" enctype="multipart/form-data">
    Selectionner un fichier json de vaisseaux à importer :
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Importer les vaisseaux" name="submit">
</form><br>

<?php
if (isset($_GET['return_ships'])) {
    if ($_GET['return_ships'] == -1) {
        echo 'Erreur lors de l\'importation des vaisseaux';
    }
    else {
        echo 'Importation de ' . $_GET['return_ships'] . ' vaisseaux réussie';
    }
}

print_ships_in_database($cnx);
?>

<h1 id="planetes">Les planètes</h1>

<h2>Importer des planètes : (charger un json)</h2>
<form action="../TraviaProject/script/import_planets.php" method="post" enctype="multipart/form-data">
    Selectionner un fichier json de planètes à importer :
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Importer les planètes" name="submit">
</form><br>

<?php
if (isset($_GET['return_planets'])) {
    if ($_GET['return_planets'] == -1) {
        echo 'Erreur lors de l\'importation des planètes';
    }
    else {
        echo 'Importation de ' . $_GET['return_planets'] . ' planètes réussie';
    }
}

print_planets_in_database($cnx);
?>

<h1 id="voyages">Les voyages</h1>

<?php
$stmt = $cnx->query("SELECT * FROM trips");
$trips = $stmt->fetchAll();
?>
<table>
    <tr><th>planete_depart</th><th>planete_arrivee</th><th>id_ship</th><th>day</th><th>time</th></tr>
    <?php
    foreach ($trips as $trip) {
        echo '<tr><td>' . $trip['planete_depart'] . '</td><td>' . $trip['planete_arrivee'] . '</td><td>' . $trip['id_ship'] . '</td><td>' . $trip['day'] . '</td><td>' . $trip['time'] . '</td></tr>';
    }
    ?>
</table>

</body>

<script>
    $(function() {
        $("#search").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "search.php",
                    dataType: "json",
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        response(data);
                    }
                });
            },
            minLength: 2
        });
    });
</script>

</html>