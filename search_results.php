<?php
session_start();
if (!isset($_SESSION['email'])) {
  header('Location: index.php');
  exit();
}

if (!isset($_GET['departure']) || !isset($_GET['arrival'])) {
    header("Location: search.php");
}

if (!isset($_COOKIE['cart'])) {
    setcookie('cart', serialize([]), time() + 7200, '/');
}

global $cnx;
include("include/connect.inc.php");
include 'class/ship.php';
include 'class/planet.php';
include 'class/trip.php';
include 'class/cart.php';

$departure = getPlanetInfo($_GET['departure']);
$arrival = getPlanetInfo($_GET['arrival']);

function toHoursMintues($duration) : string {
    $hours = floor($duration);
    $minutes = floor(($duration - $hours) * 60);
    return $hours . 'h ' . $minutes . 'm';
}

function regionToColor($region) : string {
    switch ($region) {
        case "Colonies":
            return "#FA6F74";
        case "Core":
            return "#E98900";
        case "Deep Core":
            return "#C09F06";
        case "Expansion Region":
            return "#96B011";
        case "Extragalactic":
            return "#22C104";
        case "Hutt Space":
            return "#09C47D";
        case "Inner Rim Territories":
            return "#00C4A8";
        case "Mid Rim Territories":
            return "#01BBE1";
        case "Outer Rim Territories":
            return "#00A7FF";
        case "Talcene Sector":
            return "#8F8FFF";
        case "The Centrality":
            return "#CE6FFD";
        case "Tingel Arm":
            return "#EF60DC";
        case "Wild Space":
            return "#FF60AC";
        default:
            return "white";
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

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
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

    // Keep scroll position on page reload

    document.addEventListener("DOMContentLoaded", function(event) {
        var scrollpos = localStorage.getItem('scrollpos');
        if (scrollpos) window.scrollTo(0, scrollpos, 'instant');
    });

    window.onbeforeunload = function(e) {
        localStorage.setItem('scrollpos', window.scrollY);
    };
</script>

<body>

<?php
include('include/navbar.php');
include('include/fontSelector.php');
include('include/cart.php');
?>

<div class="scroll">

<script>
    loadFont();
</script>

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

    <a href="search.php"><div class="submit">Back to search</div></a>
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
            <a href="include/addToCart.php?departure=<?php echo $_GET['departure']; ?>&arrival=<?php echo $_GET['arrival']; ?>&ship=<?php echo $ship->getId(); ?>">
                <div class="add-basket">
                    <img src="images/add-to-cart.png">
                </div>
            </a>
        </div>

        <?php
    }

    ?>

</div>

<div class="map">
    <div id="map"></div>
    <script>
        var map = L.map('map', {
            crs: L.CRS.Simple,
            minZoom: 3
        });

        var bounds = [[0,0], [125,125]];
        var maxBounds = [[-5,0], [125, 130]];

        map.fitBounds(bounds);
        map.setMaxBounds(maxBounds);

        var yx = L.latLng;

        var xy = function(x, y) {
            if (Array.isArray(x)) {    // When doing xy([x, y]);
                return yx(x[1], x[0]);
            }
            return yx(y, x);  // When doing xy(x, y);
        };
    </script>

    <?php
    $planetsPositions = getAllPlanetsInfo();
    foreach ($planetsPositions as $planet) {
        ?>
        <script>
            var planet = L.circle(xy(<?php echo (($planet["Y"]+$planet["SubGridY"])*6).", ".(($planet["X"]+$planet["SubGridX"])*6) ?>), {
                color: "<?php echo regionToColor($planet["region"]); ?>",
                fillOpacity: 1,
                radius: <?php

                    if ($planet["diameter"] >= 0 && $planet["diameter"] < 50000) {
                        echo 0.005;
                    } else if ($planet["diameter"] >= 50000 && $planet["diameter"] < 100000) {
                        echo 0.01;
                    } else if ($planet["diameter"] >= 100000 && $planet["diameter"] < 150000) {
                        echo 0.02;
                    } else if ($planet["diameter"] >= 150000 && $planet["diameter"] < 200000) {
                        echo 0.04;
                    } else if ($planet["diameter"] >= 200000 && $planet["diameter"] < 250000) {
                        echo 0.08;
                    } else if ($planet["diameter"] >= 250000) {
                        echo 0.16;
                    } else {
                        echo 0.03;
                    }

                ?>
            }).addTo(map).bindPopup('<?php
                echo $planet["name"];
                echo '<img class="popupImage" src="https://static.wikia.nocookie.net/starwars/images/'.substr(md5($planet['image']), 0, 1).'/'.substr(md5($planet['image']), 0, 2).'/'.$planet['image'].'" alt="">';
            ?>');

            <?php
                if ($planet["id"] == $_GET['departure']) {

                    $startY = (($planet["Y"]+$planet["SubGridY"])*6);
                    $startX = (($planet["X"]+$planet["SubGridX"])*6);

                    echo "L.marker(xy(".$startY.", ".$startX.")).addTo(map).bindPopup('";
                    echo $planet["name"];
                    echo '<img class="popupImage" src="https://static.wikia.nocookie.net/starwars/images/'.substr(md5($planet['image']), 0, 1).'/'.substr(md5($planet['image']), 0, 2).'/'.$planet['image'].'" alt="">';
                    echo "');";

                } else if ($planet["id"] == $_GET['arrival']) {

                    $endY = (($planet["Y"]+$planet["SubGridY"])*6);
                    $endX = (($planet["X"]+$planet["SubGridX"])*6);

                    echo "L.marker(xy(".$endY.", ".$endX.")).addTo(map).bindPopup('";
                    echo $planet["name"];
                    echo '<img class="popupImage" src="https://static.wikia.nocookie.net/starwars/images/'.substr(md5($planet['image']), 0, 1).'/'.substr(md5($planet['image']), 0, 2).'/'.$planet['image'].'" alt="">';
                    echo "');";

                }
                if (isset($startY) && isset($endY)) {
                    echo "L.polyline([xy(".$startY.", ".$startX."), xy(".$endY.", ".$endX.")], {color: 'white'}).addTo(map);";
                }
            ?>
        </script>
        <?php
    }
    ?>
</div>

</div>

<?php
include("include/footer.inc.php")
?>

</body>

</html>