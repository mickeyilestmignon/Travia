<?php
if (!isset($_GET['depart']) && isset($_GET['arrivee'])) {
    header("Location: index.php");
}
global $cnx;
include("include/connect.inc.php");
include 'class/ship.php';
include 'class/planet.php';
include 'class/trip.php';

$departure = getPlanetInfo($_GET['departure']);
$arrival = getPlanetInfo($_GET['arrival']);

function toHoursMintues($duration) : string {
    $hours = floor($duration);
    $minutes = floor(($duration - $hours) * 60);
    return $hours . 'h ' . $minutes . 'm';
}
?>

<!DOCTYPE html>
<html lang="en" onload="loadFont()">
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

    <title>Results</title>
</head>

<script>
    function sort() {
        // Get the value of the selected radio button
        var selectedValue = document.querySelector('input[name="speed"]:checked').value;
        // put in url parameter, replace if already exists
        var url = new URL(window.location.href);
        url.searchParams.set('sort', selectedValue);
        window.location.href = url.href;
    }
</script>

<body>

<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand"><img src="images/logo.png" alt="" width="250"></a>
        </div>
        <ul class="nav navbar-nav">
            <li>Sheev Palpatine</li>
        </ul>
    </div>
</nav>

<?php
include('include/fontSelector.php');
?>

<div class="box">
    <p class="p1_name"> <?php echo $departure["name"]; ?> </p>
    <p class="p2_name"> <?php echo $arrival["name"]; ?> </p>

    <?php
    echo '<img class="p1_image" src="https://static.wikia.nocookie.net/starwars/images/'.substr(md5($departure['image']), 0, 1).'/'.substr(md5($departure['image']), 0, 2).'/'.$departure['image'].'" alt="">';
    echo '<img class="p2_image" src="https://static.wikia.nocookie.net/starwars/images/'.substr(md5($arrival['image']), 0, 1).'/'.substr(md5($arrival['image']), 0, 2).'/'.$arrival['image'].'" alt="">';

    echo '<p class="p1_description">'.$departure['region']."<br>".$departure['sector'].'</p>';
    echo '<p class="p2_description">'.$arrival['region']."<br>".$arrival['sector'].'</p>';
    ?>

    <img class="trip-symbol" src="images/trip.png" alt="">

    <a href="index.php"><div class="submit">Back to search</div></a>
</div>

<div class="results">

    <p class="title">Results</p>

    <?php
    $distance = sqrt(pow(($departure["X"]+$departure["SubGridX"])*6 - ($arrival["X"]*$arrival["SubGridX"])*6, 2) + pow(($departure["Y"]+$departure["SubGridY"])*6 - ($arrival["Y"]*$arrival["SubGridY"])*6, 2));
    ?>

    <p class="distance">Distance :  <?php echo round($distance, 3); ?> billions kilometers</p>

    <div class="radio" onchange="sort()">
        <div class="sortButton">
            <input type="radio" id="fastest" name="speed" value="fastest" <?php if (isset($_GET["sort"])) { if ($_GET["sort"] == "fastest") { echo "checked"; }} ?> >
            <label for="fastest">Fastest</label>
        </div>
        <div class="sortButton">
            <input class="sortButton" type="radio" id="cheapest" name="speed" value="cheapest" <?php if (isset($_GET["sort"])) { if ($_GET["sort"] == "cheapest") { echo "checked"; }} ?> >
            <label for="cheapest">Cheapest</label>
        </div>
    </div>

    <?php

    if (isset($_GET['sort'])) {
        if ($_GET['sort'] == 'fastest') {
            $sort = " ORDER BY speed_kmh DESC";
        } else {
            $sort = " ORDER BY speed_kmh ASC";
        }
    } else {
        $sort = "";
    }

    $stmt = $cnx->prepare("SELECT * FROM ships".$sort);
    $stmt->execute();
    $ships = $stmt->fetchAll();

    for ($i = 0; $i < count($ships); $i++) {
        $ship = new Ship($ships[$i]['id'], $ships[$i]['name'], $ships[$i]['camp'], $ships[$i]['speed_kmh'], $ships[$i]['capacity']);
        $duration = $distance*1000000000 / $ship->getSpeedKmh();
        $cost = 100 * $distance;
        $cost = round($cost + ($cost * ($ship->getSpeedKmh() - 1080000000) / 1080000000), 2);

        ?>

        <div class="ship">
            <div class="ship-name"><?php echo $ship->getName(); ?></div>
            <div class="info">
                Duration : <?php echo toHoursMintues($duration); ?><br>
                Price : <?php echo $cost; ?> credits
            </div>
            <div class="add-basket">
                <img src="images/add-to-cart.png">
            </div>
        </div>

        <?php
    }

    ?>

</div>

</body>

</html>