<?php
if (!isset($_GET['depart']) && isset($_GET['arrivee'])) {
    header("Location: index.php");
}
global $cnx;
include("include/connect.inc.php");

$depart = $_GET['depart'];
$arrivee = $_GET['arrivee'];
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

<?php
?>

</body>
</html>