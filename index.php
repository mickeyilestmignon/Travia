<?php
global $cnx;
include 'include/connect.inc.php';
include 'class/ship.php';
include 'class/planet.php';
include 'class/trip.php';
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

<form action="search_results.php" method="get">
    <label for="search">Planète de départ</label>
    <input type="text" id="depart" name="depart">

    <label for="search">Planète de d'arrivée</label>
    <input type="text" id="arrivee" name="arrivee">

    <input type="submit" value="Rechercher">
</form>

<script>
    $(function() {
        $("#depart").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "script/autocomplete_search.php",
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

        $("#arrivee").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "script/autocomplete_search.php",
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

//print_ships_in_database($cnx);
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

//print_planets_in_database($cnx);
?>

<h1 id="voyages">Les voyages</h1>

<?php
//print_trips_in_database();
?>

</body>

</html>