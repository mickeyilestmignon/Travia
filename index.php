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
    <link rel="stylesheet" type="text/css" href="index.css"/>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <title>Index</title>
</head>

<body>

<?php
include('include/navbar.php');
include('include/fontSelector.php');
include('include/cart.php');
?>

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

<form action="script/logsearch.php" method="get">

    <div class="box">
        <input onchange="loadImage1()" class="p1" type="text" id="depart" name="departure" placeholder="Departure" required>
        <input onchange="loadImage2()" class="p2" type="text" id="arrivee" name="arrival" placeholder="Arrival" required>
        <input class="submit" type="submit" value="Search">

        <img class="image_index_1" src="images/trip.png" alt="">
        <img class="image_index_2" src="images/trip.png" alt="">

    </div>

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

</body>

</html>