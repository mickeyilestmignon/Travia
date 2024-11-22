<?php
global $cnx;
include 'include/connect.inc.php';
include 'class/ship.php';
include 'class/planet.php';
include 'class/trip.php';
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
    <title>Index</title>
</head>
<body>

<nav>
    <a href="#recherche">Search</a>
    <a href="#vaisseaux">Ships</a>
    <a href="#planetes">Planets</a>
    <a href="#voyages">Trips</a>
</nav>

<br><br>

<h1 id="recherche">Search</h1>

<form action="script/logsearch.php" method="get">
    <label for="search">Departure planet
        <input type="text" id="depart" name="depart">
    </label>

    <label for="search">Arrival planet
        <input type="text" id="arrivee" name="arrivee">
    </label>

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

//print_planets_in_database();
?>

<h1 id="voyages">Trips</h1>

<?php
//print_trips_in_database();
?>

</body>

</html>