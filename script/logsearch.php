<?php
if (isset($_GET['departure']) && isset($_GET['arrival'])) {
    global $cnx;
    include("../include/connect.inc.php");

    $query_dep = $cnx->query("SELECT id FROM planets WHERE name = '".$_GET['departure']."'");
    $query_arr = $cnx->query("SELECT id FROM planets WHERE name = '".$_GET['arrival']."'");
    $arrival = $query_arr->fetchAll()[0]['id'];
    $departure = $query_dep->fetchAll()[0]['id'];
    $date = date("Y-m-d h:i:sa");

    $stmt = $cnx->prepare("INSERT INTO logs (id_planete_depart, id_planete_arrivee, time) VALUES (:id_planet_departure, :id_planet_arrival, :time)");
    $stmt->bindParam(':id_planet_departure', $departure, PDO::PARAM_INT);
    $stmt->bindParam(':id_planet_arrival', $arrival, PDO::PARAM_INT);
    $stmt->bindParam(':time', $date);
    $stmt->execute();

    header("Location: ../search_results.php?departure=$departure&arrival=$arrival");
}
else {
    header("Location: ../index.php");
}